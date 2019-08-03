<?php

namespace App\Service\Client;

use Amadeus\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AmadeusClient extends Client
{


    public function __construct()
    {
        parent::__construct($this->params());
    }

    /**
     * @return Client\Params
     */
    private function params()
    {
        return new Client\Params([
            //'returnXml' => false,

            'authParams' => [
                'officeId' => config('amadeus.office_id'),
                'userId' => config('amadeus.user_id'),
                'passwordData' => config('amadeus.password'),
                'dutyCode' => config('amadeus.dutyCode'),
                'originatorTypeCode' => config('amadeus.originatorTypeCode')
            ],
            'sessionHandlerParams' => [
                Client::HEADER_V4,
                'wsdl' => config('amadeus.wsdl'),
                'stateful' => false,
                'logger' => $this->logger()
            ],
            'requestCreatorParams' => [
                'receivedFrom' => 'Samuel local'
            ]
        ]);
    }

    /**
     *  Provides Monolog instance for logging amadeus xml request and response.
     * @return Logger
     */
    private function logger()
    {
        $msgLog = new Logger('RequestResponseLogs');
        $msgLog->pushHandler(new StreamHandler(storage_path('logs/amadeus.log'), Logger::INFO));
        return $msgLog;
    }

}