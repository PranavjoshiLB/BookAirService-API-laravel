<?php

namespace App\Service\Flight\Queue;


use Amadeus\Client;

class AirQueueService
{

    /**
     * @var Client
     */
    public $client;

    public function __construct(Client $client)
    {
        $this->client =$client;
    }

    /**
     * @todo get all items in queue
     */
    public function getAll()
    {
        //TODO
    }

    /**
     *  Place a PNR on queue
     * @param $controlNumber
     * @return Client\Result
     */
    public function add($controlNumber)
    {
        return $this->client->queuePlacePnr(
            new Client\RequestOptions\QueuePlacePnrOptions([
                'targetQueue' => new Client\RequestOptions\Queue([
                    'queue' => 50,
                    'category' => 0
                ]),
                'recordLocator' => $controlNumber
            ])
        );
    }

    //@todo Remove an item from queue
    public function remove()
    {
    }
}