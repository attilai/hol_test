<?php
 
class Idual_ExportOrders_Model_Mysql4_exportorders_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('ExportOrders/ExportOrders');
    }
}