<?php
/**
 * Created by PhpStorm.
 * User: dmitricercel
 * Date: 17.01.17
 * Time: 18:52
 */

namespace App\SpeakersApp\Speech\View;


class SpeechesView implements \JsonSerializable
{
    /**
     * @var mixed
     */
    private $speeches;
    /**
     * @var array
     */
    private $_speeches;

    /**
     * SpeechesView constructor.
     *
     * @param $speeches
     */
    public function __construct($speeches)
    {
        $this->setSpeeches($speeches)->processSpeeches();
    }

    private function setSpeeches($speeches)
    {
        $this->speeches = $speeches;
        return $this;
    }

    private function processSpeeches()
    {
        foreach ( $this->speeches->get() as $speech ) {
            $_speech = [ 'speech' => $speech ];

            if ( $speech->getNearestDiscourse() ) {
                $_speech = array_merge($_speech, [ 'nearestDiscourse' => $speech->getNearestDiscourse() ]);
            }
            if ( $speech->getNextDiscourse() ) {
                $_speech = array_merge($_speech, [ 'nextDiscourse' => $speech->getNextDiscourse() ]);
            }
            if ( $speech->getPrevDiscourse() ) {
                $_speech = array_merge($_speech, [ 'prevDiscourse' => $speech->getPrevDiscourse() ]);
            }

            $this->_speeches[] = $_speech;
        }
    }

    public function jsonSerialize()
    {
        return $this->_speeches;
    }
}