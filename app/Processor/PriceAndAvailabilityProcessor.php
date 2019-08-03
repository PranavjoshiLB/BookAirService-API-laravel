<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/28/2018
 * Time: 2:39 PM
 */

namespace App\Processor;


use App\Model\Flight\CabinClass;
use Robbo\Presenter\Presenter;

class PriceAndAvailabilityProcessor extends Presenter implements ProcessorInterface
{

    /**
     * @return array
     */
    public function getOutput()
    {
        $data = [];
        $pricingGrps = $this->mainGroup['pricingGroupLevelGroup'];
        if (isset($pricingGrps[0])) {
            foreach ($pricingGrps as $group) {
                $data[] = $this->output($group);
            }
            return $data;
        }
        return array($this->output($pricingGrps));
    }

    /**
     * @return mixed
     */
    protected function output($group)
    {

        $fareInfoGrp = $group['fareInfoGroup'];
        $segLevelGrp = $fareInfoGrp['segmentLevelGroup'];

        $data['currency'] = $fareInfoGrp['fareAmount']['monetaryDetails']['currency'];
        $data['fareAmount'] = (float)$fareInfoGrp['fareAmount']['monetaryDetails']['amount'];
        $taxes = $fareInfoGrp['surchargesGroup']['taxesAmount']['taxDetails'];
        $data['taxAmount'] = collect($taxes)->sum->rate;

        if (isset($fareInfoGrp['fareAmount']['otherMonetaryDetails'][0])) {
            $monetaryDetails = collect($fareInfoGrp['fareAmount']['otherMonetaryDetails']);
            $details = $monetaryDetails->where('typeQualifier', 712)->values()->toArray();
        } else {
            $details = $fareInfoGrp['fareAmount']['otherMonetaryDetails'];
        }

        $data['totalAmount'] = $details;


        if (isset($segLevelGrp[0])) {
            foreach ($segLevelGrp as $levelGrp) {
                $data['flights'][] = $this->getFlightInfo($levelGrp);
            }
            return $data;
        }
        $data['flights'][] = $this->getFlightInfo($segLevelGrp);
        return $data;
    }

    /**in
     * @param array $segInfo
     * @return mixed
     */
    public function getFlightInfo(array $segLevelGrp)
    {
        $segInfo = $segLevelGrp['segmentInformation'];

        $info['departureDate'] = formatDate($segInfo['flightDate']['departureDate']);
        $info['boardPointDetails'] = $segInfo['boardPointDetails']['trueLocationId'];
        $info['offPointDetails'] = $segInfo['offpointDetails']['trueLocationId'];
        $info['companyDetails'] = $segInfo['companyDetails']['marketingCompany'];
        $info['flightNumber'] = $segInfo['flightIdentification']['flightNumber'];
        $info['priceTicketDetails'] = $segLevelGrp['additionalInformation']['priceTicketDetails']['indicators'];
        $info['rateClass'] = $segLevelGrp['fareBasis']['additionalFareDetails']['rateClass'];
        $info['bookingClass'] = $this->getBookingClass($segInfo['flightIdentification']['bookingClass']);

        return $info;
    }

    public function getBookingClass($code){
        $class= CabinClass::find($code);
        if($class==null){
            return [
                'id'=>$code,
                'name' => $code
            ];
        }
        return $class;
    }
}