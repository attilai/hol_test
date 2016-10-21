<?php

namespace PayCheckout\Api\Service\Klarna;

class KlarnaAddress
{
    /**
     * @var string
     */
    private $firstName;
    
    /**
     * @var string
     */
    private $lastOrCompanyName;
    
    /**
     * @var string
     */
    private $address;
    
    /**
     * @var string
     */
    private $postalCode;
    
    /**
     * @var string
     */
    private $city;
    
    /**
     * @var string
     */
    private $country;
    
    /**
     * Create new Klarna address
     *
     * @param string $firstName 
     * @param string $lastOrCompanyName 
     * @param string $address 
     * @param string $postalCode 
     * @param string $city 
     * @param string $country 
     */
    public function __construct($firstName, $lastOrCompanyName, $address, $postalCode, $city, $country)
    {
        $this->firstName            = $firstName;
        $this->lastOrCompanyName    = $lastOrCompanyName;
        $this->address              = $address;
        $this->postalCode           = $postalCode;
        $this->city                 = $city;
        $this->country              = $country;
    }
    
    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * @return string
     */
    public function getLastOrCompanyName()
    {
        return $this->lastOrCompanyName;
    }
    
    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }
    
    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}