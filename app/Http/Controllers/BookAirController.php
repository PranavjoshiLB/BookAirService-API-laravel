<?php

namespace App\Http\Controllers;

use Amadeus\Client;
use App\Exceptions\AirSellRecommendationException;
use App\Exceptions\FareDiscrepancyException;
use App\Exceptions\PnrCreateException;
use App\Exceptions\PricePnrException;
use App\Filters\SearchFilter;
use App\Model\Flight\Journey;
use App\Processor\PnrAddElementProcessor;
use App\Service\Flight\Contract\BookAirService;
use App\Service\Flight\RequestOption\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nathanmac\Utilities\Parser\Parser;
use App\Service\Flight\RequestOption\Traveller as Passenger;
use App\Service\Flight\RequestOption\Address;
use Amadeus\Client\RequestOptions\Pnr\Element\Contact;

class BookAirController extends ApiController
{
    use SearchFilter;

    protected $service;
    protected $parser;

    /**
     * FlightController constructor.
     * @param BookAirService $service
     */
    public function __construct(BookAirService $service)
    {
        $this->service = $service;
        $this->parser = new Parser();
    }

    /**
     * This confirms if a particular air segment can be booked.
     * It recommended this method is called by the client before users are allowed to supply PNR information
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function Bookable(Request $request)
    {
        $this->validate($request, [
            'itinerary.*.origin' => 'required',
            'itinerary.*.destination' => 'required',
            'itinerary.*segment.*.departureLocation' => 'required|min:3',
            'itinerary.*segment.*.arrivalLocation' => 'required|min:3',
            'itinerary.*.segment.*.dateOfDeparture' => 'required|date|date_format:Y-m-d|after:yesterday',
            'itinerary.*.segment.*.dateOfArrival' => 'required|date|date_format:Y-m-d|after:yesterday',
            'itinerary.*.segment.*.timeOfDeparture' => 'required|date_format:H:i',
            'itinerary.*.segment.*.timeOfArrival' => 'required|date_format:H:i',
            'itinerary.*.segment.*.marketingCompany' => 'required',
            'itinerary.*.segment.*.flightNumber' => 'required',
            'itinerary.*.segment.*.bookingClass' => 'required',
            'itinerary.*.segment.*.totalPassenger' => 'numeric|required',

            //'itinerary.segment.*.availabilityCtx' =>'',
            'itinerary' => 'array|required',
            'passenger' => 'array|required',
            'passenger.*.lastName' => 'required',
            'passenger.*.firstName' => 'required',
            'passenger.*.withInfant' => 'boolean',
            'passenger.*.dateOfBirth' => 'date',
            'passenger.*.travellerType' => 'in:CHD, INF,ADT, INS',
            'passenger.*.infant' => 'array',
            'passenger.*.infant.firstName' => 'required_if:passenger.*.withInfant,1',
            'passenger.*.infant.lastName' => 'required_if:passenger.*.withInfant,1',
            'passenger.*.infant.dateOfBirth' => 'date',


        ]);
        $passengers = $request->passenger;
        $travellers = [];
        $itinerary = [];
        $num = 1;
        foreach ($passengers as $passenger) {
            $passenger['number'] = $num;
            $travellers[] = new Passenger($passenger);
            $num++;
        }
        // Set Itinerary
        foreach ($request['itinerary'] as $journey) {
            $itinerary[] = new Itinerary($journey);
        }
        $this->service->setItinerary($itinerary)
            ->setTravellers($travellers);

        $response = $this->service->airSellFromRecommendation();
        if ($response->status === Client\Result::STATUS_OK) {
            return ok(['status' => true]);
        }
        return fail('This itinerary can not be booked at the moment');
    }

    /**
     * @param Request $request
     */
    public function bookFlight(Request $request)
    {
        $this->validate($request, [
            'itinerary.*.origin' => 'required',
            'itinerary.*.destination' => 'required',
            'itinerary.*segment.*.departureLocation' => 'required|min:3',
            'itinerary.*segment.*.arrivalLocation' => 'required|min:3',
            'itinerary.*.segment.*.dateOfDeparture' => 'required|date|date_format:Y-m-d|after:yesterday',
            'itinerary.*.segment.*.dateOfArrival' => 'required|date|date_format:Y-m-d|after:yesterday',
            'itinerary.*.segment.*.timeOfDeparture' => 'required|date_format:H:i',
            'itinerary.*.segment.*.timeOfArrival' => 'required|date_format:H:i',
            'itinerary.*.segment.*.marketingCompany' => 'required',
            'itinerary.*.segment.*.flightNumber' => 'required',
            'itinerary.*.segment.*.bookingClass' => 'required',
            'itinerary.*.segment.*.totalPassenger' => 'numeric|required',
            'itinerary' => 'array|required',
            'passenger' => 'array|required',
            'payment' => 'array',
            'contact' => 'array',
            'passenger.*.lastName' => 'required',
            'passenger.*.firstName' => 'required',
            'passenger.*.withInfant' => 'boolean',
            'passenger.*.dateOfBirth' => 'date',
            'passenger.*.travellerType' => 'in:CHD, INF,ADT, INS',
            'passenger.*.infant.firstName' => 'required_if:passenger.*.withInfant,1',
            'passenger.*.infant.lastName' => 'required_if:passenger.*.withInfant,1',
            'passenger.*.infant.dateOfBirth' => 'date',


            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'address.addressLine' => 'required',
            'address.name' => 'required',
            'address.city' => 'required',
            'address.country' => 'required',
            'address.zipCode' => 'required',
            'fareAmount' => 'required|numeric',
            //'from' => 'required',
            //'to' => 'required'
        ]);
        $fareAmount = $request->fareAmount;
        $passengers = $request->passenger;

        $travellers = [];
        $itinerary = [];
        // Set passengers
        $num = 1;
        foreach ($passengers as $passenger) {
            $passenger['number'] = $num;
            $travellers[] = new Passenger($passenger);
            $num++;
        }
        // Set Itinerary
        foreach ($request['itinerary'] as $journey) {
            $itinerary[] = new Itinerary($journey);
        }
        $this->service->setItinerary($itinerary)
            ->setTravellers($travellers)
            ->setFareAmount($fareAmount)
            ->addElement(new Contact([
                'type' => Contact::TYPE_EMAIL,
                'value' => $request->contact['email']
            ]))
            ->addElement(
                new Contact([
                    'type' => Contact::TYPE_PHONE_MOBILE,
                    'value' => $request->contact['phone']
                ])
            )
            ->addElement(new Address($request->address));
        try {
            $pnr = $this->service->makeBooking();
            $journey = new Journey(
                [
                    'company_id' => $pnr->companyId(),
                    'control_number' => $pnr->controlNumber(),
                    'itinerary' => $request->itinerary,
                    'user_id' => Auth::id()
                ]
            );
            $journey->save();
            return ok($journey);
        } catch (AirSellRecommendationException $exception) {
            throw $exception;
        } catch (PnrCreateException $exception) {
            throw $exception;
        } catch (PricePnrException $exception) {
            throw $exception;
        } catch (FareDiscrepancyException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
