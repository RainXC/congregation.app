<?php

namespace App\SpeakersApp\Discourse\Model;

use App\SpeakersApp\Discourse\Model\Status;
use App\SpeakersApp\Speaker\Model\Speaker;
use App\SpeakersApp\Speech\Model\Speech;
use Illuminate\Database\Eloquent\Model;

class DiscourseAssignment extends Model
{
    const STATUS_PRESET    = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_MOVED     = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_CANCELED  = 5;

    protected $table = 'discourses_assignments';

    /**
     * @var array
     */
    protected $appends = ['conditions'];

    public function speaker()
    {
        return $this->belongsTo(Speaker::class, 'speakerId')->with(['congregation']);
    }

    public function speech()
    {
        return $this->belongsTo(Speech::class, 'speechId');
    }

    public function commentaries()
    {
        return $this->hasMany(DiscourseAssignmentCommentary::class, 'discourseAssignmentId', 'id')->orderBy('created_at', 'desc');
    }

    public function discourse()
    {
        return $this->belongsTo(Discourse::class, 'discourseId');
    }

    public function setSpeech($id)
    {
        $this->speechId = $id;
        return $this->save();
    }

    public function setSpeaker($id)
    {
        $this->speakerId = $id;
        return $this->save();
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'statusId');
    }

    public function isPreset()
    {
        return $this->statusId == self::STATUS_PRESET;
    }

    public function isConfirmed()
    {
        return $this->statusId == self::STATUS_CONFIRMED;
    }

    public function isMoved()
    {
        return $this->statusId == self::STATUS_MOVED;
    }

    public function isCompleted()
    {
        return $this->statusId == self::STATUS_COMPLETED;
    }

    public function isCanceled()
    {
        return $this->statusId == self::STATUS_CANCELED;
    }

    public function isAvailableToCancel()
    {
        return ($this->isPreset() || $this->isConfirmed()) && !$this->isCompleted();
    }

    public function isAvailableToConfirm()
    {
        return $this->isPreset();
    }

    public function isAvailableToComplete()
    {
        return $this->isConfirmed() && $this->discourse->isPast();
    }

    public function setStatus($statusId)
    {
        $this->statusId = $statusId;
        $this->save();
        return $this->id;
    }

    public function getConditionsAttribute()
    {
        return [
            'isPreset'              => $this->isPreset(),
            'isConfirmed'           => $this->isConfirmed(),
            'isMoved'               => $this->isMoved(),
            'isCompleted'           => $this->isCompleted(),
            'isCanceled'            => $this->isCanceled()
        ];
    }
}