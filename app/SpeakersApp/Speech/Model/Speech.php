<?php

namespace App\SpeakersApp\Speech\Model;

use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Discourse\Model\DiscourseAssignment;
use App\SpeakersApp\Speaker\Model\Speaker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Speech extends Model
{
    protected $table = 'speeches';

    /**
     * @var Carbon
     */
    private $currentTime;

    /**
     * Speaker constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setCurrentTime(Carbon::now());
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'speakers_speeches', 'speechId', 'speechId');
    }

    public function __toString()
    {
        return $this->getName().' ['.$this->getCode().']';
    }

    /**
     * @param Carbon $time
     *
     * @return $this
     */
    public function setCurrentTime(Carbon $time)
    {
        $this->currentTime = $time;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCurrentTime()
    {
        return $this->currentTime;
    }

    /**
     * @return Discourse|array
     */
    public function getNearestDiscourse()
    {
        $prevDisc = $this->getPrevDiscourse();
        $nextDisc = $this->getNextDiscourse();

        if ( $prevDisc && $nextDisc) {
            return ( $prevDisc->time->diffInHours($this->getCurrentTime()) < $nextDisc->time->diffInHours($this->getCurrentTime()) )
                ? $prevDisc
                : $nextDisc;
        }

        if ( $prevDisc ) {
            return $prevDisc;
        }

        if ( $nextDisc ) {
            return $nextDisc;
        }

        return null;
    }

    /**
     * @return Discourse|array
     */
    public function getNextDiscourse()
    {
        $speechId      = $this->id;
        // TODO: Remove congregationId from system
        $congregationId = Auth::user()->congregation_id;

        $discourse = Discourse::where('congregationId', $congregationId)
            ->where('time', '>', $this->getCurrentTime()->toDateTimeString())
            ->whereHas('assignments', function ($builder) use($speechId) {
                $builder->where('speechId', $speechId)
                    ->whereIn('statusId', [
                        DiscourseAssignment::STATUS_COMPLETED,
                        DiscourseAssignment::STATUS_CONFIRMED,
                        DiscourseAssignment::STATUS_PRESET
                    ]);
            })->first();

        return $discourse;
    }

    /**
     * @return Discourse|array
     */
    public function getPrevDiscourse()
    {
        $speechId      = $this->id;
        // TODO: Remove congregationId from system
        $congregationId = Auth::user()->congregation_id;

        $discourse = Discourse::where('congregationId', $congregationId)
            ->where('time', '<', $this->getCurrentTime()->toDateTimeString())
            ->whereHas('assignments', function ($builder) use($speechId) {
                $builder->where('speechId', $speechId)
                    ->whereIn('statusId', [
                        DiscourseAssignment::STATUS_COMPLETED,
                        DiscourseAssignment::STATUS_CONFIRMED,
                        DiscourseAssignment::STATUS_PRESET
                    ]);
            })->first();

        return $discourse;
    }
}
