<?php

	/*
		This plug-in was developed by iDEAL Checkout.
		See www.ideal-checkout.nl for more information.

		This file was generated on 18-08-2015, 10:55:14
	*/


	// Merchant ID
	$aSettings['MERCHANT_ID'] = '005095600';

	// Your iDEAL Sub ID
	$aSettings['SUB_ID'] = '0';

	// Use TEST/LIVE mode; true=TEST, false=LIVE
	$aSettings['TEST_MODE'] = false;

	// Password used to generate private key file
	$aSettings['PRIVATE_KEY_PASS'] = 'yi1DLdnQofOB';

	// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)
	$aSettings['PRIVATE_KEY_FILE'] = 'hollandgold.key';

	// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)
	$aSettings['PRIVATE_CERTIFICATE_FILE'] = 'hollandgold.cer';


	// Basic gateway settings
	$aSettings['GATEWAY_NAME'] = 'ING Bank - iDEAL Advanced';
	$aSettings['GATEWAY_WEBSITE'] = 'http://www.ingbank.nl/';
	$aSettings['GATEWAY_METHOD'] = 'ideal-professional-v3';
	$aSettings['GATEWAY_VALIDATION'] = true;


	// E-mailadresses for transaction updates (comma seperated)
	$aSettings['TRANSACTION_UPDATE_EMAILS'] = 'sales@hollandgold.nl';

?>