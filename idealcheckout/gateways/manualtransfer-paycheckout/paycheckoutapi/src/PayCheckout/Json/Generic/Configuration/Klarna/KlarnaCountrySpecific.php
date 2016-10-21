<?php

namespace PayCheckout\Json\Generic\Configuration\Klarna;

use PayCheckout\Json\JsonBase;

class KlarnaCountrySpecific extends JsonBase
{
    /**
     * @var string
     */
    protected $countryIso3166Alpha2;
    
    /**
     * @var bool
     */
    protected $usesKlarnaAccount;
    
    /**
     * @var bool
     */
    protected $usesKlarnaInvoice;
    
    /**
     * @return string
     */
    public function getCountryIso3166Alpha2()
    {
        return $this->countryIso3166Alpha2;
    }
    
    /**
     * @return bool
     */
    public function usesKlarnaAccount()
    {
        return $this->usesKlarnaAccount;
    }
    
    /**
     * @return bool
     */
    public function usesKlarnaInvoice()
    {
        return $this->usesKlarnaInvoice;
    }
}