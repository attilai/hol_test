<?php

namespace PayCheckout\Json\Generic\Order;

use DateTime;
use PayCheckout\Json\Generic\Order\Identity\Address;
use PayCheckout\Json\Generic\Order\Identity\Identity;
use PayCheckout\Json\Generic\Order\Item\OrderItem;
use PayCheckout\Json\JsonBase;

class Order extends JsonBase
{
    /**
     * @var Identity
     */
    protected $billingIdentity;
    
    /**
     * @var Address
     */
    protected $shippingAddress;
    
    /**
     * @var OrderItem[]
     */
    protected $items;
    
    /**
     * @var string
     */
    protected $customerOrderRef;
    
    /**
     * @var string
     */
    protected $customerNote;
    
    /**
     * @var int
     */
    protected $currency;
        
    /**
     * @var integer
     */
    protected $quantityMultiplyFactor;
    
    /**
     * Create new order
     */
    public function __construct()
    {
        $this->items = array();
    }
    
    /**
     * @return Identity
     */
    public function getBillingIdentity()
    {
        return $this->billingIdentity;
    }
    
    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
    
    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }
       
    /**
     * @param OrderItem $item 
     */
    public function addItem(OrderItem $item)
    {
        $this->items[] = $item;
    }
    
    /**
     * @return string
     */
    public function getCustomerOrderReference()
    {
        return $this->customerOrderRef;
    }
    
    /**
     * @param string $customerOrderRef
     */
    public function setCustomerOrderReference($customerOrderRef)
    {
        $this->customerOrderRef = $customerOrderRef;
    }
    
    /**
     * @return string
     */
    public function getCustomerNote()
    {
        return $this->customerNote;
    }
    
    /**
     * @param string $customerNote
     */
    public function setCustomerNote($customerNote)
    {
        $this->customerNote = $customerNote;
    }
        
    /**
     * @return int
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param int $currency 
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    /**
     * @return int
     */
    public function getQuantityMultiplyFactor()
    {
        return $this->quantityMultiplyFactor;
    }
    
    /**
     * @param int $quantityMultiplyFactor 
     */
    public function setQuantityMultiplyFactor($quantityMultiplyFactor)
    {
        $this->quantityMultiplyFactor = $quantityMultiplyFactor;
    }
    
    /**
     * Set billing address of order
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
    public function setBillingAddress($countryIso3166Alpha2, $firstName, $lastName, $addressLine1, $zipCode, $city,
        $title = null, $addressLine2 = null, $stateProvince = null, DateTime $dateOfBirth = null, $emailAddress = null, $gender = null,
        $phoneNumber = null, $phoneNumber2 = null, $cellPhoneNumber = null, $socialSecurityNumber = null, $organisation = null, $department = null, $chamberOfCommerceNumber = null, $vatNumber = null)
    {
        $this->billingIdentity = new Identity(
            $countryIso3166Alpha2,
			$firstName,
			$lastName,
			$addressLine1,
			$zipCode,
			$city,
			$title,
			$addressLine2,
			$stateProvince,
			$dateOfBirth, 
            $emailAddress,
			$gender,
			$phoneNumber,
			$phoneNumber2,
			$cellPhoneNumber,
			$socialSecurityNumber,
			$organisation,
			$department,
			$chamberOfCommerceNumber,
			$vatNumber    
        );
    }
    
    /**
     * Set shipping address of order
     * 
     * @param string $countryIso3166Alpha2
     * @param string $firstName
     * @param string $lastName
     * @param string $addressLine1
     * @param string $zipCode
     * @param string $city
     * @param string $phoneNumber
     * @param string $cellPhoneNumber
     * @param string $title
     * @param string $addressLine2
     * @param string $stateProvince
     * @param string $organisation
     * @param string $department
     */
    public function setShippingAddress($countryIso3166Alpha2, $firstName, $lastName, $addressLine1, $zipCode, $city,
        $title = null, $addressLine2 = null, $stateProvince = null, $phoneNumber = null, $cellPhoneNumber = null, $organisation = null, $department = null)
    {
        $this->shippingAddress = new Address(
            $countryIso3166Alpha2,
			$firstName,
			$lastName,
			$addressLine1,
			$zipCode,
			$city,
            $title,
			$addressLine2,
			$stateProvince,
			$phoneNumber,
			$cellPhoneNumber,
			$organisation,
			$department
        );
    }
    
    /**
     * Set order info of payment
     * 
     * @param string $customerOrderReference
     * @param string $customerNote
     */
    public function setInfo($customerOrderReference = null, $customerNote = null)
    {
        $this->customerOrderRef = $customerOrderReference;
        $this->customerNote		= $customerNote;
    }
    
    /**
     * Add item to order
     * 
     * @param int $orderLineNumber 
     * @param string $name
     * @param string $description
     * @param float $quantityOrdered
     * @param float $quantityCancelled
     * @param float $quantityInvoiced
     * @param float $quantityRefunded
     * @param float $unitPriceExclusiveVat
     * @param float $unitPriceInclusiveVat
     * @param float $vatDisplayPercentage
     * @param int $itemType
     * @param float $discountDisplayPercentage
     * @param string $skuCode
     * @param int $unitPriceMultiplyFactorOverride
     */
    public function addNewItem($orderLineNumber, $name, $description, $quantityOrdered, $quantityCancelled, $quantityInvoiced, $quantityRefunded, 
        $unitPriceExclusiveVat, $unitPriceInclusiveVat, $vatDisplayPercentage, $itemType,
        $discountDisplayPercentage = null, $skuCode = null, $unitPriceMultiplyFactorOverride = null)
    {
        $this->items[] = new OrderItem(
            $orderLineNumber,
			$itemType,
			$name,
			$description,
			$quantityOrdered,
			$quantityCancelled,
			$quantityInvoiced,
			$quantityRefunded, 
			$unitPriceExclusiveVat,
			$unitPriceInclusiveVat,
			$vatDisplayPercentage,
			$discountDisplayPercentage,
			$skuCode,
            $unitPriceMultiplyFactorOverride
        );
    }
    
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        switch($name)
        {
            case 'billingIdentity':
                $this->billingIdentity = new Identity();
                $this->billingIdentity->jsonDeserialize($value);
                return;
            case 'shippingAddress':
                $this->shippingAddress = new Address();
                $this->shippingAddress->jsonDeserialize($value);
                return;
            case 'items':
                // Items needs to an array (list of items)
                if (is_array($value))
                {
                    foreach ($value as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new item and add to items
                            $item = new OrderItem();
                            $item->jsonDeserialize($itemValues);
							
                            $this->addItem($item);
                        }
                    }
                }
                return;
        }

        parent::setJsonData($name, $value);
    }
}