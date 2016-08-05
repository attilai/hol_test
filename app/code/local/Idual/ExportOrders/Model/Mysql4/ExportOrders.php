    <?php
     
    class Idual_ExportOrders_Model_Mysql4_exportorders extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {   
            $this->_init('ExportOrders/ExportOrders', 'orderexport_id');
        }
    }