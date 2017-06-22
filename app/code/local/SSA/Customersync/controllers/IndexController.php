<?php
class SSA_Customersync_IndexController extends Mage_Core_Controller_Front_Action
{
    public function syncronizeAdminAction()
    {
       $params = Mage::app()->getRequest()->getPost('data');
       $data = unserialize($params);   //print_r($data); echo "<hr>"; exit;
       $customer_id = $data['customer_id'];
       $account_data = $data['account'];
       $address_data = $data['address'];
       $password_hash = $data['password_hash'];

       $customer_address = array();
       $customer_address_keys = array();
       if($address_data)
       {
          foreach($address_data as $key => $addr)
          {
            if($key != '_template_')
            {
               $customer_address[$key] = $address_data[$key];
               $customer_address_keys[] = $key;
            }
          }
       }
       $defaultBilling = $account_data['default_billing'];
       $defaultShipping = $account_data['default_shipping'];
       if(count($customer_address_keys) > 0)
       {
          if(strchr($defaultBilling,'_item'))
          {
             $defaultBilling = $customer_address_keys[0];
          }
          
          if(strchr($defaultShipping,'_item'))
          {
             if(count($customer_address_keys) > 1) $defaultShipping = $customer_address_keys[1];
             else $defaultShipping = $customer_address_keys[0];
          }
       }
       //echo $customer_id."<hr>"; print_r($account_data); echo "<hr>"; print_r($customer_address); echo "<hr>"; exit;
       
       $websiteId = Mage::app()->getWebsite()->getId();
       $store = Mage::app()->getStore();
       if($account_data)
       {
          //Add or update customer data
          $customer = Mage::getModel('customer/customer')->load($customer_id);
          if($customer->getId() == null)
          {
             $customer->setId($customer_id);
             $message = "Customer added";
          }
          else $message = "Customer updated";
          $customer->setWebsiteId($websiteId)
            ->setStore($store)
            ->setEmail($account_data['email'])
            ->setGroupId($account_data['group_id'])
            ->setCreatedAt($account_data['created_at'])
            ->setUpdatedAt($account_data['updated_at'])
            ->setIsActive($account_data['is_active'])
            ->setCreatedIn($account_data['created_in'])
            ->setPrefix($account_data['prefix'])
            ->setFirstname($account_data['firstname'])
            ->setMiddleName($account_data['middlename'])
            ->setLastname($account_data['lastname'])
            ->setSuffix($account_data['suffix'])
            ->setTaxvat($account_data['taxvat'])
            ->setExactonlineDebtorId($account_data['exactonline_debtor_id'])
            ->setDob($account_data['dob'])
            ->setGender($account_data['gender']);
            
          if($account_data['new_password'] != null) $customer->setPassword($account_data['new_password']);
          elseif($account_data['password'] != null) $customer->setPassword($account_data['password']);
          else $customer->setPasswordHash($password_hash);
          //print_r($customer); exit;
          try {
             $customer->save();
             echo $message." Customer ID: ".$customer->getId()."\n";
          }
          catch(Exception $e) {
             echo "Error: ".$e->getMessage()."\n";
          }
            
          //Set address
          foreach($customer_address as $key => $val)
          {
             $address = Mage::getModel("customer/address");
             if(intval($key) > 0)
             {
                $address->load($key);
                if(!$address->getId()) $address->setId($key);
             }
             else $address->setId(null);
             $address->setCustomerId($customer->getId())
             ->setPrefix($val['prefix'])
             ->setFirstname($val['firstname'])
             ->setMiddlename($val['middlename'])
             ->setLastname($val['lastname'])
             ->setCountryId($val['country_id'])
		     ->setRegionId($val['region_id'])
		     ->setRegion($val['region'])
             ->setPostcode($val['postcode'])
             ->setCity($val['city'])
             ->setTelephone($val['telephone'])
             ->setFax($val['fax]'])
             ->setCompany($val['company'])
             ->setStreet(array(0 => $val['street'][0], 1 => $val['street'][1]))
             ->setVatId($val['vat_id'])
             ->setSaveInAddressBook('1');
             if($key == $defaultBilling) $address->setIsDefaultBilling('1');
             if($key == $defaultShipping) $address->setIsDefaultShipping('1');
             try {
                $address->save();
                echo "Customer ID: ".$customer->getId()." address ".$address->getId()." saved\n";
             }
             catch (Exception $e) {
                echo "Error 2: ".$e->getMessage()."\n";
             }
          }
       }
    }
    
    public function deleteCustomerAdminAction()
    {
       Mage::register('isSecureArea', true);
       $params = Mage::app()->getRequest()->getPost('data');
       $customer_data = unserialize($params); //print_r($customer_data); echo "<hr>"; exit;
       $customer = Mage::getModel('customer/customer')->load($customer_data['customer_id']);
       if($customer->getId())
       {
          try
          {
             $customer->delete();
             echo "Customer ".$customer->getId()." deleted\n";
          }
          catch (Exception $e)
          {
             echo "Error: ".$e->getMessage()."\n";
          }
       }
       Mage::unregister('isSecureArea');
    }
    
    public function syncronizeAction()
    {
       $params = Mage::app()->getRequest()->getPost('data');
       $account_data = unserialize($params);  //print_r($account_data); echo "<hr>"; exit;
       $websiteId = Mage::app()->getWebsite()->getId();
       $store = Mage::app()->getStore();
       if($account_data)
       {
          //Add or update customer data
          $customer = Mage::getModel('customer/customer')->load($account_data['entity_id']);

          //New customer
          if(!$customer->getId())
          {
             $customer->setId($account_data['entity_id']);
             $message = "Customer added";
          }
          else $message = "Customer updated";
          $customer->setWebsiteId($websiteId)
            ->setStore($store)
            ->setEmail($account_data['email'])
            ->setUpdatedAt($account_data['updated_at'])
            ->setFirstname($account_data['firstname'])
            ->setLastname($account_data['lastname']);
          if($account_data['password'] != null) $customer->setPassword($account_data['password']);
          try {
             $customer->save();
             echo $message." Customer ID: ".$customer->getId()."\n";
          }
          catch(Exception $e) {
             echo "Error: ".$e->getMessage()."\n";
          }
       }
    }
    
    public function syncronizeAddressAction()
    {
       $params = Mage::app()->getRequest()->getPost('data');
       $address_data = unserialize($params); //print_r($address_data); echo "<hr>"; //exit;

       $customer_id = !empty($address_data['customer_id']) ? $address_data['customer_id'] : $address_data['parent_id'];
       $customer = Mage::getModel('customer/customer')->load($customer_id);
       $customer_data = $customer->getData(); //print_r($customer_data); echo "<hr>";  exit;
       $defaultBilling = $customer_data['default_billing'];
       $defaultShipping = $customer_data['default_shipping'];
       
       $is_default_billing = 0;
       if($address_data['is_default_billing'] == 1 || $address_data['entity_id'] == $defaultBilling)  $is_default_billing = 1;
       
       $is_default_shipping = 0;
       if($address_data['is_default_shipping'] == 1 || $address_data['entity_id'] == $defaultShipping)  $is_default_shipping = 1;
       
       if($is_default_billing == 0 && $is_default_shipping == 0)
       {
           $is_default_billing = 1;
           $is_default_shipping = 1;
       }

       $address = Mage::getModel('customer/address')->load($address_data['entity_id']);
       $address->setParentId($customer_id)
             ->setFirstname($address_data['firstname'])
             ->setLastname($address_data['lastname'])
             ->setCompany($address_data['company'])
             ->setStreet($address_data['street'])
             ->setCity($address_data['city'])
             ->setCountryId($address_data['country_id'])
		     ->setRegion($address_data['region'])
             ->setRegionId($address_data['region_id'])
             ->setPostcode($address_data['postcode'])
             ->setTelephone($address_data['telephone'])
             ->setFax($address_data['fax]'])
             ->setIsDefaultBilling($is_default_billing)
             ->setIsDefaultShipping($is_default_shipping); //print_r($address); echo "<hr>"; exit;
       try
       {
          $address->save();
          echo "Customer ID: ".$address->getCustomerId()." address ".$address->getId()." saved\n";
       }
       catch (Exception $e)
       {
          echo "Error: ".$e->getMessage()."\n";
       }
    }
    
    public function deleteAddressAction()
    {
       $params = Mage::app()->getRequest()->getPost('data');
       $address_data = unserialize($params); //print_r($address_data); echo "<hr>"; exit;
       $address = Mage::getModel('customer/address')->load($address_data['address_id']);
       if($address->getId())
       {
          try
          {
             $address->delete();
             echo "Address ".$address->getId()." deleted\n";
          }
          catch (Exception $e)
          {
             echo "Error: ".$e->getMessage()."\n";
          }
       }
    }
}
