<?php

namespace PayCheckout\Json\Generic\Configuration;

use PayCheckout\Json\JsonBase;
use PayCheckout\Json\Generic\Configuration\Klarna\Klarna;
use PayCheckout\Json\Generic\Configuration\IDeal\IDeal;
use PayCheckout\Json\Generic\Configuration\PayPal\PayPal;
use PayCheckout\Json\Generic\Configuration\SofortBanking\SofortBanking;
use PayCheckout\Json\Generic\Configuration\Surcharge\SurchargeV2;
use PayCheckout\Json\Generic\Configuration\MinMaxSelector\MinMaxSelector;

class Configuration extends JsonBase
{
    /**
     * @var int[]
     */
    protected $selectedPaymentMethods;

    /**
     * @var string[]
     */
    protected $selectedPaymentMethodsTxt;
    
    /**
     * @var array|SurchargeV2[]
     */
    protected $surchargeV2;
    
    /**
     * @var array|MinMaxSelector[]
     */
    protected $minMaxSelector;
    
    /**
     * @var Klarna
     */
    protected $klarna;

    /**
     * @var IDeal
     */
    protected $iDeal;
    
    /**
     * @var PayPal
     */
    protected $payPal;
    
    /**
     * @var SofortBanking
     */
    protected $sofortBanking;

    /**
     * Create new configuration
     */
    public function __construct()
    {
        $this->selectedPaymentMethods	    = array();
        $this->selectedPaymentMethodsTxt    = array();
        $this->surchargeV2				    = array();
        $this->minMaxSelector			    = array();
    }
    
    /**
     * @return int[]
     */
    public function getSelectedPaymentMethods()
    {
        return $this->selectedPaymentMethods;
    }
    
    /**
     * @return string[]
     */
    public function getSelectedPaymentMethodsTxt()
    {
        return $this->selectedPaymentMethodsTxt;
    }
    
    /**
     * @return array|SurchargeV2[]
     */
    public function getSurchargeV2()
    {
        return $this->surchargeV2;
    }
    
    /**
     * @return MinMaxSelector[]
     */
    public function getMinMaxSelector()
    {
        return $this->minMaxSelector;
    }
    
    /**
     * @return Klarna
     */
    public function getKlarna()
    {
        return $this->klarna;
    }

    /**
     * @return IDeal
     */
    public function getIDeal()
    {
        return $this->iDeal;
    }

    /**
     * @return PayPal
     */
    public function getPayPal()
    {
        return $this->payPal;
    }

    /**
     * @return SofortBanking
     */
    public function getSofortBanking()
    {
        return $this->sofortBanking;
    }
        
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        switch($name)
        {
            case 'surchargeV2':
                // Items needs to an array (list of surcharges)
                if (is_object($value)) 
                {
                    foreach ((array) $value as $paymentMethod => $countries)
                    {
                        $paymentMethodIndex = \PayCheckout\PaymentMethod::convertFromJson($paymentMethod);
                        foreach ($countries as $country => $itemValues) 
                        {
                            // Check if item is an object
                            if (is_object($itemValues))
                            {
                                // Create new surcharge and add to configuration
                                $surchargeV2 = new SurchargeV2();
                                $surchargeV2->jsonDeserialize($itemValues);							

                                $this->surchargeV2[$paymentMethodIndex][$country] = $surchargeV2;
                            }
                        }
                    }
                }
                return;
            case 'minMaxSelector':
                // Items needs to an array (list of min max selectors)
                if (is_object($value)) 
                {
                    foreach ((array) $value as $paymentMethod => $currencies)
                    {
                        $paymentMethodIndex = \PayCheckout\PaymentMethod::convertFromJson($paymentMethod);
                        foreach ($currencies as $currency => $itemValues) 
                        {
                            // Check if item is an object
                            if (is_object($itemValues))
                            {
                                // Create new min max selector and add to configuration
                                $minMaxSelector = new MinMaxSelector();
                                $minMaxSelector->jsonDeserialize($itemValues);
                                
                                $this->minMaxSelector[$paymentMethodIndex][$currency] = $minMaxSelector;
                            }
                        }
                    }
                }
                return;
            case 'klarna':
                $this->klarna = new Klarna();
                $this->klarna->jsonDeserialize($value);
                return;
            case 'iDeal':
                $this->iDeal = new IDeal();
                $this->iDeal->jsonDeserialize($value);
                return;
            case 'payPal':
                $this->payPal = new PayPal();
                $this->payPal->jsonDeserialize($value);
                return;
            case 'sofortBanking':
                $this->sofortBanking = new SofortBanking();
                $this->sofortBanking->jsonDeserialize($value);
                return;
        }
        
        parent::setJsonData($name, $value);
    }
}