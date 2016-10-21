<?php

namespace PayCheckout\Json\Generic\Order\Identity;

use PayCheckout\Json\JsonBase;

class Address extends JsonBase
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $firstName;
    
    /**
     * @var string
     */
    protected $lastName;
    
    /**
     * @var string
     */
    protected $countryIso3166Alpha2;
    
    /**
     * @var string
     */
    protected $addressLine1;
    
    /**
     * @var string
     */
    protected $addressLine2;
    
    /**
     * @var string
     */
    protected $zipCode;
    
    /**
     * @var string
     */
    protected $city;
    
    /**
     * @var string
     */
    protected $stateProvince;
    
    /**
     * @var string
     */
    protected $phoneNumber;
    
    /**
     * @var string
     */
    protected $cellPhoneNumber;
    
    /**
     * Organisation specific
     * 
     * @var string
     */
    protected $organisation;
    
    /**
     * Organisation specific
     * 
     * @var string
     */
    protected $department;
    
    /**
     * Create new address
     * 
     * @param string $countryIso3166Alpha2 
     * @param string $firstName 
     * @param string $lastName 
     * @param string $addressLine1 
     * @param string $zipCode 
     * @param string $city 
     * @param string $title 
     * @param string $addressLine2 
     * @param string $stateProvince 
     * @param string $phoneNumber 
     * @param string $cellPhoneNumber 
     * @param string $organisation 
     * @param string $department 
     */
    public function __construct($countryIso3166Alpha2 = null, $firstName = null, $lastName = null, $addressLine1 = null, $zipCode = null, $city = null,
        $title = null, $addressLine2 = null, $stateProvince = null,$phoneNumber = null, $cellPhoneNumber = null, $organisation = null, $department = null)
    {
        $this->countryIso3166Alpha2 = $countryIso3166Alpha2;
        $this->firstName            = $firstName;
        $this->lastName             = $lastName;
        $this->addressLine1         = $addressLine1;
        $this->zipCode              = $zipCode;
        $this->city                 = $city;
        $this->title                = $title;
        $this->addressLine2         = $addressLine2;
        $this->stateProvince        = $stateProvince;
        $this->phoneNumber          = $phoneNumber;
        $this->cellPhoneNumber      = $cellPhoneNumber;
        $this->organisation         = $organisation;
        $this->department           = $department;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    
    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    
    /**
     * @return string
     */
    public function getCountryIso3166Alpha2()
    {
        return $this->countryIso3166Alpha2;
    }
    
    /**
     * @param string $countryIso3166Alpha2
     */
    public function setCountryIso3166Alpha2($countryIso3166Alpha2)
    {
        $this->countryIso3166Alpha2 = $countryIso3166Alpha2;
    }
    
    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }
    
    /**
     * @param string $addressLine1
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }
    
    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }
    
    /**
     * @param string $addressLine2
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }
    
    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
    
    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }
    
    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
    
    /**
     * @return string
     */
    public function getStateProvince()
    {
        return $this->stateProvince;
    }
    
    /**
     * @param string $stateProvince
     */
    public function setStateProvince($stateProvince)
    {
        $this->stateProvince = $stateProvince;
    }
    
    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    /**
     * @return string
     */
    public function getCellPhoneNumber()
    {
        return $this->cellPhoneNumber;
    }
    
    /**
     * @param string $cellPhoneNumber
     */
    public function setCellPhoneNumber($cellPhoneNumber)
    {
        $this->cellPhoneNumber = $cellPhoneNumber;
    }
    
    /**
     * @return string
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }
    
    /**
     * @param string $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }
    
    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }
    
    /**
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }
}