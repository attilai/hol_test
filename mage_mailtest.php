<?php 
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();


	$fromEmail = "info@hollandgold.nl"; // sender email address
	$fromName = "John Doe"; // sender name
	
	$toEmail = "web-ahrnWy@mail-tester.com"; // recipient email address
	$toName = "Mark Doe"; // recipient name
	
	$body = "This is Test Email!"; // body text
	$subject = "Test Subject"; // subject text
	
	$mail = new Zend_Mail();		
	
	$mail->setBodyText($body);
	
	$mail->setFrom($fromEmail, $fromName);
	
	$mail->addTo($toEmail, $toName);
	
	$mail->setSubject($subject);
	
	try {
		$mail->send();
	}
	catch(Exception $ex) {
		// I assume you have your custom module. 
		// If not, you may keep 'customer' instead of 'yourmodule'.
		Mage::getSingleton('core/session')
			->addError(Mage::helper('yourmodule')
			->__('Unable to send email.'));
	}

echo "Test email sent";
?>