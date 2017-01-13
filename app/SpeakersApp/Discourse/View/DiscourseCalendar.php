<?php
/**
 * Created by PhpStorm.
 * User: dmitricercel
 * Date: 17.12.16
 * Time: 17:02
 */

namespace App\SpeakersApp\Discourse\View;


use App\SpeakersApp\Discourse\Model\Discourse;

class DiscourseCalendar implements \JsonSerializable
{
    /**
     * @var Discourse
     */
    private $discourses;

    /**
     * @var array
     */
    private $_discourses = [];

    /**
     * @var array
     */
    private $_years = [];

    /**
     * @var array
     */
    private $years = [];

    /**
     * @var array
     */
    private $months = [];

    /**
     * DiscourseCalendar constructor.
     *
     * @param $discourses
     */
    public function __construct( $discourses )
    {
        $this->discourses = $discourses;
        $this->parseMonths()->parseYears()->formatDiscourses();
    }

    /**
     * @return Discourse
     */
    private function getDiscourses()
    {
        return $this->discourses;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        foreach( $this->years as $year ) {
            array_push($this->_years, $year);
        }

        return $this->_years;
    }

    /**
     * @return $this
     */
    private function parseYears()
    {
        foreach ( $this->getDiscourses() as $discourse ) {
            $year = $discourse->time->year;
            if ( !isset($this->years[$year]) ) {
                $this->years[$year] = [
                    'id'     => $year,
                    'name'   => $year,
                    'months' => $this->months[$year]
                ];
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function parseMonths()
    {
        foreach ( $this->getDiscourses() as $discourse ) {
            $year  = $discourse->time->year;
            $month = $discourse->time->month;
            if ( !isset($this->months[$year][$month]) ) {
                $this->months[$year][$month] = [
                    'id'   => $month,
                    'name' => $discourse->time->format('F'),
                    'year' => $year
                ];
            }
        }

        return $this;
    }

    private function formatDiscourses()
    {
        foreach ( $this->years as $yearKey => $year ) {
            foreach ( $year['months'] as $monthKey => $month ) {
                foreach ( $this->getDiscourses() as $discourse ) {
                    if ( $this->isDiscourseForMonthYear($discourse, $monthKey, $yearKey) ) {
                        $this->_discourses[] = $discourse;
                    }
                }
                $this->years[$yearKey]['months'][$monthKey]['discourses'] = $this->_discourses;
                $this->_discourses = [];
            }
        }

        foreach ( $this->years as $yearKey => $year ) {
            $months = [];
            foreach ( $year['months'] as $monthKey => $month ) {
                array_push($months, $month);
            }
            $this->years[$yearKey]['months'] = $months;
        }
    }

    private function isDiscourseForMonthYear( Discourse $discourse, int $month, int $year )
    {
        return ( $discourse->time->month == $month && $discourse->time->year == $year );
    }
}