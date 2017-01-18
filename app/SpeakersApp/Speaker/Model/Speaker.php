<?php

namespace App\SpeakersApp\Speaker\Model;

use App\SpeakersApp\Congregation\Model\Congregation;
use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Discourse\Model\DiscourseAssignment;
use App\SpeakersApp\Speech\Model\Speech;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Speaker extends Model
{
    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = false;

    /**
     * @var string
     */
    protected $table = 'speakers';

    /**
     * @var Carbon
     */
    private $currentTime;

    protected $dates = ['created_at', 'updated_at'];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function speeches()
    {
        return $this->belongsToMany(Speech::class, 'speakers_speeches', 'speakerId', 'speechId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function discourses()
    {
        return $this->belongsToMany(Discourse::class, 'discourses_assignments', 'speakerId', 'discourseId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function congregation()
    {
        return $this->hasOne(Congregation::class, 'id', 'congregationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne( Status::class, 'id', 'statusId');
    }

    /**
     * @param $speechId
     *
     * @return bool
     */
    public function addSpeech($speechId)
    {
        if ( !$this->speeches->contains($speechId) ) {
            $this->speeches()->attach($speechId);
            return true;
        }
        return false;
    }

    /**
     * @param $speechId
     *
     * @return bool
     */
    public function removeSpeech($speechId)
    {
        if ( $this->speeches->contains($speechId) ) {
            $this->speeches()->detach($speechId);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getLastname().' '.$this->getFirstname();
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return mixed
     */
    public function  getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
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
            return ( $prevDisc->time->diffInHours($this->getCurrentTime()) <= $nextDisc->time->diffInHours($this->getCurrentTime()) )
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
        $speakerId      = $this->id;
        // TODO: Remove congregationId from system
        $congregationId = Auth::user()->congregation_id;

        $discourse = Discourse::where('congregationId', $congregationId)
            ->whereDate('time', '>=', $this->getCurrentTime()->toDateString())
            ->whereHas('assignments', function ($builder) use($speakerId) {
                $builder->where('speakerId', $speakerId)
                    ->whereIn('statusId', [
                        DiscourseAssignment::STATUS_COMPLETED,
                        DiscourseAssignment::STATUS_CONFIRMED,
                        DiscourseAssignment::STATUS_PRESET
                    ]);
            })->orderBy('id', 'asc')->first();

        return $discourse;
    }

    /**
     * @return Discourse|array
     */
    public function getPrevDiscourse()
    {
        $speakerId      = $this->id;
        // TODO: Remove congregationId from system
        $congregationId = Auth::user()->congregation_id;

        $discourse = Discourse::where('congregationId', $congregationId)
            ->whereDate('time', '<=', $this->getCurrentTime()->toDateString())
            ->whereHas('assignments', function ($builder) use($speakerId) {
                $builder->where('speakerId', $speakerId)
                    ->whereIn('statusId', [
                        DiscourseAssignment::STATUS_COMPLETED,
                        DiscourseAssignment::STATUS_CONFIRMED,
                        DiscourseAssignment::STATUS_PRESET
                    ]);
            })->orderBy('id', 'desc')->first();

        return $discourse;
    }
}
