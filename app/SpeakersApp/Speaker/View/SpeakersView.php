<?php
/**
 * Created by PhpStorm.
 * User: dmitricercel
 * Date: 17.01.17
 * Time: 18:52
 */

namespace App\SpeakersApp\Speaker\View;


use Carbon\Carbon;

class SpeakersView implements \JsonSerializable
{
    /**
     * @var mixed
     */
    private $speakers;
    /**
     * @var array
     */
    private $_speakers;
    /**
     * @var Carbon
     */
    private $currentTime;

    /**
     * SpeakersView constructor.
     *
     * @param $speakers
     */
    public function __construct($speakers)
    {
        $this->setSpeakers($speakers);
    }

    private function setSpeakers($speakers)
    {
        $this->speakers = $speakers;
        return $this;
    }

    /**
     * @param Carbon $time
     *
     * @return $this
     */
    public function setCurrentTime( Carbon $time )
    {
        $this->currentTime = $time;

        return $this;
    }

    private function processSpeakers()
    {
        foreach ( $this->speakers->get() as $speaker ) {
            if ( $this->currentTime ) {
                $speaker->setCurrentTime($this->currentTime);
            }

            $_speaker = [ 'speaker' => $speaker ];

            if ( $speaker->getNearestDiscourse() ) {
                $_speaker = array_merge($_speaker, [ 'nearestDiscourse' => $speaker->getNearestDiscourse() ]);
            }
            if ( $speaker->getNextDiscourse() ) {
                $_speaker = array_merge($_speaker, [ 'nextDiscourse' => $speaker->getNextDiscourse() ]);
            }
            if ( $speaker->getPrevDiscourse() ) {
                $_speaker = array_merge($_speaker, [ 'prevDiscourse' => $speaker->getPrevDiscourse() ]);
            }

            $this->_speakers[] = $_speaker;
        }

        return $this;
    }

    public function getSpeakers()
    {
        return $this->_speakers;
    }

    public function jsonSerialize()
    {
        return $this->processSpeakers()->getSpeakers();
    }
}