<?php

	class Mage_Ideal_Model_Ideal extends Mage_Payment_Model_Method_Abstract
	{
		protected $_code = 'ideal';

		protected $_isGateway = false;
		protected $_canAuthorize = false;
		protected $_canCapture = true;
		protected $_canCapturePartial = false;
		protected $_canRefund = false;
		protected $_canVoid = false;
		protected $_canUseInternal = false;
		protected $_canUseCheckout = true;
		protected $_canUseForMultishipping = false;   


		// This url is called after order confirmation; It triggers IdealController->redirectAction()
		public function getOrderPlaceRedirectUrl() 
		{
			$sUrl = Mage::getUrl('ideal/ideal/redirect', array('_secure' => true));
			return $this->fixUrl($sUrl, false);
		}
	


		// Setup ideal transaction record in database - called in: IdealController->redirectAction()
		public function setupPayment()
		{
			$iOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
			$this->_order = Mage::getModel('sales/order')->load($iOrderId);
			// $iOrderId = $oSession->getCheckout()->getLastRealOrderId();
			// $this->_order = Mage::getModel('sales/order')->loadByIncrementId($iOrderId);


			// Validate order
			if(!$this->_order->getId())
			{
				Mage::throwException('Cannot load order #' . $iOrderId);
			}


			// Validate currency
			if(strcasecmp(Mage::app()->getStore()->getCurrentCurrencyCode(), 'EUR') !== 0)
			{
				Mage::throwException(Mage::helper('ideal')->__('The selected currency is not supported. Please use EURO.'));
			}


			// Validate amount
			if($this->_order->getGrandTotal() < 1.00)
			{
				Mage::throwException(Mage::helper('ideal')->__('The total amount of order #' . $iOrderId . ' is ' . $this->_order->getGrandTotal() . ', but should be at least 1.00.'));
			}



			// Load gateway config
			require_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/ideal/config.php');
			$aGatewaySettings = gateway_getSettings();

			// Load gateway scripts
			require_once($aGatewaySettings['GATEWAY_FILE']);



			// Find transaction record values
			$sOrderId = $this->_order->getRealOrderId(); // Find order ID
			$sOrderCode = GatewayCore::randomCode(32);
			$sTransactionId = GatewayCore::randomCode(32);
			$sTransactionCode = GatewayCore::randomCode(32);
			$sTransactionMethod = $aGatewaySettings['GATEWAY_METHOD'];
			$fTransactionAmount = $this->_order->getGrandTotal(); // Find order TOTAL
			$sTransactionDescription = 'Webshop Bestelling #' . $sOrderId;


			$sUrl = Mage::getUrl('ideal/ideal/return', array('_secure' => true, 'order_id' => $sOrderId, 'order_code' => $sOrderCode));
			$sUrl = $this->fixUrl($sUrl, false);


			$sTransactionPaymentUrl = Mage::getUrl('checkout');
			$sTransactionSuccessUrl = $sUrl;
			$sTransactionPendingUrl = $sUrl;
			$sTransactionFailureUrl = Mage::getUrl('checkout');


			// Create query
			$sql = "INSERT INTO `" . Mage::getConfig()->getTablePrefix() . "transactions` SET 
			`id` = NULL, 
			`order_id` = '" . addslashes($sOrderId) . "', 
			`order_code` = '" . addslashes($sOrderCode) . "', 
			`transaction_id` = '" . addslashes($sTransactionId) . "', 
			`transaction_code` = '" . addslashes($sTransactionCode) . "', 
			`transaction_method` = '" . addslashes($sTransactionMethod) . "', 
			`transaction_date` = '" . addslashes(time()) . "', 
			`transaction_amount` = '" . addslashes($fTransactionAmount) . "', 
			`transaction_description` = '" . addslashes($sTransactionDescription) . "', 
			`transaction_status` = NULL, 
			`transaction_url` = NULL, 
			`transaction_payment_url` = '" . addslashes($sTransactionPaymentUrl) . "', 
			`transaction_success_url` = '" . addslashes($sTransactionSuccessUrl) . "', 
			`transaction_pending_url` = '" . addslashes($sTransactionPendingUrl) . "', 
			`transaction_failure_url` = '" . addslashes($sTransactionFailureUrl) . "', 
			`transaction_params` = NULL, 
			`transaction_log` = NULL;";


			// Add record to transaction table
			Mage::getSingleton('core/resource')->getConnection('core_write')->query($sql);



			// Return iDEAL URL
			$sUrl = Mage::getBaseUrl() . 'ideal/setup.php?order_id=' . $sOrderId . '&order_code=' . $sOrderCode;
			$sUrl = $this->fixUrl($sUrl, true);
			return $sUrl;
		}

		// Validate payment after user returns from iDEAL - called in: IdealController->returnAction()
		public function validatePayment($sOrderId, $sOrderCode)
		{
			// Set default state & reply
			$sState = Mage_Sales_Model_Order::STATE_CANCELED;
			$bReply = false;

			// Auto create invoices on success
			$bCreateInvoice = Mage::getStoreConfig('payment/ideal/autocreate_invoice');

			// Find transaction record in database
			$sql = "SELECT * FROM `" . Mage::getConfig()->getTablePrefix() . "transactions` WHERE `order_id` = '" . addslashes($sOrderId) . "' AND (`order_code` = '" . addslashes($sOrderCode) . "') ORDER BY `id` DESC LIMIT 1;";
			$oRecordset = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);

			// See if record is available
			if(isset($oRecordset[0]['transaction_status'])) // if(sizeof($oRecordset))
			{
				$sTransactionStatus = $oRecordset[0]['transaction_status'];

				// Load order
				$this->_order = Mage::getModel('sales/order')->loadByIncrementId($oRecordset[0]['order_id']);

				if(in_array($sTransactionStatus, array('SUCCESS')))
				{
					$sState = Mage_Sales_Model_Order::STATE_PROCESSING;
					$bReply = true;
				}
				elseif(in_array($sTransactionStatus, array('PENDING')))
				{
					$sState = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
				}
				elseif(in_array($sTransactionStatus, array('OPEN')))
				{
					header('Location: ' . $oRecordset[0]['transaction_url']);
					exit;
				}
				elseif(in_array($sTransactionStatus, array('')))
				{
					$sUrl = Mage::getBaseUrl() . 'ideal/setup.php?order_id=' . $sOrderId . '&order_code=' . $sOrderCode;
					$sUrl = $this->fixUrl($sUrl, true);

					header('Location: ' . $sUrl);
					exit;
				}
				else // if(in_array($sTransactionStatus, array('EXPIRED', 'CANCELLED', 'FAILURE')))
				{
					$sState = Mage_Sales_Model_Order::STATE_CANCELED;
				}


				// update order status
				if(strcmp($sState, $this->_order->getState()) !== 0)
				{
					$this->_order->setState($sState, true);
					$this->_order->save();
				}


				// Send mails
				if($bReply)
				{
					$this->_order->sendNewOrderEmail();
					$this->_order->setEmailSent(true);
					$this->_order->save();
				}
			}

			return $bReply;
		}

		protected function fixUrl($sUrl, $bRemoveLanguageCode = false)
		{
			if($bRemoveLanguageCode)
			{
				$sRegex = '/\/[a-z]{2,2}\//';

				while(preg_match($sRegex, $sUrl))
				{
					$sUrl = preg_replace($sRegex, '/', $sUrl);
				}
			}

			// Remove /index.php/ from URL
			while(strpos($sUrl, '/index.php/') !== false)
			{
				$sUrl = str_replace('/index.php/', '/', $sUrl);
			}

			$sUrl = str_replace('/?___SID=U/', '/', $sUrl);
			$sUrl = substr($sUrl, 0, 10) . str_replace('//', '/', substr($sUrl, 10));

			return $sUrl;
		}
	}

?>