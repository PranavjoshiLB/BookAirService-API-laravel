<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/9/2018
 * Time: 5:24 PM
 */

namespace App\Service\Car\Struct;


class EndDateTime
{

    public $year;
    /**
     * @var string
     */
    public $month=3;
    /**
     * @var string
     */
    public $day=12;
    /**
     * @var string
     */
    public $hour=1;
    /**
     * @var string
     */
    public $minutes=0;
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