<?php

namespace App\SpeakersApp\Discourse\Model;

use App\SpeakersApp\Congregation\Model\Congregation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Discourse extends Model
{
    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = false;

    protected $dates = ['time', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $appends = ['assignment', 'timeDetails'];

    /**
     * @return HasMany
     */
    public function assignments() : HasMany
    {
        return $this->hasMany(DiscourseAssignment::class, 'discourseId', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function commentaries()
    {
        return $this->hasMany(DiscourseCommentary::class, 'discourseId', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function getAssignmentAttribute()
    {
        return $this->getAssignment();
    }

    public function getAssignment()
    {
        return DiscourseAssignment::whereIn('statusId', [ DiscourseAssignment::STATUS_CONFIRMED, DiscourseAssignment::STATUS_PRESET, DiscourseAssignment::STATUS_COMPLETED ])
            ->where('discourseId', $this->id)
            ->with(['speaker', 'speech', 'status'])
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getTimeDetailsAttribute()
    {
        return [
            'date'   => $this->time->format('Y-m-d'),
            'time'   => $this->time->format('H:i:s'),
            'day'    => $this->time->day,
            'month'  => $this->time->month,
            'year'   => $this->time->year,
            'hour'   => $this->time->hour,
            'minute' => $this->time->minute,
            'second' => $this->time->second,
            'dayCaption' => $this->time->format('jS'),
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function congregation() : HasOne
    {
        return $this->hasOne(Congregation::class, 'id', 'congregationId');
    }

    /**
     * @return Discourse
     */
    public function nextDate()
    {
        $discourses = new Discourse();
        return $discourses->where('id', '>', $this->id)
                          ->where('congregationId', '=', $this->congregationId)
                          ->orderBy('time', 'ASC')
                          ->take(1)
                          ->first();
    }

    /**
     * @return Discourse
     */
    public function prevDate()
    {
        $discourses = new Discourse();
        return $discourses->where('id', '<', $this->id)
                          ->where('congregationId', '=', $this->congregationId)
                          ->orderBy('time', 'DESC')
                          ->take(1)
                          ->first();
    }

    public function setStatus($statusId)
    {
        $this->statusId = $statusId;
        $this->save();
        return $this->id;
    }

    public function __toString()
    {
        $speech  = $this->assignment()->speech;
        $speaker = $this->assignment()->speaker;

        return $this->getDay().' | '.$speaker.' | '.$speech;
    }
}