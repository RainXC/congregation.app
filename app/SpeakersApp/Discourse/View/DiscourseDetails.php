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
        return [
            'discourse' => $this->getDiscourse(),
            'next'      => $this->getDiscourse()->nextDate(),
            'prev'      => $this->getDiscourse()->prevDate()
        ];
    }

}