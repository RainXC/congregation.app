<?php
namespace App\SpeakersApp\Speaker\Repository;


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

        return $this;
    }

    public function filterByFavorite()
    {
        $this->speakers->where('favorite', 1);
        return $this;
    }

    public function get()
    {
        return $this->speakers->get();
    }
}