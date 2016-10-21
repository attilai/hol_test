<?php



	if(is_file(dirname(__FILE__) . '/debug.php'))
	{
		include_once(dirname(__FILE__) . '/debug.php');
	}
	
	if(is_file(dirname(__FILE__) . '/library.php'))
	{
		include_once(dirname(__FILE__) . '/library.php');
	}

	// Create a random code with N digits.
	function ic_getRandomCode($iLength = 64)
	{
		return idealcheckout_getRandomCode($iLength);
	}

	// Retrieve ROOT url of script
	function ic_getRootUrl($iParent = 0)
	{
		return idealcheckout_getRootUrl($iParent);
	}

	// Retrieve ROOT url of script
	function ic_getRootPath()
	{
		return idealcheckout_getRootPath();
	}

	// Escape SQL values
	function ic_escapeSql($sString, $bEscapeLike = false)
	{
		return idealcheckout_escapeSql($sString, $bEscapeLike);
	}

	// Serialize data
	function ic_serialize($mData)
	{
		return idealcheckout_serialize($mData);
	}

	// Unserialize data
	function ic_unserialize($sString)
	{
		return idealcheckout_unserialize($sString);
	}

	// Print html to screen
	function ic_output($sHtml, $bImage = true)
	{
		return idealcheckout_output($sHtml, $bImage);
	}
	
	// Load data from an URL
	function ic_doHttpRequest($sUrl, $sPostData = false, $bRemoveHeaders = false, $iTimeout = 30, $bDebug = false, $aAdditionalHeaders = false)
	{
		return idealcheckout_doHttpRequest($sUrl, $sPostData, $bRemoveHeaders, $iTimeout, $bDebug, $aAdditionalHeaders);
	}
	
	// Curl verifcation error has occured
	function ic_getCurlVerificationError()
	{
		return idealcheckout_getCurlVerificationError();
	}
	
	// Escape quoted strings
	function ic_escapeQuotes($sString, $bEscapeDoubleQuotes = false)
	{
		return idealcheckout_escapeQuotes($sString, $bEscapeDoubleQuotes);
	}
	
	// Translate text using language files
	function ic_getTranslation($sLanguageCode = false, $sGroup, $sKey, $aParams = array())
	{
		return idealcheckout_getTranslation($sLanguageCode, $sGroup, $sKey, $aParams);
	}

	// Load database settings
	function ic_getDatabaseSettings($sStoreCode = false)
	{
		return idealcheckout_getDatabaseSettings($sStoreCode);
	}

	// Load database settings
	function ic_getWebsiteSettings($sStoreCode = false)
	{
		return idealcheckout_getWebsiteSettings($sStoreCode);
	}

	// Load gateway settings
	function ic_getGatewaySettings($sStoreCode = false, $sGatewayCode = false)
	{
		return idealcheckout_getGatewaySettings($sStoreCode, $sGatewayCode);
	}

	// Load database settings
	function ic_die($sError, $sFile = false, $iLine = false, $sGatewayCode = 'ideal')
	{
		return idealcheckout_die($sError, $sFile, $iLine, $sGatewayCode);
	}

	// Load database settings
	function ic_log($sText, $sFile = false, $iLine = false, $bDebugCheck = true)
	{
		return idealcheckout_log($sText, $sFile, $iLine, $bDebugCheck);
	}

	// Load database settings
	function ic_getDebugMode()
	{
		return idealcheckout_getDebugMode();
	}
	
	// Load database settings
	function ic_sendMail()
	{
		return idealcheckout_sendMail();
	}
	
	// Load database settings
	function ic_getStoreCode()
	{
		return idealcheckout_getStoreCode();
	}
	
	// Load database settings
	function ic_splitAddress($sAddress)
	{
		return idealcheckout_splitAddress($sAddress);
	}

	// Load database settings
	function ic_database_query($sQuery, $oDatabaseConnection = false)
	{
		return idealcheckout_database_query($sQuery, $oDatabaseConnection);
	}
	
	// Load database settings
	function ic_database_setup($oDatabaseConnection = false)
	{
		return idealcheckout_database_setup($oDatabaseConnection);
	}

	// Load database settings
	function ic_database_getRecord($sQuery, $oDatabaseConnection = false)
	{
		return idealcheckout_database_getRecord($sQuery, $oDatabaseConnection);
	}

	// Load database settings
	function ic_database_isRecord($sQuery, $oDatabaseConnection = false)
	{
		return idealcheckout_database_isRecord($sQuery, $oDatabaseConnection);
	}

	// Load database settings
	function ic_database_getRecords($sQuery, $oDatabaseConnection = false)
	{
		return idealcheckout_database_getRecords($sQuery, $oDatabaseConnection);
	}

	// Load database settings
	function ic_database_execute($sQuery, $oDatabaseConnection = false)
	{
		return idealcheckout_database_execute($sQuery, $oDatabaseConnection);
	}

	// Load database settings
	function ic_database_fetch_assoc($oRecordSet)
	{
		return idealcheckout_database_fetch_assoc($oRecordSet);
	}
	
	// Load database settings
	function ic_database_error($oDatabaseConnection = false)
	{
		return idealcheckout_database_error($oDatabaseConnection);
	}

	// Load database settings
	function ic_database_insert_id($oDatabaseConnection = false)
	{
		return idealcheckout_database_insert_id($oDatabaseConnection);
	}
	
	// Load database settings
	function ic_database_select_db($oDatabaseConnection = false, $sDatabaseName = false)
	{
		return idealcheckout_database_select_db($oDatabaseConnection, $sDatabaseName);
	}
	
	// Load database settings
	function ic_json_decode($sString)
	{
		return idealcheckout_json_decode($sString);
	}
	
	// Load database settings
	function ic_json_encode($aData)
	{
		return idealcheckout_json_encode($aData);
	}
	
	
?>