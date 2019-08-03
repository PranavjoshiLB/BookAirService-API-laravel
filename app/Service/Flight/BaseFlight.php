<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/5/2018
 * Time: 11:15 PM
 */

namespace app\Service\Flight;


use Amadeus\Client\RequestOptions\Fare\MPPassenger;

abstract class BaseFlight
{

    /**
     * @var string
     */
    protected $departure;
    /**
     * @var string
     */
    protected $arrival;
    /**
     * @var string
     */
    protected $airline;
    /**
     * @var string
     */
    protected $flightNumber;
    /**
     * @var \DateTime
     */
    protected $departureDate;

    /**
     * @var \DateTime
     */
    protected $returnDate;
    /**
     * @var string
     */
    protected $bookingClass;

    /**
     * @var MPPassenger []
     */
    protected $passengers;


    public function setDepartureLocation(string $departure)
    {

        $this->departure = $departure;
    }

    /**
     * @return string
     */
    public function getDepartureLocation()
    {
        return $this->departure;
    }

    /**
     * @param string $arrival
     */
    public function setArrivalLocation(string $arrival)
    {
        $this->arrival = $arrival;
    }

    /**
     * @return string
     */
    public function getArrivalLocation()
    {
        return $this->arrival;
    }

    /**
     * @param $airline
     */
    public function setAirline($airline)
    {
        $this->airline = $airline;
    }

    /**
     * @return string
     */
    public function getAirline()
    {
        return $this->airline;
    }

    /**
     * @param \DateTime $date
     */
    public function setDepartureDate(\DateTime $date)
    {
        $this->departureDate = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setReturnedDate(\DateTime $dateTime)
    {
        $this->returnDate = $dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getReturnedDate()
    {
        return $this->returnDate;
    }

    /**
     * @param string $flightNo
     */
    public function setFlightNumber(string $flightNo)
    {
        $this->flightNumber = $flightNo;
    }

    /**
     * @return mixed
     */
    public function getFlightNumber()
    {
        return $this->flightNumber;
    }

    /**
     * @param $class
     */
    public function setBookingClass($class)
    {
        $this->bookingClass = $class;
    }

    /**
     * @return mixed
     */
    public function getBookingClass()
    {
        return $this->bookingClass;
    }

    /**
     * Set Passenger type;
     * @param MPPassenger $passenger
     */
    public function setPassenger(MPPassenger $passenger)
    {
        $this->passengers[] = $passenger;
    }

}