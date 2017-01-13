<?php
namespace App\SpeakersApp\Discourse\Model;

use Illuminate\Support\Facades\Auth;

class DiscourseCommentaryGenerator
{
    private $discourse;

    public function __construct( Discourse $discourse )
    {
        $this->discourse = $discourse;
    }

    private function getDiscourse()
    {
        return $this->discourse;
    }

    public function create($text)
    {
        $commentary = new DiscourseCommentary();

        $commentary->text        = $text;
        $commentary->discourseId = $this->getDiscourse()->id;
        $commentary->statusId    = 1;
        $commentary->authorId    = Auth::id();

        $commentary->save();

        return $commentary->id;
    }

    public function toPreset( DiscourseAssignment $assignment )
    {
        $this->create(json_encode([
            'speaker' => $assignment->speaker->id,
            'speech'  => $assignment->speech->id,
            'langKey' => 'toPreset',
            'type'    => 'robot',
        ]));
    }

    public function toConfirmed( DiscourseAssignment $assignment )
    {
        $this->create(json_encode([
            'speaker' => $assignment->speaker->id,
            'speech'  => $assignment->speech->id,
            'langKey' => 'toConfirmed',
            'type'    => 'robot',
        ]));
    }

    public function toCompleted( DiscourseAssignment $assignment )
    {
        $this->create(json_encode([
            'speaker' => $assignment->speaker->id,
            'speech'  => $assignment->speech->id,
            'langKey' => 'toCompleted',
            'type'    => 'robot',
        ]));
    }

    public function toMoved( DiscourseAssignment $assignment )
    {
        $this->create(json_encode([
            'speaker' => $assignment->speaker->id,
            'speech'  => $assignment->speech->id,
            'langKey' => 'toMoved',
            'type'    => 'robot',
        ]));
    }

    public function toCanceled( DiscourseAssignment $assignment )
    {
        $this->create(json_encode([
            'speaker' => $assignment->speaker->id,
            'speech'  => $assignment->speech->id,
            'langKey' => 'toCanceled',
            'type'    => 'robot',
        ]));
    }

    public function newAssignment( DiscourseAssignment $assignment )
    {
        $this->create(json_encode([
            'speaker' => $assignment->speaker->id,
            'speech'  => $assignment->speech->id,
            'langKey' => 'toPreset',
            'type'    => 'robot',
        ]));
    }

    public function attachSpeech( DiscourseAssignment $assignment )
    {
        $this->create(json_encode([
            'speaker' => $assignment->speaker->id,
            'speech'  => $assignment->speech->id,
            'langKey' => 'attachSpeech',
            'type'    => 'robot',
        ]));
    }
}