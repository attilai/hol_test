<?php

namespace PayCheckout\Json\Generic\Order\Identity;

use DateTime;
use PayCheckout\IdentityType;
use PayCheckout\Json\JsonBase;

class Identity extends JsonBase
{
    /**
     * @var int
     */
    protected $identityType;
    
    /**
     * @var string
     */
    protected $emailAddress;
    
    /**
     * @var int
     */
    protected $gender;
    
    /**
     * @var Address
     */
    protected $address;
    
    /**
     * @var string
     */
    protected $phoneNumber2;
    
    /**
     * @var DateTime|null
     */
    protected $dateOfBirth;
    
    /**
     * @var string
     */
    protected $socialSecurityNumber;
    
    /**
     * Organisation specific
     * 
     * @var string
     */
    protected $chamberOfCommerceNumber;
    
    /**
     * Organisation specific
     * 
     * @var string
     */
    protected $vatNumber;
    
    /**
     * Create new identity (and address)
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
     * @param DateTime|null $dateOfBirth 
     * @param string $emailAddress 
     * @param int $gender 
     * @param string $phoneNumber 
     * @param string $phoneNumber2 
     * @param string $cellPhoneNumber 
     * @param string $socialSecurityNumber 
     * @param string $organisation 
     * @param string $department 
     * @param string $chamberOfCommerceNumber 
     * @param string $vatNumber 
     */
    public function __construct($countryIso3166Alpha2 = null, $firstName = null, $lastName = null, $addressLine1 = null, $zipCode = null, $city = null,
        $title = null, $addressLine2 = null, $stateProvince = null, DateTime $dateOfBirth = null, $emailAddress = null, $gender = null,
        $phoneNumber = null, $phoneNumber2 = null, $cellPhoneNumber = null, $socialSecurityNumber = null, $organisation = null, $department = null, $chamberOfCommerceNumber = null, $vatNumber = null)
    {
        // Identity
        $this->dateOfBirth              = $dateOfBirth;
        $this->emailAddress             = $emailAddress;
        $this->gender                   = $gender;
        $this->phoneNumber2             = $phoneNumber2;
        $this->socialSecurityNumber     = $socialSecurityNumber;
        $this->chamberOfCommerceNumber  = $chamberOfCommerceNumber;
        $this->vatNumber                = $vatNumber;
        
        // It's important to remove the time
        if ($dateOfBirth != null)
        {
            $this->dateOfBirth->setTime(0, 0, 0);
        }
        
        if ($organisation != null)
        {
            $this->identityType = IdentityType::ORGANISATION;
        }
        else
        {
            $this->identityType = IdentityType::PERSON;
        }
        
        // Address
        $this->setAddress(new Address(
            $countryIso3166Alpha2, $firstName, $lastName, $addressLine1, $zipCode, $city,
            $title, $addressLine2, $stateProvince, $phoneNumber, $cellPhoneNumber, $organisation, $department
        ));
    }
    
    /**
     * @return int
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }
    
    /**
     * @param int $identityType
     */
    public function setIdentityType($identityType)
    {
        $this->identityType = $identityType;
    }
    
    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
    
    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }
    
    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    /**
     * @param int $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
    
    /**
     * @return string
     */
    public function getPhoneNumber2()
    {
        return $this->phoneNumber2;
    }
    
    /**
     * @param string $phoneNumber2
     */
    public function setPhoneNumber2($phoneNumber2)
    {
        $this->phoneNumber2 = $phoneNumber2;
    }
    
    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }
    
    /**
     * @return DateTime|null
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    
    /**
     * @param DateTime|null $dateOfBirth
     */
    public function setDateOfBirth(DateTime $dateOfBirth = null)
    {
        $this->dateOfBirth = $dateOfBirth;
    }
    
    /**
     * @return string
     */
    public function getSocialSecurityNumber()
    {
        return $this->socialSecurityNumber;
    }
    
    /**
     * @param string $socialSecurityNumber
     */
    public function setSocialSecurityNumber($socialSecurityNumber)
    {
        $this->socialSecurityNumber = $socialSecurityNumber;
    }
    
    /**
     * @return string
     */
    public function getChamberOfCommerceNumber()
    {
        return $this->chamberOfCommerceNumber;
    }
    
    /**
     * @param string $chamberOfCommerceNumber
     */
    public function setChamberOfCommerceNumber($chamberOfCommerceNumber)
    {
        $this->chamberOfCommerceNumber = $chamberOfCommerceNumber;
    }
    
    /**
     * @return string
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }
    
    /**
     * @param string $vatNumber
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        switch($name)
        {
            case 'dateOfBirth':
                $this->dateOfBirth = new DateTime($value);
                return;
            case 'address':
                $this->address = new Address();
                $this->address->jsonDeserialize($value);
                return;
        }
        
        parent::setJsonData($name, $value);
    }
}