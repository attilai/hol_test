<?php
/**
 * @category    Mage
 * @package     Mage_Sales
 */


class BD_NegativeDiscount_Model_Quote_Discount extends Mage_SalesRule_Model_Quote_Discount
{
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getDiscountAmount();
        if ($amount!=0) {
			if($amount<0)
        	{
            	$title = Mage::helper('sales')->__('Discount');
				
				if ($code = $address->getCouponCode()) {
					$title = Mage::helper('sales')->__('Discount (%s)', $code);
				}
				$address->addTotal(array(
					'code'=>$this->getCode(),
					'title'=>$title,
					'value'=>-$amount
				));				
        	}
        	else
        	{
        		$title = Mage::helper('sales')->__('Extra kosten');
				
				if ($code = $address->getCouponCode()) {
					$title = Mage::helper('sales')->__('Extra kosten (%s)', $code);
				}
				$address->addTotal(array(
					'code'=>$this->getCode(),
					'title'=>$title,
					'value'=>$amount
				));				
        	}
        }
        return $this;
    }

}
