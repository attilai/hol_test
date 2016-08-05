<?php
 
class Idual_ExportOrders_Model_Observer
{
	function __construct(){

	}
	
    public function orderStatusChange($event)
    {
        $order = $event->getOrder();
        $orderStatus = $order->getStatus();
		$orderId = $order->getData('entity_id');
		
		
		/*
		 * The Following order statusses are valid:
		 * 1. canceled (I suppose a typo)
		 * 2. pending
		 * 3. processing
		*/
		$valid_statuses = array('canceled', 'pending', 'processing');
		
		if( !in_array($orderStatus, $valid_statuses) ) {
			return $this;
		}
		
		$model = Mage::getModel('ExportOrders/ExportOrders');
		$data['order_type'] = 'update';
		if( $model->IsNew($orderId) ) {
			$data['order_type'] = 'new';
		}
		
		$now = Mage::getModel('core/date')->timestamp(time());
		$now = date('Y-m-d H:i:s', $now);
			
		/* INSERT */
		$data['order_id'] = $orderId;
		$data['order_status'] = $orderStatus;
		$data['export_status'] = 'open';
		$data['created_time'] = $now;
		
		$model_order = Mage::getModel('ExportOrders/ExportOrders')->setData($data);
		try {
        	$insertId = $model_order->save()->getId();
        	$msg = "Data successfully inserted. Insert ID: ".$insertId;
    	} catch (Exception $e){
    		//echo  $e->getMessage(); exit;
     		$msg = $e->getMessage();
		}
		
		Mage::log($msg);
		
		return $this;
    }
}