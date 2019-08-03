<?php

namespace App\Service\Flight\RequestOption;

use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Itinerary as Journey;

class Itinerary extends Journey
{

    /**
     * Itinerary constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {

        $this->setOrigin($params);
        $this->setDestination($params);
        $this->setSegment($params);
    }

    /**
     * @param $params
     */
    public function setSegment($params)
    {
        if (isset($params['segment'])) {
            foreach ($params['segment'] as $segment) {
                $this->segments[] = new Segment($segment);
            }
        }
    }

    /**
     * @param $params
     */
    public function setOrigin($params)
    {
        if (isset($params['origin'])) {
            $this->from = $params['origin'];
        }
    }

    /**
     * @param $params
     */
    public function setDestination($params)
    {
        if (isset($params['destination'])) {
            $this->to = $params['destination'];
        }
    }
}