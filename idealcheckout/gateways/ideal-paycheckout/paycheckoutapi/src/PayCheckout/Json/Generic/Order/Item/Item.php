<?php

namespace PayCheckout\Json\Generic\Order\Item;

use PayCheckout\Json\JsonBase;

class Item extends JsonBase
{
    /**
     * @var int
     */
    protected $orderLineNumber;
    
    /**
     * @var int
     */
    protected $itemType;
    
    /**
     * Optional: typical usage: UPC, EAN, GTIN or APN code
     * 
     * @var string
     */
    protected $skuCode;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $description;
    
    /**
     * @var int
     */
    protected $quantity;
    
    /**
     * Price per unit exclusive VAT (including discount)
     * 
     * @var int
     */
    protected $unitPriceExclusiveVat;
    
    /**
     * Price per unit inclusive VAT (including discount)
     * 
     * @var int
     */
    protected $unitPriceInclusiveVat;
    
    /**
     * @var int
     */
    protected $vatDisplayPercentage;
    
    /**
     * @var int
     */
    protected $discountDisplayPercentage;
    
    /**
     * @var int
     */
    protected $unitPriceMultiplyFactorOverride;
    
    /**
     * Create new order item
     * 
     * @param int $orderLineNumber
     * @param int $itemType
     * @param string $name 
     * @param string $description 
     * @param int $quantity
     * @param int $unitPriceExclusiveVat 
     * @param int $unitPriceInclusiveVat 
     * @param int $vatDisplayPercentage 
     * @param int $discountDisplayPercentage 
     * @param string $skuCode
     * @param int $unitPriceMultiplyFactorOverride
     */
    public function __construct($orderLineNumber = null, $itemType = null, $name = null, $description = null, $quantity = null, $unitPriceExclusiveVat = null, 
        $unitPriceInclusiveVat = null, $vatDisplayPercentage = null, $discountDisplayPercentage = null, $skuCode = null, $unitPriceMultiplyFactorOverride = null)
    {
        $this->orderLineNumber                  = $orderLineNumber;
        $this->itemType                         = $itemType;
        $this->name                             = $name;
        $this->description                      = $description;
        $this->quantity                         = $quantity;
        $this->unitPriceExclusiveVat            = $unitPriceExclusiveVat;
        $this->unitPriceInclusiveVat            = $unitPriceInclusiveVat;
        $this->vatDisplayPercentage             = $vatDisplayPercentage;
        $this->discountDisplayPercentage        = $discountDisplayPercentage;
        $this->skuCode                          = $skuCode;
        $this->unitPriceMultiplyFactorOverride  = $unitPriceMultiplyFactorOverride;
    }
    
    /**
     * @return int
     */
    public function getOrderLineNumber()
    {
        return $this->orderLineNumber;
    }
    
    /**
	 * @param int $orderLineNumber
     */
    public function setOrderLineNumber($orderLineNumber)
    {
        $this->orderLineNumber = $orderLineNumber;
    }
    
    /**
     * @return int
     */
    public function getItemType()
    {
        return $this->itemType;
    }
    
    /**
     * @param int $itemType
     */
    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }
    
    /**
     * @return string
     */
    public function getSkuCode()
    {
        return $this->skuCode;
    }
    
    /**
     * @param string $skuCode
     */
    public function setSkuCode($skuCode)
    {
        $this->skuCode = $skuCode;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    
    /**
     * @return int
     */
    public function getUnitPriceExclusiveVat()
    {
        return $this->unitPriceExclusiveVat;
    }
    
    /**
     * @param int $unitPriceExclusiveVat
     */
    public function setUnitPriceExclusiveVat($unitPriceExclusiveVat)
    {
        $this->unitPriceExclusiveVat = $unitPriceExclusiveVat;
    }
    
    /**
     * @return int
     */
    public function getUnitPriceInclusiveVat()
    {
        return $this->unitPriceInclusiveVat;
    }
    
    /**
     * @param int $unitPriceInclusiveVat
     */
    public function setUnitPriceInclusiveVat($unitPriceInclusiveVat)
    {
        $this->unitPriceInclusiveVat = $unitPriceInclusiveVat;
    }
    
    /**
     * @return int
     */
    public function getVatDisplayPercentage()
    {
        return $this->vatDisplayPercentage;
    }
    
    /**
     * @param int $vatDisplayPercentage
     */
    public function setVatDisplayPercentage($vatDisplayPercentage)
    {
        $this->vatDisplayPercentage = $vatDisplayPercentage;
    }
    
    /**
     * @return int
     */
    public function getDiscountDisplayPercentage()
    {
        return $this->discountDisplayPercentage;
    }
    
    /**
     * @param int $discountDisplayPercentage
     */
    public function setDiscountDisplayPercentage($discountDisplayPercentage)
    {
        $this->discountDisplayPercentage = $discountDisplayPercentage;
    }
     
    /**
     * @return int
     */
    public function getUnitPriceMultiplyFactorOverride()
    {
        return $this->unitPriceMultiplyFactorOverride;
    }  
}