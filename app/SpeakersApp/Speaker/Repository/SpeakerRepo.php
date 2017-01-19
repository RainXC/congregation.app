<?php
namespace App\SpeakersApp\Speaker\Repository;


use App\SpeakersApp\Discourse\Model\Discourse;
use App\SpeakersApp\Discourse\Model\DiscourseAssignment;
use App\SpeakersApp\Speaker\Model\Speaker;

class SpeakerRepo
{
    private $speakers;

    public function __construct(  )
    {
        $this->speakers = Speaker::with(['status', 'speeches', 'discourses', 'congregation']);
    }

    public function filterByDebt()
    {
        $debtList = [];
        $assignments = DiscourseAssignment::where('statusId', DiscourseAssignment::STATUS_CANCELED);
        foreach( $assignments->get() as $assignment ) {
            $repaid = DiscourseAssignment::where('speakerId', $assignment->speakerId)
                                        ->where('speechId', $assignment->speechId)
                                        ->whereIn('statusId', [
                                            DiscourseAssignment::STATUS_COMPLETED,
                                            DiscourseAssignment::STATUS_CONFIRMED,
                                            DiscourseAssignment::STATUS_PRESET
                                        ])->first();
            if ( !$repaid ) {
                $debtList[] = $assignment->speakerId;
            }
        }

        $this->speakers->whereIn('id', $debtList);

        return $this;
    }

    public function filterByFavorite()
    {
        $this->speakers->where('favorite', 1);
        return $this;
    }

    public function get()
    {
        return $this->speakers;
    }
}