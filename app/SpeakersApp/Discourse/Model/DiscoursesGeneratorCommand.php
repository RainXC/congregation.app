<?php
namespace App\SpeakersApp\Discourse\Model;

use App\SpeakersApp\Congregation\Model\Congregation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DiscoursesGeneratorCommand
{
    private $_year;

    public function __construct( Carbon $year )
    {
        $this->_year = $year;
    }

    public function execute()
    {
        return $this->setWeekendMeetings();
    }

    private function setWeekendMeetings()
    {
        $congregations = Congregation::all();

        foreach ( $congregations as $congregation ) {
            foreach ( $this->_year->weekends() as $weekend ) {
                if ( $weekend->getNumber() == $congregation->meetingDay ) {
                    if ( $congregation->getMeetingTime() ) {
                        $discourse = new Discourse();

                        $discourse->authorId       = Auth::id();
                        $discourse->statusId       = 1;
                        $discourse->congregationId = $congregation->id;
                        $discourse->time           = date('Y-m-d H:i:s', $weekend->getTimestampWithHour( new Time($congregation->getMeetingTime()) ));

                        $discourse->save();
                    }
                }

            }
        }
        return true;
    }
}