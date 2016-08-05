<?php 

class Idual_ExportOrders_Model_exportorders extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ExportOrders/ExportOrders');
    }
	
	public function IsNew($orderId) {
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		
		$query = 'SELECT * FROM ' . $resource->getTableName('ExportOrders/ExportOrders') . ' where order_id='.$orderId .' AND export_status="completed"';
		$results = $readConnection->fetchAll($query);
		
		if( count($results) > 0 ) {
			return false;
		} 
		return true;
	}
	
	public function GetBoOrderid($order_id) {
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		
		$query = 'SELECT * FROM ' . $resource->getTableName('ExportOrders/ExportOrders') . ' where order_id='.$order_id .' AND export_status="completed"';
		$results = $readConnection->fetchAll($query);
		
		if( count($results) > 0 ) {
			$response = json_decode($results[0]['export_result']); 
			if( isset($response->boresponse->bo_orderid)) {
				return $response->boresponse->bo_orderid;
			}; 
		} 
		return false;
	}
	
	public function GetListOpenOrders() {
		$maxOrders = 100;
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		
		/* SELECT an order*/
		$query = 'SELECT * FROM ' . $resource->getTableName('ExportOrders/ExportOrders') . ' where export_status="open" ORDER BY orderexport_id ASC LIMIT '.$maxOrders;
		$collection = $readConnection->fetchAll($query);
		
		//print_r($collection); exit;
		
		return $collection;
	}
}