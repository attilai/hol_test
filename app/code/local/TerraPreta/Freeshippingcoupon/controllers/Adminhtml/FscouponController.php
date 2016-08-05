<?php

/**
 * Footer links admin controller
 *
 * @category   Terrapreta
 * @package    Terrapreta_Freeshippingcoupon
 */
class TerraPreta_Freeshippingcoupon_Adminhtml_FscouponController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Footer Links'), Mage::helper('adminhtml')->__('Footer Links'));           
        return $this;
    }
    
    public function indexAction()
    {
        //$model = Mage::getModel('freeshippingcoupon/multiflat');
        //var_dump($model); //die();
        //die('this?');
		$this->_initAction()
            ->_setActiveMenu('freeshippingcoupon')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Footer Links'), Mage::helper('adminhtml')->__('Footer Links'))
            ->_addContent($this->getLayout()->createBlock('freeshippingcoupon/adminhtml_fscoupon'))
            ->renderLayout();    	
    }
    
    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {        
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
            //$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $couponPartFirst = substr(str_shuffle($chars),0,3);
            $couponPartLast = substr(str_shuffle($chars),0,5);        
            $couponCode = $couponPartFirst.''.$couponPartLast;

            $postData = Mage::app()->getRequest()->getPost();
            //var_dump($postData); die();
            $saved = (isset($postData['from_date']) ? $postData['from_date'] : date('d-m-Y', strtotime(date('Y-m-d H:i:s')) + 86400));
            $time = explode("-", $saved);
            krsort($time);
            $expireDate = implode("-",$time);

            $coupon = Mage::getModel('salesrule/rule');
            $coupon->setName('Gratis verzending coupon')
            ->setDescription('Dit is een automatisch geneereerde coupon code, voor gratis verzendingen. Aangemaakt op: '.date('Y-m-d H:i:s'))
            //->setFromDate(null)
            ->setFromDate(date('Y-m-d'))
            ->setToDate($expireDate)
            ->setCouponType(2)
            ->setCouponCode($couponCode)
            ->setUsesPerCoupon(1)
            ->setUsesPerCustomer(1)
            ->setCustomerGroupIds(array(0,1,2,3)) //an array of customer groupids
            ->setIsActive(1)
            //serialized conditions.  the following examples are empty
            ->setConditionsSerialized('a:6:{s:4:"type";s:32:"salesrule/rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}')
            ->setActionsSerialized('a:6:{s:4:"type";s:40:"salesrule/rule_condition_product_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}')
            ->setStopRulesProcessing(0)
            ->setIsAdvanced(1)
            ->setProductIds('')
            ->setSortOrder(0)
            ->setSimpleAction('by_percent')
            ->setDiscountAmount(0)
            ->setDiscountQty(null)
            ->setDiscountStep('0')
            //->setSimpleFreeShipping('1')
			//->setMsmultiflat('0')
            //->setApplyToShipping('0')
            ->setIsRss(0)
            ->setWebsiteIds(array(1));
            $coupon->save();
            Mage::getSingleton('admin/session')->setData('freeShippingCouponCode',$couponCode);
            $this->_redirect('adminfreeshippingcoupon/adminhtml_fscoupon');
        } else {
            $this->_redirect('adminfreeshippingcoupon/adminhtml_fscoupon');
        }

    }
    

}