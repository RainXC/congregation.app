<?php

namespace App\SpeakersApp\Discourse\Http\Controllers;

use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Discourse\View\DiscourseCalendar;
use App\SpeakersApp\Discourse\View\DiscourseDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

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
}
