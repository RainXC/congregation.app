<?php

namespace App\SpeakersApp\Discourse\Http\Controllers;


use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Discourse\Model\DiscourseAssignment;
use App\SpeakersApp\Discourse\Model\DiscourseAssignmentGenerator;
use App\SpeakersApp\Discourse\Model\DiscourseAssignmentStatusChanger;

use App\Http\Controllers\Controller;
use App\SpeakersApp\Speaker\Model\Speaker;
use App\SpeakersApp\Speech\Model\Speech;
use Symfony\Component\HttpFoundation\Request;

class DiscourseAssignmentsController extends Controller
{
    public function create(Request $request)
    {
        $discourse  = Discourse::find($request->input('discourseId'));
        $speaker    = Speaker::find($request->input('speakerId'));
        $speech     = Speech::find($request->input('speechId'));
        $generator  = new DiscourseAssignmentGenerator( $discourse );

        $generator->create( $speaker, $speech, $request->input('comment') );

        return response()->json(true);
    }

    public function confirm($assignmentId)
    {
        $assignment    = DiscourseAssignment::find($assignmentId);
        $statusChanger = new DiscourseAssignmentStatusChanger($assignment);
        $statusChanger->toConfirmed();

        return response()->json(true);
    }

    public function cancel($assignmentId)
    {
        $assignment    = DiscourseAssignment::find($assignmentId);
        $statusChanger = new DiscourseAssignmentStatusChanger($assignment);
        $statusChanger->toCanceled();

        return response()->json(true);
    }

    public function move($assignmentId)
    {
        $assignment    = DiscourseAssignment::find($assignmentId);
        $statusChanger = new DiscourseAssignmentStatusChanger($assignment);
        $statusChanger->toMoved();

        return response()->json(true);
    }

    public function preset($assignmentId)
    {
        $assignment    = DiscourseAssignment::find($assignmentId);
        $statusChanger = new DiscourseAssignmentStatusChanger($assignment);
        $statusChanger->toPreset();

        return response()->json(true);
    }

    public function complete($assignmentId)
    {
        $assignment    = DiscourseAssignment::find($assignmentId);
        $statusChanger = new DiscourseAssignmentStatusChanger($assignment);
        $statusChanger->toCompleted();

        return response()->json(true);
    }
}
