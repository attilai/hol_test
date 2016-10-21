<?php

namespace PayCheckout\Json\Generic\Configuration\Klarna;

use PayCheckout\Json\JsonBase;

class Klarna extends JsonBase
{
    /**
     * @var int
     */
    protected $eid;
    
    /**
     * @var string
     */
    protected $secret;
    
    /**
     * @var bool
     */
    protected $fixDuplicateOrderItemNames;
    
    /**
     * @var KlarnaCountrySpecific[]
     */
    protected $klarnaCountrySpecific;
    
    /**
     * @var bool
     */
    protected $sendKlarnaEmailWhenInvoiceIsCreated;
    
	/**
	 * @var bool
	 */
	protected $sendKlarnaEmailWhenInvoiceIsAltered;
    
    /**
     * @return int
     */
    public function getEid()
    {
        return $this->eid;
    }
    
    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
    
    /**
     * @return bool
     */
    public function isFixDuplicateOrderItemNames()
    {
        return $this->fixDuplicateOrderItemNames;
    }
        
    /**
     * @return bool
     */
    public function isSendKlarnaEmailWhenInvoiceIsCreated()
    {
        return $this->sendKlarnaEmailWhenInvoiceIsCreated;
    }
    
    /**
     * @return bool
     */
    public function isSendKlarnaEmailWhenInvoiceIsAltered()
    {
        return $this->sendKlarnaEmailWhenInvoiceIsAltered;
    }
    
    /**
     * @return KlarnaCountrySpecific[]
     */
    public function getKlarnaCountrySpecific()
    {
        return $this->klarnaCountrySpecific;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        switch($name)
        {
            case 'klarnaCountrySpecific':
                // Items needs to an array (list of Klarna country specifics)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new Klarna country specific and add to configuration
                            $klarnaCountrySpecific = new KlarnaCountrySpecific();
                            $klarnaCountrySpecific->jsonDeserialize($itemValues);
                            
                            $this->klarnaCountrySpecific[] = $klarnaCountrySpecific;
                        }
                    }
                }
                return;
        }

        parent::setJsonData($name, $value);
    }
}