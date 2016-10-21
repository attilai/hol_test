<?php

	require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
	require_once(__DIR__ . '/paycheckoutapi/src/PayCheckout/autoloader.php');
	
	class Gateway extends GatewayCore
	{
		// Load iDEAL settings
		public function __construct()
		{
			$this->init();
		}

		
		public function doSetup()
		{
			// Look for proper GET's en POST's
			if(empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid transaction request.</p>';
			}
			else
			{
				$sHtml = '';
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];

				// Lookup transaction
				if($this->getRecordByOrder())
				{
					if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
					{
						$sHtml .= '<p>Transaction already completed</p>';
					}
					else
					{
						$sReturnUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/return.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code'];
						$sLanguageCode = (empty($this->oRecord['language_code']) ? 'nl' : $this->oRecord['language_code']);
					
						
						$oMessage = PayCheckout\Api\Payment\Generic::create(
\PayCheckout\PaymentMethod::SOFORTBANKING,
$sOrderId,
$this->oRecord['transaction_description'],
intval(round($this->oRecord['transaction_amount'] * 100)),
PayCheckout\Currency::EUR,
$_SERVER['REMOTE_ADDR'],
null,
null,
null);

						
						
						// Validate order_params
						$bProductDataError = false;
						$aProductData = array();
						
						$bCustomerDataError = false;
						$aCustomerData = array();
						
						if(!empty($this->oRecord['order_params']))
						{
							$aOrderParams = idealcheckout_unserialize($this->oRecord['order_params']);

							if(isset($aOrderParams['customer']) && isset($aOrderParams['customer']['shipment_first_name']) && isset($aOrderParams['customer']['shipment_last_name']) && isset($aOrderParams['customer']['shipment_street_name']) && isset($aOrderParams['customer']['shipment_street_number']) && isset($aOrderParams['customer']['shipment_zipcode']) && isset($aOrderParams['customer']['shipment_city']) && isset($aOrderParams['customer']['shipment_email']) && isset($aOrderParams['customer']['shipment_country_code']))
							{
								$aCustomerData['countryIso3166Alpha2'] = substr($aOrderParams['customer']['shipment_country_code'], 0, 2);
								$aCustomerData['firstName'] = substr($aOrderParams['customer']['shipment_first_name'], 0, 45);
								$aCustomerData['lastName'] = substr($aOrderParams['customer']['shipment_last_name'], 0, 45);
								$aCustomerData['addressLine1'] = substr($aOrderParams['customer']['shipment_street_name'] . ' ' . $aOrderParams['customer']['shipment_street_number'], 0, 45);
								$aCustomerData['zipCode'] = substr($aOrderParams['customer']['shipment_zipcode'], 0, 8);
								$aCustomerData['city'] = substr($aOrderParams['customer']['shipment_city'], 0, 45);
								$aCustomerData['emailAddress'] = substr($aOrderParams['customer']['shipment_email'], 0, 150);
								$aCustomerData['emailAddress'] = substr($aOrderParams['customer']['shipment_email'], 0, 150);
							}
							else
							{
								idealcheckout_log('Customer data is incomplete.', __FILE__, __LINE__);
								$bCustomerDataError = true;
							}
							
							if(isset($aOrderParams['products']) && is_array($aOrderParams['products']) && sizeof($aOrderParams['products']))
							{
								$i = 0;
								
								foreach($aOrderParams['products'] as $k => $v)
								{
									
									if(isset($v['code']) && isset($v['description']) && isset($v['quantity']) && isset($v['price_incl']) && isset($v['price_excl']))
									{
										$aProduct = array();
										$aProduct['orderLineNumber'] = $i;
										
										$aProduct['name'] = substr(preg_replace('/([^a-zA-Z0-9_\- ]+)/', '', $v['description']), 0, 150);
										
										$aProduct['quantityCancelled'] = 0;
										$aProduct['quantityInvoiced'] = 0;
										$aProduct['quantityRefunded'] = 0;
										$aProduct['unitPriceExclusiveVat'] = intval(round($v['price_excl'] * 100));
										$aProduct['unitPriceInclusiveVat'] = intval(round($v['price_incl'] * 100));
										$aProduct['vatPercentageDisplay'] = intval(round($v['vat'] * 100));
										$aProduct['quantityOrdered'] = intval(intval($v['quantity']));
										
// Add order item info
$oMessage->payment_addOrderItem(
$aProduct['orderLineNumber'],
$aProduct['name'],
$aProduct['name'],
$aProduct['quantityOrdered'],
$aProduct['quantityCancelled'],
$aProduct['quantityInvoiced'],
$aProduct['quantityRefunded'],
$aProduct['unitPriceExclusiveVat'],
$aProduct['unitPriceInclusiveVat'],
$aProduct['vatPercentageDisplay'],
PayCheckout\ItemType::ARTICLE,
null,
null);
									
									
									
									
										$aProductData[] = $aProduct;
										
									}
									else
									{
										idealcheckout_log('Product data is incomplete.', __FILE__, __LINE__);
										idealcheckout_log($v, __FILE__, __LINE__);
										$bProductDataError = true;
									}
										
									$i++;
								}
							}
							else
							{
								idealcheckout_log('Product data is incomplete.', __FILE__, __LINE__);
								$bProductDataError = true;
							}
						
							if($bProductDataError)
							{
								idealcheckout_log('This application doesn\'t seem to support Paycheckout.', __FILE__, __LINE__);
								idealcheckout_die('This application doesn\'t seem to support Paycheckout.', __FILE__, __LINE__);
							}						
						}

	


						// Set CustomerOrderNumber and/or CustomerNote
						$oMessage->Payment_setOrderInfo($this->oRecord['transaction_description'], '');

						// Set order billing address info
						$oMessage->payment_setOrderBillingAddress(
$aCustomerData['countryIso3166Alpha2'], 
$aCustomerData['firstName'],
$aCustomerData['lastName'],
$aCustomerData['addressLine1'],
$aCustomerData['zipCode'],
$aCustomerData['city'],
null,
null,
null,
null,
$aCustomerData['emailAddress'],
null,
null,
null,
null,
null,
null,
null,
null,
null);

						// Set order shipping address info
						$oMessage->payment_setOrderShippingAddress(
$aCustomerData['countryIso3166Alpha2'], 
$aCustomerData['firstName'],
$aCustomerData['lastName'],
$aCustomerData['addressLine1'],
$aCustomerData['zipCode'],
$aCustomerData['city'],
null,
null,
null,
null,
null,
null,
null);


						// Set return url override
						$oMessage->payment_overrideConfiguredReturnUrls(
$sReturnUrl,
$sReturnUrl,
$sReturnUrl);
  					


						// Create an executor instance
						$oApiCall = new PayCheckout\ApiExecutor(intval(trim($this->aSettings['WEBSHOP_ID'])), trim($this->aSettings['ENCRYPTION_PASSWORD']), $this->aSettings['TEST_MODE'], 'ideal-checkout');

						// Execute the call and receive result
						$aResponse = $oApiCall->execute($oMessage);
						
						
						// Analyze result
						if($aResponse->getApiResult() != PayCheckout\ApiResult::FAILED)
						{
							 $sTransactionReference = $aResponse->getTransactionReference();
							 $sRedirectUrl = $aResponse->getRedirectInfo();
							 
							 
							 if(!empty($sTransactionReference) && !empty($sRedirectUrl))
							 {
							 
								// save transaction reference
								$this->oRecord['transaction_code'] = $sTransactionReference;
								$this->save();

							
								header('Location: ' . $sRedirectUrl);
								exit;
							
							 }
						}
						else
						{
					
							idealcheckout_log('Payment could not be created, error recieved', __FILE__, __LINE__);
							$sHtml .= '<p>Invalid transaction request, ' . $aResponse->getErrorToShowToConsumer() . '</p>';
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid transaction request.</p>';
				}
			}

			idealcheckout_output($sHtml);
		}


		// Catch return
		public function doReturn()
		{
			$sHtml = '';

			if(empty($_GET['order_id']) || empty($_GET['order_code']) || empty($_GET['PayCheckoutReference']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];
				$sTransactionReference = $_GET['PayCheckoutReference'];

				// Lookup record
				if($this->getRecordByOrder())
				{	
					if(!strcasecmp($sTransactionReference, $this->oRecord['transaction_code']) === 0)
					{
						$sHtml .= '<p>Invalid return request.</p>';
					}
					else
					{	
							// Prepare GetPaymentInfo
						$oMessage = PayCheckout\Api\Service\GetLastNotificationContent::create($this->oRecord['transaction_code']);

						// Create an executor instance
						$oApiCall = new PayCheckout\ApiExecutor(intval(trim($this->aSettings['WEBSHOP_ID'])), trim($this->aSettings['ENCRYPTION_PASSWORD']), $this->aSettings['TEST_MODE']);

						// Execute the call and receive result
						$aResponse = $oApiCall->execute($oMessage);
				
						
						// Analyze result
						if($aResponse->getApiResult() != PayCheckout\ApiResult::FAILED)
						{
							// Success or Success with warning, we can now retrieve the PaymentInfo
							$sJsonString = PayCheckout\Api\Service\GetLastNotificationContent::response($aResponse);

							// Decode the POST content				 						    
							$aJsonData = json_decode($sJsonString, false, 512, JSON_BIGINT_AS_STRING);
								
							
							// Decode into ApiSigned message
							$oSignedCall = new PayCheckout\Json\ApiMessageSigned();
							$oSignedCall->setJson($aJsonData);

							// Verify message integrety
							if($oSignedCall->verify(trim($this->aSettings['ENCRYPTION_PASSWORD'])))
							{
								$oNotification = $oSignedCall->getApiMessage()->getNotification();								 
								 
								if(strcasecmp($oNotification->getNotificationType(), 0) === 0)
								{
									$iPaymentstatus = $oNotification->getPaymentStatusChange()->getStatus();
									
									if(strcasecmp($iPaymentstatus, 10) === 0)
									{
										$sTransactionStatus = 'PENDING';	
									}
									elseif(strcasecmp($iPaymentstatus, 30) === 0)
									{
										$sTransactionStatus = 'SUCCESS';	
									}
									elseif(strcasecmp($iPaymentstatus, 40) === 0)
									{
										$sTransactionStatus = 'CANCELLED';
										
									}
									elseif(strcasecmp($iPaymentstatus, 50) === 0)
									{
										$sTransactionStatus = 'FAILURE';
										
									}
									
									$this->oRecord['transaction_status'] = $sTransactionStatus;

									if(empty($this->oRecord['transaction_log']) == false)
									{
										$this->oRecord['transaction_log'] .= "\n\n";
									}

									$this->oRecord['transaction_log'] .= 'Executing StatusRequest on ' . date('Y-m-d, H:i:s') . ' for #' . $this->oRecord['transaction_id'] . '. Recieved: ' . $this->oRecord['transaction_status'];

									$this->save();

									// Handle status change
									if(function_exists('idealcheckout_update_order_status'))
									{
										idealcheckout_update_order_status($this->oRecord, 'doReturn');
									}
									
									
									// Set status message
									if(strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
									{
										if(!empty($this->oRecord['transaction_success_url']))
										{
											header('Location: ' . $this->oRecord['transaction_success_url']);
											exit;
										}

										$sHtml .= '<p>Uw betaling is met succes ontvangen.<br><input style="margin: 6px;" type="button" value="Terug naar de website" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1)) . '\'"></p>';
									}
									elseif(strcmp($this->oRecord['transaction_status'], 'OPEN') === 0)
									{
										if(!empty($this->oRecord['transaction_pending_url']))
										{
											header('Location: ' . $this->oRecord['transaction_pending_url']);
											exit;
										}

										$sHtml .= '<p>Uw betaling is in behandeling.<br><input style="margin: 6px;" type="button" value="Terug naar de website" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1)) . '\'"></p>';
									}
									elseif(strcasecmp($this->oRecord['transaction_status'], 'CANCELLED') === 0)
									{
										if(!empty($this->oRecord['transaction_failure_url']))
										{
											header('Location: ' . $this->oRecord['transaction_failure_url']);
											exit;
										}

										$sHtml .= '<p>Uw betaling is geannuleerd. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
									}
									else // if(strcasecmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
									{
										if(!empty($this->oRecord['transaction_failure_url']))
										{
											header('Location: ' . $this->oRecord['transaction_failure_url']);
											exit;
										}

										$sHtml .= '<p>Uw betaling is mislukt. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
									}
								}
								elseif(strcasecmp($oNotification->getNotificationType(), 20) === 0)
								{
									
										// Do somehting smart with the modified order
										$aOrder = $oNotification->getOrderChange()->getOrder();	
								}
							}									
						}
						else // No status found, redirect to failure
						{
							if(!empty($this->oRecord['transaction_failure_url']))
							{
								header('Location: ' . $this->oRecord['transaction_failure_url']);
								exit;
							}

							$sHtml .= '<p>Uw betaling is geannuleerd. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
						}
					}						
				}
				else
				{
					$sHtml .= '<p>Invalid return request.</p>';
				}
			}

			idealcheckout_output($sHtml);
		}
	
		// Catch report
		public function doReport()
		{			
			$sEncryptionPassword = trim($this->aSettings['ENCRYPTION_PASSWORD']);
			
			$sPostData = file_get_contents('php://input');			 

			$aData = json_decode($sPostData, false, 512, JSON_BIGINT_AS_STRING);
						
			$oMessage = new PayCheckout\Json\ApiMessageSigned();
			$oMessage->setJson($aData);

			if($oMessage->verify($sEncryptionPassword))
			{
				$oNotification = $oMessage->getApiMessage()->getNotification();
				
				idealcheckout_log($oNotification, __FILE__, __LINE__);	
		
				// Find unique ID's for record query
				$sTransactionCode = $oNotification->getPaymentStatusChange()->getPaymentReference();
				$sOrderId = $oNotification->getPaymentStatusChange()->getMerchantOrderReference();
				
				$aDatabaseSettings = idealcheckout_getDatabaseSettings();
							
				$sql = "SELECT * FROM `" . $aDatabaseSettings['table'] . "` WHERE (`order_id` = '" . idealcheckout_escapeSql($sOrderId) . "') AND (`transaction_code` = '" . idealcheckout_escapeSql($sTransactionCode) . "') ORDER BY `id` DESC LIMIT 1";
				$this->oRecord = idealcheckout_database_getRecord($sql);
				
				if(strcasecmp($oNotification->getNotificationType(), 0) === 0)
				{						
					$iPaymentstatus = $oNotification->getPaymentStatusChange()->getStatus();
									
					if(strcasecmp($iPaymentstatus, 10) === 0)
					{
						$sTransactionStatus = 'PENDING';	
					}
					elseif(strcasecmp($iPaymentstatus, 30) === 0)
					{
						$sTransactionStatus = 'SUCCESS';	
					}
					elseif(strcasecmp($iPaymentstatus, 40) === 0)
					{
						$sTransactionStatus = 'CANCELLED';
					}
					elseif(strcasecmp($iPaymentstatus, 50) === 0)
					{
						$sTransactionStatus = 'FAILURE';
					}
					
					$this->oRecord['transaction_status'] = $sTransactionStatus;

					if(empty($this->oRecord['transaction_log']) == false)
					{
						$this->oRecord['transaction_log'] .= "\n\n";
					}

					$this->oRecord['transaction_log'] .= 'Executing StatusRequest on ' . date('Y-m-d, H:i:s') . ' for #' . $this->oRecord['transaction_id'] . '. Recieved: ' . $this->oRecord['transaction_status'];

					$this->save();

					// Handle status change
					if(function_exists('idealcheckout_update_order_status'))
					{
						idealcheckout_update_order_status($this->oRecord, 'doReport');
					}					
				}
			
			
				$sHtml = '<p>De transactie status is bijgewerkt.</p>';
				// Send response 200 Ok if all is ok
			}
			else
			{				
				idealcheckout_log('Verification failed', __FILE__, __LINE__);
				
				// Verification failed
				$sHtml = '<p>' . idealcheckout_getTranslation(false, 'idealcheckout', 'Invalid return request.') . '</p>' . ($this->aSettings['TEST_MODE'] ? '<!-- Verification failed -->' : '');
			}
			
			idealcheckout_output($sHtml);
		}
		
	}
	
?>