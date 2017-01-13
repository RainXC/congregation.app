<?php
/**
 * Created by PhpStorm.
 * User: rainx_000
 * Date: 04.01.2016
 * Time: 21:40
 */

namespace App\SpeakersApp\Discourse\Model;

class DiscourseAssignmentStatusChanger
{
    private $assignment;
    private $_commentaryGenerator;

    public function __construct( DiscourseAssignment $assignment )
    {
        $this->assignment = $assignment;
    }

    private function getCommentaryGenerator()
    {
        if ( !$this->_commentaryGenerator ) {
            $this->_commentaryGenerator = new DiscourseCommentaryGenerator( $this->getDiscourse()->discourse );
        }
        return $this->_commentaryGenerator;
    }

    private function getDiscourse()
    {
        return $this->assignment;
    }

    public function toPreset()
    {
        if ( $this->getDiscourse()->setStatus(DiscourseAssignment::STATUS_PRESET) ) {
            return $this->getCommentaryGenerator()->toPreset($this->getDiscourse());
        }
        return false;
    }

    public function toConfirmed()
    {
        if ($this->getDiscourse()->setStatus(DiscourseAssignment::STATUS_CONFIRMED)) {
            return $this->getCommentaryGenerator()->toConfirmed($this->getDiscourse());
        }
        return false;
    }

    public function toCompleted()
    {
        if ($this->getDiscourse()->setStatus(DiscourseAssignment::STATUS_COMPLETED)) {
            return $this->getCommentaryGenerator()->toCompleted($this->getDiscourse());
        }
        return false;
    }

    public function toMoved()
    {
        if ($this->getDiscourse()->setStatus(DiscourseAssignment::STATUS_MOVED)) {
            return $this->getCommentaryGenerator()->toMoved($this->getDiscourse());
        }
        return false;
    }

    public function toCanceled()
    {
        if ($this->getDiscourse()->setStatus(DiscourseAssignment::STATUS_CANCELED)) {
            return $this->getCommentaryGenerator()->toCanceled($this->getDiscourse());
        }
        return false;
    }
}