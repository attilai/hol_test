<?php

	/*
		Let us help you to create a suitable configuration file for your iDEAL plugin.
		Go to: http://www.ideal-checkout.nl/
	*/

	function gateway_getSettings()
	{
		$aSettings = array();

		// Merchant ID
		$aSettings['MERCHANT_ID'] = '005095600';

		// Your iDEAL Sub ID
		$aSettings['SUB_ID'] = '0';

		// Use TEST/LIVE mode; true=TEST, false=LIVE
		$aSettings['TEST_MODE'] = false;

		// Password used to generate private key file
		$aSettings['PRIVATE_KEY_PASS'] = 'goldinvest2011';

		// Name of your PRIVATE-KEY-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_KEY_FILE'] = 'hollandgold.key';

		// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /certificates/)
		$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'hollandgold.cer';

		// Path to CERTIFICATE folder (This folder should not be accessable for webusers)
		$aSettings['CERTIFICATE_PATH'] = dirname(__FILE__) . '/certificates/';

		// Path to TEMP folder (This folder should not be accessable for webusers)
		$aSettings['TEMP_PATH'] = dirname(__FILE__) . '/temp/';

		// Basic gateway settings
		$aSettings['GATEWAY_NAME'] = 'ING Bank - iDEAL Advanced';
		$aSettings['GATEWAY_WEBSITE'] = 'http://www.ingbank.nl/';
		$aSettings['GATEWAY_METHOD'] = 'ideal-professional';
		$aSettings['GATEWAY_FILE'] = dirname(__FILE__) . '/gateways/ideal-professional/gateway.cls.php';
		$aSettings['GATEWAY_VALIDATION'] = true;

		return $aSettings;
	}

?>