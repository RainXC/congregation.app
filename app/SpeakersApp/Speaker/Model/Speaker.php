<?php

namespace App\SpeakersApp\Speaker\Model;

use App\SpeakersApp\Congregation\Model\Congregation;
use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Speech\Model\Speech;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    /**
     * @var string
     */
    protected $table = 'speakers';

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
}
