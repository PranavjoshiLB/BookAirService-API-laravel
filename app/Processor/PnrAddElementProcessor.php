<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 4/9/2018
 * Time: 1:23 PM
 */

namespace App\Processor;



use Robbo\Presenter\Presenter;

class PnrAddElementProcessor extends Presenter implements ProcessorInterface
{

    public  function companyId(){
        if(!$this->reservation()){
            return false;
        }
        $reservation = $this->reservation();
        return isset($reservation['companyId'])?$reservation['companyId']:false;
    }

    /**
     * @return bool
     */
    public function controlNumber()
    {
        if (!$this->reservation()) {
            return false;
        }
        $reservation = $this->reservation();
        return isset($reservation['controlNumber'])?$reservation['controlNumber']:false;
    }

    /**
     * @return bool
     */
    public function reservation()
    {

        if (isset($this->pnrHeader['reservationInfo']['reservation'])) {
            return $this->pnrHeader['reservationInfo']['reservation'];
        }
        return false;
    }

    public function getOutput()
    {
        // TODO: Implement getOutput() method.
    }
}