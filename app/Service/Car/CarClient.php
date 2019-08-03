<?php

namespace App\Service\Car;


use Amadeus\Client;
use Amadeus\Client\Params;

use Amadeus\Client\RequestCreator\RequestCreatorInterface;
use Amadeus\Client\ResponseHandler\ResponseHandlerInterface;
use App\Service\RequestCreator\CarRequestCreator;
use App\Service\RequestOption\Car\CarRequestOption;
use App\Service\ResponseHandler\CarResponseHandler;


class CarClient extends Client
{

    /**
     * Load a request creator
     *
     * A request creator is responsible for generating the correct request to send.
     *
     * @param RequestCreatorInterface|null $requestCreator
     * @param Params\RequestCreatorParams $params
     * @param string $libIdentifier Library identifier & version string (for Received From)
     * @param string $originatorOffice The Office we are signed in with.
     * @param array $mesVer Messages & Versions array of active messages in the WSDL
     * @return RequestCreatorInterface
     * @throws \RuntimeException
     */
    protected function loadRequestCreator($requestCreator, $params, $libIdentifier, $originatorOffice, $mesVer)
    {
        if ($requestCreator instanceof RequestCreatorInterface) {
            $newRequestCreator = $requestCreator;
        } else {
            $params->originatorOfficeId = $originatorOffice;
            $params->messagesAndVersions = $mesVer;

            $params->receivedFrom = $params->receivedFrom . " " . $libIdentifier;

            $newRequestCreator = new CarRequestCreator(
                $params,
                $libIdentifier
            );
        }

        return $newRequestCreator;
    }

    /**
     * Load a response handler
     *
     * @param ResponseHandlerInterface|null $responseHandler
     * @return ResponseHandlerInterface
     * @throws \RuntimeException
     */
    protected function loadResponseHandler($responseHandler)
    {
        if ($responseHandler instanceof ResponseHandlerInterface) {
            $newResponseHandler = $responseHandler;
        } else {
            $newResponseHandler = new CarResponseHandler();
        }
        return $newResponseHandler;
    }

    /**
     *  Get car location list
     * @param CarRequestOption $options
     * @param array $messageOptions
     * @return Client\Result
     */
    public function carLocationList(CarRequestOption $options, $messageOptions = [])
    {
        $msgName = "Car_LocationList";
        return $this->callMessage($msgName, $options, $messageOptions);

    }

    /** Query Car Availability
     * @param CarRequestOption $options
     * @param array $messageOptions
     * @return Client\Result
     */
    public function carAvailability(CarRequestOption $options, $messageOptions = [])
    {
        $msgName = 'Car_Availability';
        return $this->callMessage($msgName, $options, $messageOptions);
    }

    /**
     * Call a message with the given parameters
     *
     * @param string $messageName
     * @param Client\RequestOptions\RequestOptionsInterface $options
     * @param array $messageOptions
     * @param bool $endSession
     * @return Client\Result
     * @throws Client\Exception
     * @throws Client\Struct\InvalidArgumentException
     * @throws Client\InvalidMessageException
     * @throws Client\RequestCreator\MessageVersionUnsupportedException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function callMessage($messageName, $options, $messageOptions, $endSession = false)
    {
        $messageOptions = $this->makeMessageOptions($messageOptions, $endSession);

        $this->lastMessage = $messageName;

        $sendResult = $this->sessionHandler->sendMessage(
            $messageName,
            $this->requestCreator->createRequest(
                $messageName,
                $options
            ),
            $messageOptions
        );
        $response = $this->responseHandler->analyzeResponse(
            $sendResult,
            $messageName
        );

        if ($messageOptions['returnXml'] === false) {
            $response->responseXml = null;
        }

        return $response;
    }
}