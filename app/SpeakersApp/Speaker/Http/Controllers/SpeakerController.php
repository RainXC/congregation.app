<?php

namespace App\SpeakersApp\Speaker\Http\Controllers;

use App\SpeakersApp\Speaker\Model\Speaker;
use App\SpeakersApp\Speaker\Repository\SpeakerRepo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class SpeakerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
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
        $speakers = Speaker::with(['status', 'discourses', 'speeches', 'congregation']);
        $value    = Input::get('search');
        if ($value) {
            $speakers->where('firstname', 'LIKE', '%'.$value.'%')
                ->orWhere('lastname', 'LIKE', '%'.$value.'%')
                ->orWhere('patronymic', 'LIKE', '%'.$value.'%')
                ->orWhere('code', 'LIKE', '%'.$value.'%');
        }

        return response()->json($speakers->get());
    }

    public function favorites()
    {
        $repo = new SpeakerRepo();
        $speakers = $repo->filterByFavorite()->get();
        return response()->json($speakers);
    }

    public function debtors()
    {
        $repo = new SpeakerRepo();
        $speakers = $repo->filterByDebt()->get();

        return response()->json($speakers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $speaker     = new Speaker();

        $speaker->authorId       = Auth::id();
        $speaker->statusId       = 1;
        $speaker->congregationId = Input::get('congregationId');
        $speaker->lastname       = Input::get('lastname');
        $speaker->firstname      = Input::get('firstname');
        $speaker->patronymic     = Input::get('patronymic');
        $speaker->code           = Input::get('code');
        $speaker->phone          = Input::get('phone');
        $speaker->email          = Input::get('email');
        $speaker->save();

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
        $speakers      = new Speaker();
        $speaker       = $speakers->findOrFail($id);

        return response()->json($speaker);
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
        $speakers = new Speaker();
        $speaker  = $speakers->findOrFail($id);

        $speaker->authorId       = Auth::id();
        $speaker->statusId       = Input::get('statusId');
        $speaker->congregationId = Input::get('congregationId');
        $speaker->lastname       = Input::get('lastname');
        $speaker->firstname      = Input::get('firstname');
        $speaker->patronymic     = Input::get('patronymic');
        $speaker->code           = Input::get('code');
        $speaker->phone          = Input::get('phone');
        $speaker->email          = Input::get('email');

        $speaker->save();

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
        $speakers = new Speaker();
        $speaker  = $speakers->findOrFail($id);

        if ( ! $speaker->delete())
        {
            return response()->json(['result'=>false, 'error'=>"Something went wrong when deleting Speaker with ID {$id}"]);
        }

        return response()->json(true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function setSpeechToSpeaker()
    {
        $speakers = new Speaker();
        $speaker = $speakers->findOrFail((int)Input::get('objectId'));

        return response()->json($speaker->addSpeech(Input::get('speechId')));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetSpeechFromSpeaker()
    {
        $speakers = new Speaker();
        $speaker = $speakers->findOrFail((int)Input::get('objectId'));

        return response()->json($speaker->removeSpeech(Input::get('speechId')));
    }
}
