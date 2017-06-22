<?php
class SSA_Customersync_Model_Api extends Mage_Core_Model_Abstract
{
   public function updateCustomerAdmin($data)
   {
       $url = Mage::getStoreConfig('customersync_config/general/remote_url_admin');
       $params = array('data' => serialize($data));
       $response = $this->_sendRequest($url,$params); //print_r($response);  exit;
       Mage::log($response, null, 'customer.log');
   }
   
   public function updateCustomerData($customer)
   {
       $data = $customer->getData(); //print_r($data); exit;
       $url = Mage::getStoreConfig('customersync_config/general/remote_url');
       $params = array('data' => serialize($data));
       $response = $this->_sendRequest($url,$params); //print_r($response);  exit;
       Mage::log($response, null, 'customer.log');
   }

   public function updateCustomerAddress($address)
   {
       $data = $address->getData(); //print_r($data); exit;
       $url = Mage::getStoreConfig('customersync_config/general/remote_url_address');
       $params = array('data' => serialize($data));
       $response = $this->_sendRequest($url,$params); //print_r($response);  exit;
       Mage::log($response, null, 'customer.log');
   }
   
   public function deleteCustomer($customerId)
   {
       $data = array('customer_id' => $customerId); //print_r($data); exit;
       $url = Mage::getStoreConfig('customersync_config/general/remote_url_admin_delete');
       $params = array('data' => serialize($data));
       $response = $this->_sendRequest($url,$params); //print_r($response);  exit;
       Mage::log($response, null, 'customer.log');
   }
   
   public function deleteCustomerAddress($address_id)
   {
       $data = array('address_id' => $address_id); //print_r($data); exit;
       $url = Mage::getStoreConfig('customersync_config/general/remote_url_address_delete');
       $params = array('data' => serialize($data));
       $response = $this->_sendRequest($url,$params); //print_r($response);  exit;
       Mage::log($response, null, 'customer.log');
   }
   
   protected function _sendRequest($url,$params)
   {
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       $response = curl_exec($ch);
       return $response;
   }

}
