<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:23 PM
 */

namespace App\Service\Car\Struct;


class BeginDateTime
{

    public $year;
    public $month;
    public $day;
    public $hour;
    public $minutes;

    /**
     * BeginDateTime constructor.
     * @param \DateTime $time
     */
    public function __construct(\DateTime $time)
    {
        $this->year = $time->format('Y');
        $this->month = $time->format('m');
        $this->day = $time->format('d');
        $this->hour = $time->format('H');
        $this->minutes = $time->format('i');
    }
}