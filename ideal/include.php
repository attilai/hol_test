<?php


	// Set default timezone (required in PHP 5+)
	if(function_exists('date_default_timezone_set'))
	{
		date_default_timezone_set('Europe/Amsterdam');
	}



	// Load user configuration
	require_once(dirname(__FILE__) . '/config.php');
	$aGatewaySettings = gateway_getSettings();



	// Load gateway class
	if(file_exists($aGatewaySettings['GATEWAY_FILE']) == false)
	{
		die('ERROR: Cannot load gateway file "' . $aGatewaySettings['GATEWAY_FILE'] . '".<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);
	}
	else
	{
		require_once($aGatewaySettings['GATEWAY_FILE']);
	}



	// Find database settings
	$aMagentoSettings = loadMagentoSettings(); 

	// Define database settings
	define('DATABASE_HOST', $aMagentoSettings['database_host'], true);
	define('DATABASE_USER', $aMagentoSettings['database_user'], true);
	define('DATABASE_PASS', $aMagentoSettings['database_pass'], true);
	define('DATABASE_NAME', $aMagentoSettings['database_name'], true);
	define('DATABASE_PREFIX', $aMagentoSettings['database_prefix'], true);



	// Connect to database
	mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS) or die('ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);
	mysql_select_db(DATABASE_NAME) or die('ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);


	// Order update
	function gateway_update_order_status($oRecord, $sView)
	{
		if(in_array($sView, array('doReport', 'doReturn', 'doValidate')))
		{
			$sComment = '';
			$sOrderState = false;

			if(in_array($sView, array('doReport', 'doReturn')))
			{
				$sComment = 'Status rapportage ontvangen van Payment Service Provider.';
			}
			else // if(in_array($sView, array('doValidate')))
			{
				$sComment = 'Status ontvangen na handmatige controle van openstaande transacties.';
			}
			


			// Find order state
			if($oRecord['transaction_status'] == 'SUCCESS')
			{
				$sOrderState = 'processing';
			}
			elseif($oRecord['transaction_status'] == 'PENDING')
			{
				$sOrderState = 'pending_payment';
			}
			elseif($oRecord['transaction_status'] == 'OPEN')
			{
				$sOrderState = 'pending_payment';
			}
			elseif($oRecord['transaction_status'] == 'FAILURE')
			{
				$sOrderState = 'canceled';
			}
			else
			{
				$sOrderState = 'canceled';
			}


			// Find real order id
			$sql = "SELECT `entity_id` FROM `" . DATABASE_PREFIX . "sales_flat_order` WHERE `increment_id` = '" . $oRecord['order_id'] . "' LIMIT 1;";
			$oRecordset = mysql_query($sql) or die('QUERY: ' . $sql . '<br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

			if($oOrder = mysql_fetch_assoc($oRecordset))
			{
				if($sOrderId = $oOrder['entity_id'])
				{
					if(in_array($oRecord['transaction_status'], array('SUCCESS')))
					{
						// Update order status
						$sql = "UPDATE `" . DATABASE_PREFIX . "sales_flat_order` SET `state` = '" . addslashes($sOrderState) . "', `status` = '" . addslashes($sOrderState) . "', `base_total_paid` = '" . addslashes($oRecord['transaction_amount']) . "', `total_paid` = '" . addslashes($oRecord['transaction_amount']) . "' WHERE `entity_id` = '" . addslashes($sOrderId) . "' LIMIT 1;";
						mysql_query($sql) or die('QUERY: ' . $sql . '<br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

						$sql = "UPDATE `" . DATABASE_PREFIX . "sales_flat_order_grid` SET , `status` = '" . addslashes($sOrderState) . "', `base_total_paid` = '" . addslashes($oRecord['transaction_amount']) . "', `total_paid` = '" . addslashes($oRecord['transaction_amount']) . "' WHERE `entity_id` = '" . addslashes($sOrderId) . "' LIMIT 1;";
						mysql_query($sql);
					}
					else
					{
						// Update order status
						$sql = "UPDATE `" . DATABASE_PREFIX . "sales_flat_order` SET `state` = '" . addslashes($sOrderState) . "', `status` = '" . addslashes($sOrderState) . "' WHERE `entity_id` = '" . addslashes($sOrderId) . "' LIMIT 1;";
						mysql_query($sql) or die('QUERY: ' . $sql . '<br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

						$sql = "UPDATE `" . DATABASE_PREFIX . "sales_flat_order_grid` SET `status` = '" . addslashes($sOrderState) . "' WHERE `entity_id` = '" . addslashes($sOrderId) . "' LIMIT 1;";
						mysql_query($sql);
					}

					// Update order history
					$sql = "INSERT INTO `" . DATABASE_PREFIX . "sales_flat_order_status_history` (`entity_id`, `parent_id`, `is_customer_notified`, `is_visible_on_front`, `comment`, `status`, `created_at`) VALUES (NULL, '" . addslashes($sOrderId) . "', 0, 1, '" . addslashes($sComment) . "', '" . addslashes($sOrderState) . "', '" . date('Y-m-d H:i:s') . "');";
					mysql_query($sql) or die('QUERY: ' . $sql . '<br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

					// Update stock?!
					// ..

					// Send confirmation email to client and/or webmaster?!
					// ..
				}
			}
		}
	}

	// Load magento database settings from XML file
	function loadMagentoSettings()
	{
		$sXml = file_get_contents(dirname(dirname(__FILE__)) . '/app/etc/local.xml');
		
		// Find <resources>..</resources> in XML data
		$sXml = getXmlValue($sXml, 'resources');
		
		// Find database settings in XML data
		$aMagentoSettings = array('database_host' => getXmlValue($sXml, 'host'), 'database_user' => getXmlValue($sXml, 'username'), 'database_pass' => getXmlValue($sXml, 'password'), 'database_name' => getXmlValue($sXml, 'dbname'), 'database_prefix' => getXmlValue($sXml, 'table_prefix'));
		
		return $aMagentoSettings;
	}
	
	function getXmlValue($sXml, $sTag)
	{
		if($iIndexA = (strpos($sXml, '<' . $sTag . '>') + strlen($sTag) + 2));
		{
			if($iIndexB = strpos($sXml, '</' . $sTag . '>', $iIndexA));
			{
				$sValue = trim(substr($sXml, $iIndexA, $iIndexB - $iIndexA));

				if(substr($sValue, 0, 9) == '<![CDATA[')
				{
					$sValue = substr($sValue, 9);
				}

				if(substr($sValue, -3, 3) == ']]>')
				{
					$sValue = substr($sValue, 0, -3);
				}
				
				return trim($sValue);
			}
		}

		return '';
	}

?>