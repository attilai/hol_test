<?php

namespace PayCheckout\Api\Service\Ideal;

class Country
{
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var Issuer[]
     */
    private $issuers;
    
    /**
     * Create new country
     * 
     * @param string $name 
     * @param Issuer[] $issuers 
     */
    public function __construct($name, array $issuers)
    {
        $this->name		= $name;
        $this->issuers	= $issuers;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return Issuer[]
     */
    public function getIssuers()
    {
        return $this->issuers;
    }
}