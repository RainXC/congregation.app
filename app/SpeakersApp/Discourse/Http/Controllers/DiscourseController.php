<?php

namespace App\SpeakersApp\Discourse\Http\Controllers;

use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Discourse\View\DiscourseCalendar;
use App\SpeakersApp\Discourse\View\DiscourseDetails;
use App\SpeakersApp\Speaker\Model\Speaker;
use App\SpeakersApp\Speech\Model\Speech;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;

class DiscourseController extends Controller
{
    /**
     * DiscourseController constructor.
     */
    public function __construct()
    {
        $this->middleware('apiAuth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $discourses = Discourse::where('congregationId', $user->congregation_id)
                                ->with(['congregation', 'assignments', 'commentaries'])
                                ->whereDate( 'time', '>', Carbon::today()->addDay(-1)->toDateTimeString() )
                                ->orderBy('time', 'asc')->get();

        return response()->json($discourses);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function calendar()
    {
        $user = Auth::user();
        $discourses = Discourse::where('congregationId', $user->congregation_id)
            ->with(['congregation', 'assignments', 'commentaries'])
            ->whereDate( 'time', '>', Carbon::today()->addDay(-1)->toDateTimeString() )
            ->orderBy('time', 'asc')->get();

        $view = new DiscourseCalendar($discourses);

        return response()->json($view);
    }

    public function history()
    {
        $user = Auth::user();
        $discourses = Discourse::where('congregationId', $user->congregation_id)
                                ->with(['congregation', 'assignments', 'commentaries'])
                                ->whereDate( 'time', '<=', Carbon::today()
                                ->toDateTimeString() )
                                ->orderBy('time', 'desc')
                                ->paginate(10);

        return response()->json($discourses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $discourse = new Discourse();

        $discourse->authorId       = Auth::id();
        $discourse->statusId       = 1;

        $discourse->congregationId = Input::get('congregationId');
        $discourse->speakerId      = Input::get('speakerId');
        $discourse->speechId       = Input::get('speechId');
        $discourse->time           = Input::get('time');


        $discourse->save();

        return response()->json(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discourse = Discourse::with(['congregation', 'assignments', 'commentaries'])->findOrFail($id);

        return response()->json(new DiscourseDetails($discourse));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $discourses = new Discourse();
        $discourse  = $discourses->findOrFail($id);

        $discourse->authorId       = Auth::id();
        $discourse->statusId       = Input::get('statusId');
        $discourse->congregationId = Input::get('congregationId');
        $discourse->speakerCode    = Input::get('speakerId');
        $discourse->speechCode     = Input::get('speechId');
        $discourse->time           = Input::get('time');

        $discourse->save();

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discourses = new Discourse();
        $discourse  = $discourses->findOrFail($id);

        if ( ! $discourse->delete())
        {
            return response()->json(['result'=>false, 'error'=>"Something went wrong when deleting Discourse with ID {$id}"]);
        }

        return response()->json(true);
    }

    public function setSpeech()
    {
        $discourses = new Discourse();
        $discourse = $discourses->findOrFail((int)Input::get('objectId'));

        return response()->json($discourse->setSpeech(Input::get('speechId')));
    }

    public function setSpeaker()
    {
        $discourses = new Discourse();
        $discourse = $discourses->findOrFail((int)Input::get('objectId'));

        return response()->json($discourse->setSpeaker(Input::get('speakerId')));
    }

    public function export()
    {
        $discoursesData = $speakersData = $speechesData = [];
        $discourses = Discourse::all();
        $speeches   = Speech::all();
        $speakers   = Speaker::all();

        foreach ( $discourses as $discourse ) {
            if ( $discourse->getAssignment() ) {
                $discoursesData[$discourse->time->format('Y')][] = [
                    $discourse->time->format('d-m-Y H:i'),
                    $discourse->getAssignment()->speaker->getLastname() .' '. $discourse->getAssignment()->speaker->getFirstname(),
                    $discourse->getAssignment()->speaker->congregation->getName(),
                    $discourse->getAssignment()->speech->getName(),
                    $discourse->getAssignment()->speech->getCode(),
                ];
            }
        }

        foreach ( $speeches as $speech ) {
            $speechesData[] = [
                $speech->getCode(),
                $speech->getName(),
            ];
        }

        foreach ( $speakers as $speaker ) {
            $speakersData[] = [
                $speaker->getLastname() . ' ' . $speaker->getFirstname(),
                $speaker->getPhone(),
                $speaker->congregation->getName(),
            ];
        }

        Excel::create('Публичные встречи - '.Carbon::now(), function($excel) use($discoursesData, $speechesData, $speakersData) {
            $worksheet = new LaravelExcelWorksheet();
            foreach( $discoursesData as $key=>$data ) {
                $invalidCharacters = $worksheet->getInvalidCharacters();
                $key = str_replace($invalidCharacters, '', $key);

                $excel->sheet($key, function($sheet) use($data) {
                    $sheet->freezeFirstRow();
                    $sheet->prependRow([
                        'Дата и время',
                        'Докладчик',
                        'Собрание',
                        'Название речи',
                        'Номер речи'
                    ]);
                    $sheet->fromArray($data, null, 'A4', false, false);
                    $sheet->setStyle(array(
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  15
                        )
                    ));
                    $sheet->row(1, function($row) {
                        $row->setBackground('#eeeeee');
                        $row->setFontColor('#333333');
                        $row->setFontWeight('bold');
                    });
                });
            }

            $excel->sheet('Докладчики', function($sheet) use($speakersData) {
                $sheet->freezeFirstRow();
                $sheet->prependRow([
                    'ФИО',
                    'Телефон',
                    'Собрание'
                ]);
                $sheet->fromArray($speakersData, null, 'A4', false, false);
                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  15
                    )
                ));
                $sheet->row(1, function($row) {
                    $row->setBackground('#eeeeee');
                    $row->setFontColor('#333333');
                    $row->setFontWeight('bold');
                });
            });

            $excel->sheet('Речи', function($sheet) use($speechesData) {
                $sheet->freezeFirstRow();
                $sheet->prependRow([
                    'Номер речи',
                    'Название речи'
                ]);
                $sheet->fromArray($speechesData, null, 'A4', false, false);
                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  15
                    )
                ));
                $sheet->row(1, function($row) {
                    $row->setBackground('#eeeeee');
                    $row->setFontColor('#333333');
                    $row->setFontWeight('bold');
                });
            });
        })->export('xls');
    }
}
