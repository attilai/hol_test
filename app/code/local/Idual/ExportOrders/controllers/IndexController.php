<?php 

//ini_set('display_errors', '1');

class Idual_ExportOrders_IndexController extends Mage_Core_Controller_Front_Action{
    	
	/**
	 * Process orders from order log table. This function will be triggered by the cronjob 
	 *
	 * @return void
	 */ 
    public function indexAction(){
        
		//$order = Mage::getModel('sales/order')->load(5204); echo  $order->getIncrementId(); exit;
		//echo Mage::getConfig()->getModuleConfig("Idual_ExportOrders")->version;
		
		$model = Mage::getModel('ExportOrders/ExportOrders');
		
		
		//$xml = $this->_GetXMLNew(5706);
		//header('Content-Type: application/xml'); echo $xml; exit;
		
		// EDDY
		//Mage::log('IndexController line 24');
		// $xml = $this->_GetXMLNew('11217');
		// tot hier
		
		$collection = $model->GetListOpenOrders();
		foreach($collection as $order){
			$insertId = $order['orderexport_id'];
			$orderId = $order['order_id'];
			
			/* Check if it is a new order */
			$orderType = 'update';
			if( $model->IsNew($orderId) ) {
				$xml = $this->_GetXMLNew($orderId);
			} else {
				$xml = $this->_GetXMLUpdate($orderId);
			}

			$result = $this->_doXMLRequest($xml);

			$bo_orderid = (int)$result->boresponse->bo_orderid;
			$now = Mage::getModel('core/date')->timestamp(time());
			$now = date('Y-m-d H:i:s', $now);
			
			
			$data['bo_orderid'] = $bo_orderid;
			$data['update_time'] = $now;
			$data['export_status'] = 'completed';
			$data['export_result'] = json_encode($result);
			
			try {
				$model_order = $model->load($insertId)->addData($data);
				$model_order->setId($insertId)->save();
	        	$msg = "Data updated successfully.";
			} catch (Exception $e){
	     		$msg = $e->getMessage();
			}
		}
		
		// EDDY
		//Mage::log('IndexController line 62');
		// tot hier
		
       // Mage::dispatchEvent('sales_order_save_after');
        
    }
	
	/**
	 * Generate XML string for BO interface. 
	 *
	 * @param  order_id   
	 * @return output XML string
	 */ 
	private function _GetXMLUpdate($order_id) {
		// EDDY
		//Mage::log('IndexController line 77');
		// tot hier
		$order = Mage::getModel('sales/order')->load($order_id);
		$order_number = $order->getIncrementId();
		$request_time = $this->_GetRequestTime();
		$request_id = $this->_GetRequestId();
		
		$model = Mage::getModel('ExportOrders/ExportOrders');
		$bo_orderid = $model->GetBoOrderid($order_id);
		
		$_totalData = $order->getData();
		$order_status = $_totalData['status'];
		
		$domtree = new DOMDocument('1.0', 'UTF-8');
		/* create the root element of the xml tree */
	    $xmlRoot = $domtree->createElement("request");
	    /* append it to the document created */
	    $xmlRoot = $domtree->appendChild($xmlRoot);
		
		$xmlRoot->appendChild($domtree->createElement('requesttype','StatusOrder'));
		$xmlRoot->appendChild($domtree->createElement('requesttime',$request_time));
		$xmlRoot->appendChild($domtree->createElement('requestid',$request_id));
		$xmlRoot->appendChild($domtree->createElement('m_ordernummer',$order_number));
		$xmlRoot->appendChild($domtree->createElement('bo_orderid',$bo_orderid));
		$xmlRoot->appendChild($domtree->createElement('status',$order_status));
		
		// EDDY <- ебанько
		//Mage::log('IndexController line 104');
		// tot hier
		$output = $domtree->saveXML();
		return $output;
	}
	
	/**
	 * Generate XML string for BO interface. 
	 *
	 * @param  order_id   
	 * @return output XML string
	 */ 
	private function _GetXMLNew($order_id) {
        if($order_id == "15020") {
            return;
        }
		$order = Mage::getModel('sales/order')->load($order_id);
		// EDDY $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
		// EDDY $order = Mage::getModel('sales/order')->loadByIncrementId('100011678');
		// EDDY $order = Mage::getModel('sales/order')->load('11218');
		$_totalData = $order->getData();
		// EDDY
		//Mage::log('order_id line 122 = '.$order_id);
		// tot hier
		
		$request_time = $this->_GetRequestTime();
		$request_id = $this->_GetRequestId();
		$order_number = $order->getIncrementId();
		
		$store_id = $order->getStore()->getData('store_id');
		$store_code = '';
		switch($store_id) {
			case 1: $store_code = 'HGNL-NL'; break;
			case 2: $store_code = 'HGBE-FR'; break;
			case 3: $store_code = 'HGBE-NL'; break;
		}
		
		$order_data = date("d-m-Y", strtotime($_totalData['created_at']));
		$order_total = $_totalData['grand_total'];
		$shipping_cost = $order->getShippingAmount();
		$order_status = $_totalData['status'];
		
		/* Shipping methods 
		 * 
		 * Method 1: Verzekerde bezorging : msmultiflat
		 * Method 2: Privé Koerier
		 * Method 3: verzending buitenland
		 * Method 4: Gratis afhalen op kantoor : freeshipping
		 * 
		 * */
		 /*
		 echo '<pre>';
		 // print_r($order->getShippingCarrier());
		 print_r(strtolower($order->getShippingDescription()));
		 echo '</pre>';
		 echo strtolower($order->getShippingDescription());
		 die();
		 */
		 // EDDY $shipping_method_code = $order->getAllCarriers()->getCode();
		 // EDDY $shipping_method_code = $order->getCarriers()->getCode();
		 // $shipping_method_code = $order->getShippingCarrier()->getCarrierCode(); < deze was het
		 //echo $shipping_method_code; exit;
		 // EDDY $shipping_method_code = "";
		 
		if (strpos(strtolower($order->getShippingDescription()),'koerier') !== false) {
			$shipping_method = 'bezorgen';
		} elseif(strpos(strtolower($order->getShippingDescription()),'opslag') !== false){
			$shipping_method = 'bezorgen';
		} elseif(strpos(strtolower($order->getShippingDescription()),'afhalen') !== false) {
			$shipping_method = 'afhalen';
		} else {
			$shipping_method = 'verzenden';
		}
		/* 
		Werkt niet meer sinds andere theme ??????
		 if( $shipping_method_code == 'flatrate' ) {
		 	$shipping_method = 'bezorgen';
		 } elseif( $shipping_method_code == 'freeshipping' || $shipping_method_code == 'flatrate2' ) {
		 	$shipping_method = 'afhalen';
		 } else {
		 	$shipping_method = 'verzenden';
		 }
		 */
		 
		 $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
		 /*
		 echo '<pre>';
		 print_r($order->getPayment()->getMethodInstance()->getCode());
		 echo '</pre>';
		 die();
		 */
		 if( $payment_method_code == 'bankoverschrijving' OR $payment_method_code == 'checkmo') {
		 	$payment_method = 'OVERSCHRIJVING';
		 } elseif( $payment_method_code == 'idealcheckoutideal' or $payment_method_code == 'ingkassacompleet_ideal') {
		 	$payment_method = 'IDEAL';
         } elseif( $payment_method_code == 'ingpsp_bancontact') {
		 	$payment_method = 'BANCONTACT';
         } else {
		 	$payment_method = 'BALIE';
		 }
		 
		 Mage::log(date("Y-m-d H:i:s")."=>".$order->getIncrementId()."=>".$payment_method_code."=>".$payment_method, null, 'idual_orders.log');
		
		// EDDY
		//Mage::log('IndexController line 174');
		// tot hier
		
		
		//$shipping_method = $shipping_method; //$order->getShippingDescription();//$_totalData['shipping_method']; $order->getShippingCarrier()->getCarrierCode();
		//$payment_method = $order->getPayment()->getMethodInstance()->getTitle();
		
		/* Customer ID is not always available */
		$customer_id = $order->getCustomerId();
		
		/* Billing Address */
		$billing_Prefix = $order->getBillingAddress()->getPrefix(); 
		$billing_FirstName =  $order->getBillingAddress()->getFirstname(); 
		$billing_MiddleName = $order->getBillingAddress()->getMiddlename();
		$billing_LastName =  $order->getBillingAddress()->getLastname();
		$billing_Suffix =  $order->getBillingAddress()->getSuffix();
		$billing_Company =  $order->getBillingAddress()->getCompany();
		
		$address = $order->getBillingAddress()->getStreet(); 
		$billing_Adrress1 = array_shift($address);
		$billing_Adrress2 = array_shift($address); 
		$billing_City = $order->getBillingAddress()->getCity();
		$billing_CountryId = $order->getBillingAddress()->getCountryId();
		$billing_Region = $order->getBillingAddress()->getRegion();
		$billing_Postcode = $order->getBillingAddress()->getPostcode(); 
		$billing_Tel = $order->getTelephone();
		$billing_Fax = $order->getFax();
		// $billing_Email = $order->getBillingAddress()->getEmail(); 
		$billing_Email = $order->getCustomerEmail(); 
		$billing_Gender = $order->getBillingAddress()->getCustomerGender();
		
		/* Shipping address */
		$shipping_Prefix = $order->getShippingAddress()->getPrefix(); 
		$shipping_FirstName =  $order->getShippingAddress()->getFirstname(); 
		$shipping_MiddleName = $order->getShippingAddress()->getMiddlename();
		$shipping_LastName =  $order->getShippingAddress()->getLastname();
		$shipping_Suffix =  $order->getShippingAddress()->getSuffix();
		$shipping_Company =  $order->getShippingAddress()->getCompany();
		$address = $order->getShippingAddress()->getStreet(); 
		$shipping_Adrress1 = array_shift($address);
		$shipping_Adrress2 = array_shift($address); 
		$shipping_City = $order->getShippingAddress()->getCity();
		$shipping_CountryId = $order->getShippingAddress()->getCountryId();
		$shipping_Region = $order->getShippingAddress()->getRegion();
		$shipping_Postcode = $order->getShippingAddress()->getPostcode(); 
		$shipping_Tel = $order->getTelephone();
		$shipping_Fax = $order->getFax();
		// $shipping_Email = $order->getShippingAddress()->getEmail(); 
		$shipping_Email = $order->getCustomerEmail(); 
		$shipping_Gender = $order->getShippingAddress()->getCustomerGender();
		
		$domtree = new DOMDocument('1.0', 'UTF-8');
		/* create the root element of the xml tree */
	    $xmlRoot = $domtree->createElement("request");
	    /* append it to the document created */
	    $xmlRoot = $domtree->appendChild($xmlRoot);
		
		$xmlRoot->appendChild($domtree->createElement('requesttype','NewOrder'));
		$xmlRoot->appendChild($domtree->createElement('requesttime',$request_time));
		$xmlRoot->appendChild($domtree->createElement('requestid',$request_id));
		
	    $order_element = $domtree->createElement("order");
	    $order_element = $xmlRoot->appendChild($order_element);	
	
	    $order_element->appendChild($domtree->createElement('m_klantid',$customer_id));
	    $order_element->appendChild($domtree->createElement('m_ordernummer',$order_number));
	    $order_element->appendChild($domtree->createElement('webshop',$store_code));
	    $order_element->appendChild($domtree->createElement('besteldatum',$order_data));
		$order_element->appendChild($domtree->createElement('betalingswijze',$payment_method));
	    $order_element->appendChild($domtree->createElement('status',$order_status));
	    $order_element->appendChild($domtree->createElement('orderbedrag', number_format($order_total, 2, ',', '')));
	    $order_element->appendChild($domtree->createElement('verzendwijze',$shipping_method));
		
		$order_element->appendChild($domtree->createElement('verzendkosten',number_format($shipping_cost, 2, ',', '')));
		if (!empty($shipping_Company)) {
			$order_element->appendChild($domtree->createElement('bedrijfsnaam',$shipping_Company));
		}
		$order_element->appendChild($domtree->createElement('achternaam',$shipping_LastName));
		$order_element->appendChild($domtree->createElement('voorvoegsel',$shipping_MiddleName));
		$order_element->appendChild($domtree->createElement('voorletters',$shipping_FirstName));
		$order_element->appendChild($domtree->createElement('geslacht',$shipping_Gender));
		$order_element->appendChild($domtree->createElement('straatnaam',$shipping_Adrress1));

		if($shipping_Adrress2 == ''){
			trim($shipping_Adrress1);
			$arayAddress = explode(" ", $shipping_Adrress1);
			$shipping_Adrress2 = array_pop($arayAddress);			
		}

		$order_element->appendChild($domtree->createElement('huisnummer',$shipping_Adrress2));
		$order_element->appendChild($domtree->createElement('huisnummertoev',''));
		$order_element->appendChild($domtree->createElement('postcode',$shipping_Postcode));
		$order_element->appendChild($domtree->createElement('plaats',$shipping_City));
		$order_element->appendChild($domtree->createElement('country',$shipping_CountryId)); // notering NL, BE, DE
		$order_element->appendChild($domtree->createElement('email',$shipping_Email));
		
		$orderlines_element = $domtree->createElement("orderregels");
	    $orderlines_element = $xmlRoot->appendChild($orderlines_element);	
		
		foreach($order->getItemsCollection() as $prod)
    	{
    		$orderline_element = $domtree->createElement("regel");
			
			$price_sold = $prod->getPrice();
			
        	$_product = Mage::getModel('catalog/product')->load($prod->getProductId());
        	$sku = $_product->getSku();
        	$order_quantity = (int)$prod->getQtyOrdered();
			$unit_price = $_product->getPrice();
			
			$taxclassid = $_product->getData("tax_class_id");
			$store = $order->getStore('default');
			$request = Mage::getSingleton('tax/calculation')->getRateRequest(null, null, null, $store);
			$percent = Mage::getSingleton('tax/calculation')->getRate($request->setProductClassId($taxclassid));
			
			$orderline_element->appendChild($domtree->createElement('SKU',$sku));
			$orderline_element->appendChild($domtree->createElement('aantal',$order_quantity));
			$orderline_element->appendChild($domtree->createElement('bedrag',number_format($price_sold, 2, ',', '')));
			$orderline_element->appendChild($domtree->createElement('btwcode',$percent));
			
			$orderlines_element->appendChild($orderline_element);	
    	}
		
		$output = $domtree->saveXML();
		return $output;
	}

	private function _doXMLRequest($xml_data) {
		// $url = 'https://www.bullionoffice.com/webservice/general_request.php';	
		$url = 'https://www.bulliongroup.nl/webservice/general_request.php';	
		$postfields = array(
    		'xml' => $xml_data // get it with file_get_contents() for example
		);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => $xml_data));
		
	
		$tuData = curl_exec($ch);
		if(!curl_errno($ch)){
		  $info = curl_getinfo($ch);
		  //echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
		  Mage::log('Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url']);
		} else {
		  // echo 'Curl error: ' . curl_error($ch);
		  Mage::log('Curl error: ' . curl_error($ch));
		}

		curl_close($ch);
		
	
		$tuData =  substr($tuData, strpos($tuData, '<?xml'));
		$tuData = simplexml_load_string($tuData);
		//header('Content-Type: application/xml'); echo $tuData; exit;
		
		return $tuData;
	} 
	
	private function _GetRequestId() {
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 50);
	}
	
	private function _GetRequestTime() {
		return date("YmdHis", Mage::getModel('core/date')->timestamp(time() ));
	}
	
	/**
	 * DO NOT USE THIS FUNCTION. This function is only used for development
	 *	 
	 * @return Void	Print all stores
	 */ 
	private function _GetStores() {
		$allStores = Mage::app()->getStores();
		foreach ($allStores as $_eachStoreId => $val)
		{
			$_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
			$_storeName = Mage::app()->getStore($_eachStoreId)->getName();
			$_storeId = Mage::app()->getStore($_eachStoreId)->getId();
			echo $_storeId."<br />";
			echo $_storeCode."<br />";
			echo $_storeName."<br />";
			echo "<hr>";
		}
		exit;
	}
}
