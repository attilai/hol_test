<?php

class Wyomind_Googleshopping_Model_Mysql4_Googleshopping extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the googleshopping_id refers to the key field in your database table.
        $this->_init('googleshopping/googleshopping', 'googleshopping_id');
    }
}