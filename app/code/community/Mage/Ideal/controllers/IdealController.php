<?php

	class Mage_Ideal_IdealController extends Mage_Core_Controller_Front_Action
	{
		protected $_code = 'ideal'; 


		// Go to iDEAL
		public function redirectAction()
		{
			// Load /app/code/community/Mage/Ideal/Model/Ideal.php
			$oIdealModel = Mage::getSingleton('ideal/ideal');


			// Create transaction record and get URL to /ideal/setup.php
			$sIdealUrl = $oIdealModel->setupPayment();


			// redirect
			header('Location: ' . $sIdealUrl);
			exit();
		}


		// Return from iDEAL
		public function returnAction() 
		{
			$oIdealModel = Mage::getSingleton('ideal/ideal');

			$sOrderId = $this->getRequest()->get('order_id');
			$sOrderCode = $this->getRequest()->get('order_code');


			if($oIdealModel->validatePayment($sOrderId, $sOrderCode))
			{ 
				$this->_redirect('checkout/onepage/success', array('_secure' => true));
			}
			else 
			{
				$this->_redirect('checkout/cart');
			} 
		}
	}

?>