<?php

namespace PayCheckout\Json\Generic\Order\Item;

class OrderItem extends Item
{    
    /**
     * @var int
     */
    protected $quantityInvoiced;
    
    /**
     * @var int
     */
    protected $quantityPaid;
    
    /**
     * @var int
     */
    protected $quantityRefunded;
    
    /**
     * @var int
     */
    protected $quantityCancelled;
    
    /**
     * Create new order item
     * 
     * @param int $orderLineNumber
     * @param int $itemType
     * @param string $name
     * @param string $description
     * @param int $quantity
     * @param int $quantityCancelled
     * @param int $quantityInvoiced
     * @param int $quantityRefunded
     * @param int $unitPriceExclusiveVat
     * @param int $unitPriceInclusiveVat
     * @param int $vatDisplayPercentage
     * @param int $discountDisplayPercentage
     * @param string $skuCode
     * @param int $unitPriceMultiplyFactorOverride
     */
    public function __construct($orderLineNumber = null, $itemType = null, $name = null, $description = null, $quantity = null,
        $quantityCancelled = null, $quantityInvoiced = null, $quantityRefunded = null, $unitPriceExclusiveVat = null, 
        $unitPriceInclusiveVat = null, $vatDisplayPercentage = null, $discountDisplayPercentage = null, $skuCode = null, $unitPriceMultiplyFactorOverride = null)
    {
        parent::__construct($orderLineNumber, $itemType, $name, $description, $quantity, $unitPriceExclusiveVat, 
            $unitPriceInclusiveVat, $vatDisplayPercentage, $discountDisplayPercentage, $skuCode, $unitPriceMultiplyFactorOverride );
		
        $this->quantity				= $quantity;
        $this->quantityInvoiced		= $quantityInvoiced;
        $this->quantityRefunded		= $quantityRefunded;
        $this->quantityCancelled	= $quantityCancelled;
    }
    
    /**
     * @return int
     */
    public function getQuantityInvoiced()
    {
        return $this->quantityInvoiced;
    }
    
    /**
     * @param int $quantityInvoiced
     */
    public function setQuantityInvoiced($quantityInvoiced)
    {
        $this->quantityInvoiced = $quantityInvoiced;
    }
    
    /**
     * @return int
     */
    public function getQuantityPaid()
    {
        return $this->quantityPaid;
    }
    
    /**
     * @param int $quantityPaid
     */
    public function setQuantityPaid($quantityPaid)
    {
        $this->quantityPaid = $quantityPaid;
    }
    
    /**
     * @return int
     */
    public function getQuantityRefunded()
    {
        return $this->quantityRefunded;
    }
    
    /**
     * @param int $quantityRefunded
     */
    public function setQuantityRefunded($quantityRefunded)
    {
        $this->quantityRefunded = $quantityRefunded;
    }
    
    /**
     * @return int
     */
    public function getQuantityCancelled()
    {
        return $this->quantityCancelled;
    }

    /**
     * @param int $quantityCancelled
     */
    public function setQuantityCancelled($quantityCancelled)
    {
        $this->quantityCancelled = $quantityCancelled;
    }
}