<?php
/**
 * Created by PhpStorm.
 * User: dmitricercel
 * Date: 28.12.16
 * Time: 19:36
 */

namespace App\SpeakersApp\Discourse\View;


use App\SpeakersApp\Discourse\Model\Discourse;

class DiscourseDetails implements \JsonSerializable
{
    /**
     * @var Discourse
     */
    private $discourse;

    public function __construct(Discourse $discourse)
    {
        $this->discourse = $discourse;
    }

    public function getDiscourse()
    {
        return $this->discourse;
    }

    public function jsonSerialize()
    {
        $details = [
            'discourse' => $this->getDiscourse(),
            'prevTwo'   => [],
            'nextTwo'   => []
        ];

        if ( $this->getDiscourse()->prevDate()->prevDate() ) {
            array_push($details['prevTwo'], $this->getDiscourse()->prevDate()->prevDate());
        }
        if ( $this->getDiscourse()->prevDate() ) {
            array_push($details['prevTwo'], $this->getDiscourse()->prevDate());
            array_merge($details, ['prev'=> $this->getDiscourse()->prevDate()]);

        }

        if ( $this->getDiscourse()->nextDate() ) {
            array_push($details['nextTwo'], $this->getDiscourse()->nextDate());
            array_merge($details, ['next' => $this->getDiscourse()->nextDate()]);
            if ( $this->getDiscourse()->nextDate()->nextDate() ) {
                array_push($details['nextTwo'], $this->getDiscourse()->nextDate()->nextDate());
            }
        }

        return $details;
    }

}