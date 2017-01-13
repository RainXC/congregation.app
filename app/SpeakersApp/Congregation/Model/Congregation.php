<?php

namespace App\SpeakersApp\Congregation\Model;

use App\Time\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class Congregation extends Model
{
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getMeetingTime()
    {
        return $this->meetingTime;
    }

    /**
     * @return mixed
     */
    public function getMeetingDay()
    {
        return Lang::get('days.'.$this->meetingDay);
    }

    /**
     * @return null
     */
    public function getNextMeetingDate()
    {
        return null;
    }
}
