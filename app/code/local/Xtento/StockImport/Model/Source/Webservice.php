<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2014-03-12T23:03:09+01:00
 * File:          app/code/local/Xtento/StockImport/Model/Source/Webservice.php.sample
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Model_Source_Webservice extends Xtento_StockImport_Model_Source_Abstract
{
    /*
     * !!!!! IMPORTANT !!!!!
     *
     * Modify below this line. Add custom functions, similar to the function below. Must return parameter $filesToProcess as in example below.
     */
    public function importStockAndLevertijd()
    {
		/*
		$url='https://www.bullionoffice.com/webservice/general_request.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$input_xml = '<request>
			<requesttype>GetStock</requesttype>
			<requesttime>'.$datenow.'</requesttime>
			<requestid>http_download4hollandgold</requestid>
		</request>';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
		curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlRequest=" . $input_xml);
		
		$data = curl_exec($ch); // execute curl request
		curl_close($ch);
		
		$xml = simplexml_load_string($data);
		
		$filesToProcess[] = array('source_id' => 'http_download4hollandgold', 'path' => '/var/www/tmp/import/', 'filename' => 'http_download', 'data' => $data); // Set a filename here. 'data' must contain the returned string from the HTTP source which will then be imported

        return $filesToProcess;
		*/
		
		
        $filesToProcess = array();
        // Do whatever - sample code for a HTTP request below.
        $curlClient = curl_init();
		
		// EDDY for BulionOffices
		// Make datestring like 20130308101024 YearMonthDayHourMinuteSecond
		$datenow = date("YmdHis");
		$input_xml = '<request>
            <requesttype>GetStock</requesttype>
            <requesttime>'.$datenow.'</requesttime>
            <requestid>http_download4hollandgold</requestid>
		</request>';
        // curl_setopt($curlClient, CURLOPT_URL, 'https://www.bullionoffice.com/webservice/general_request.php');
		curl_setopt($curlClient, CURLOPT_URL, 'https://www.bulliongroup.nl/webservice/general_request.php');
        curl_setopt($curlClient, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlClient, CURLOPT_POSTFIELDS, "xmlRequest=" . $input_xml);
        curl_setopt($curlClient, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlClient, CURLOPT_SSL_VERIFYHOST, 0);
		
        $fileContents = curl_exec($curlClient);
        curl_close($curlClient);
		//$fileContents = new SimpleXMLElement($fileContents);
		
        // EDDY $filesToProcess[] = array('source_id' => $this->getSource()->getId(), 'path' => '', 'filename' => 'http_download', 'data' => $fileContents); 
		// Set a filename here. 'data' must contain the returned string from the HTTP source which will then be imported
        // $filesToProcess[] = array('source_id' => $this->getSource()->getId(), 'path' => '', 'filename' => 'http_download4hollandgold', 'data' => $fileContents[0]['data']); 
		// Set a filename here. 'data' must contain the returned string from the HTTP source which will then be imported

        // Return files to process
        //return $fileContents;
		
       //  $filesToProcess[] = array(array('data' => $fileContents)); // Set a filename here. 'data' must contain the returned string from the HTTP source which will then be imported
        $filesToProcess[] = array('source_id' => $this->getSource()->getId(), 'path' => '/var/www/vhosts/hollandgold.nl/var/stockimport/', 'filename' => 'stock4hollandgold.xml', 'data' => $fileContents); 

        // Return files to process
        return $filesToProcess;
		
		$logEntry = Mage::registry('stock_import_log');
		$logEntry->setResult(Xtento_StockImport_Model_Log::RESULT_WARNING);
		$logEntry->addResultMessage(Mage::helper('xtento_stockimport')->__('Source "%s" (ID: %s): %s', $this->getSource()->getName(), $this->getSource()->getId(), 'Something went wrong'));

    }

    /*
     * !!!!! Do not modify below this line !!!!!
     */
    public function testConnection()
    {
        $this->initConnection();
        return $this->getTestResult();
    }

    public function initConnection()
    {
        $this->setSource(Mage::getModel('xtento_stockimport/source')->load($this->getSource()->getId()));
        $testResult = new Varien_Object();
        $this->setTestResult($testResult);
        if (!@method_exists($this, $this->getSource()->getCustomFunction())) {
            $this->getTestResult()->setSuccess(false)->setMessage(Mage::helper('xtento_stockimport')->__('Custom function/method \'%s\' not found in %s.', $this->getSource()->getCustomFunction(), __FILE__));
        } else {
            $this->getTestResult()->setSuccess(true)->setMessage(Mage::helper('xtento_stockimport')->__('Custom function/method found and ready to use.', __FILE__));
        }
        $this->getSource()->setLastResult($this->getTestResult()->getSuccess())->setLastResultMessage($this->getTestResult()->getMessage())->save();
        return true;
    }

    public function loadFiles()
    {
        // Init connection
        $this->initConnection();
        // Call custom function
        $filesToProcess = @$this->{$this->getSource()->getCustomFunction()}();
        return $filesToProcess;
    }

    public function archiveFiles($filesToProcess, $forceDelete = false)
    {

    }
}