<?php
namespace App\SpeakersApp\Discourse\Model;

use App\SpeakersApp\Speaker\Model\Speaker;
use App\SpeakersApp\Speech\Model\Speech;
use Illuminate\Support\Facades\Auth;

class DiscourseAssignmentGenerator
{
    private $_discourse;
    private $_commentaryGenerator;

    public function __construct( Discourse $discourse )
    {
        $this->_discourse = $discourse;
    }

    private function getDiscourse()
    {
        return $this->_discourse;
    }

    private function getCommentaryGenerator()
    {
        if ( !$this->_commentaryGenerator ) {
            $this->_commentaryGenerator = new DiscourseCommentaryGenerator( $this->getDiscourse() );
        }
        return $this->_commentaryGenerator;
    }

    public function create( Speaker $speaker, Speech $speech, $comment='' )
    {
        $id = $this->_create($speaker, $speech);
        if ( $id && $comment ) {
            $this->_createComment($comment);
        }
        return $id;
    }

    private function _cancelOldAssignment()
    {
        if ( $this->getDiscourse()->getAssignment() ) {
            $changer = new DiscourseAssignmentStatusChanger($this->getDiscourse()->getAssignment());
            return $changer->toCanceled();
        }
        return true;
    }

    private function _create( Speaker $speaker, Speech $speech )
    {
        $this->_cancelOldAssignment();

        $assignment = new DiscourseAssignment();

        $assignment->authorId    = Auth::id();
        $assignment->statusId    = DiscourseAssignment::STATUS_PRESET;

        $assignment->discourseId = $this->getDiscourse()->id;
        $assignment->speakerId   = $speaker->id;
        $assignment->speechId    = $speech->id;

        $assignment->save();
        $this->getCommentaryGenerator()->newAssignment($assignment);

        $this->_attachSpeech($speaker, $speech, $assignment);
        return $assignment->id;
    }

    private function _attachSpeech(Speaker $speaker, Speech $speech, $assignment)
    {
        if ( !$speaker->speeches->contains($speech->id) ) {
            $this->getCommentaryGenerator()->attachSpeech($assignment);
        }
        return $speaker->addSpeech($speech->id);
    }

    private function _createComment($text)
    {
        $comment = new DiscourseCommentary();

        $comment->authorId    = Auth::id();
        $comment->statusId    = 1;

        $comment->discourseId = $this->getDiscourse()->id;
        $comment->text = $text;

        $comment->save();

        return $comment->id;
    }
}