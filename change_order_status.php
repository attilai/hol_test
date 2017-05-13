<?php
//Status Updated start here
//https://www.hollandgold.nl/change_order_status.php?order_id=100017366&pass=statusapply
if (isset($_GET["order_id"]) && $_GET['pass'] == "statusapply") {
    require_once 'app/Mage.php';

    umask(0);
    Mage::app('default');
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
    /*
        //Magento Status List
        const STATE_NEW             = 'new';
        const STATE_PENDING_PAYMENT = 'pending_payment';
        const STATE_PROCESSING      = 'processing';
        const STATE_COMPLETE        = 'complete';
        const STATE_CLOSED          = 'closed';
        const STATE_CANCELED        = 'canceled';
        const STATE_HOLDED          = 'holded';
        const STATE_PAYMENT_REVIEW  = 'payment_review';
     */

    $order = Mage::getModel('sales/order')->loadByIncrementID($_GET['order_id']);
    if ($order->getId()) {
        $order->setManuallyRun(true);
        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
        $order->save();
    } else {
        echo "Please check Order ID";
    }
} else {
    echo "Please check Order ID and Password";
}