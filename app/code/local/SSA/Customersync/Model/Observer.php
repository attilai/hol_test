<?php
class SSA_Customersync_Model_Observer
{
   public function syncCustomerAdmin($observer)
   {
      $customer = $observer->getCustomer();
      $customer_data = $customer->getData();
      $data = Mage::app()->getRequest()->getPost();
      $addresses = $customer->getAddresses();
      $address_array = array();
      foreach($addresses as $_address)
      {
         $address_data = $_address->getData();
         $address_array[$_address['entity_id']] = array(
            'prefix' => $_address['prefix'],
            'firstname' => $_address['firstname'],
            'middlename' => $_address['middlename'],
            'lastname' => $_address['lastname'],
            'suffix' => $_address['suffix'],
            'company' => $_address['company'],
            'street' => array(
                       '0' => $_address['street'],
                       '1' => ''
            ),
            'city' => $_address['city'],
            'country_id' => $_address['country_id'],
            'region' => $_address['region'],
            'region_id' => $_address['region_id'],
            'postcode' => $_address['postcode'],
            'telephone' => $_address['telephone'],
            'fax' => $_address['fax'],
            'vat_id' => $_address['vat_id']
         );
      }
      $data['address'] = $address_array;
      $data['password_hash'] = $customer_data['password_hash']; //print_r($data); exit;
      $api = Mage::getModel('customersync/api');
      $api->updateCustomerAdmin($data);
   }
}
