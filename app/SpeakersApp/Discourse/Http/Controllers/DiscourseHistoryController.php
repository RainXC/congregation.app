<?php

namespace App\SpeakersApp\Discourse\Http\Controllers;

use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Discourse\Model\DiscourseCommentary;
use App\SpeakersApp\Discourse\View\DiscourseCalendar;
use Carbon\Carbon;
use Composer\Command\DumpAutoloadCommand;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class DiscourseHistoryController extends Controller
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
    public function index(int $id)
    {
        $discourse = Discourse::findOrFail($id);

        return response()->json($discourse->commentaries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $discourseId, Request $request)
    {
        $commentary = new DiscourseCommentary();

        $commentary->text        = $request->text;
        $commentary->discourseId = $discourseId;
        $commentary->statusId    = DiscourseCommentary::STATUS_ACTIVE;
        $commentary->authorId    = Auth::id();

        $commentary->save();

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
        $commentary = DiscourseCommentary::findOrFail($id);

        return response()->json($commentary);
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
        $commentary  = DiscourseCommentary::findOrFail($id);

        $commentary->text        = $request->text;
        $commentary->discourseId = $id;
        $commentary->statusId    = DiscourseCommentary::STATUS_ACTIVE;
        $commentary->authorId    = Auth::id();

        $commentary->save();



        return response()->json(true);
    }

    /**
     * @param int $id
     * @param int $discourseId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id, int $discourseId)
    {
        $commentary  = DiscourseCommentary::findOrFail($discourseId);
        $commentary->delete();

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
