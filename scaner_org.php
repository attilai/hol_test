<?php 

    require_once('app/Mage.php'); //Path to Magento
    umask(0);
    Mage::app();

    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );

    $from = "info@hollandgold.nl";
    $to = "web-nDKS04@mail-tester.com";
    $subject = 'TestSubject';
    $sender = 'Some Sender';
    $recipient = 'Some Recipient';
    $message = 'Hi Maikel!';

    $host = 'hosting08.exonet.nl';
    $config = array(
        'auth' => 'login',
        'username' => 'info@hollandgold.nl',
        'password' => 'EUcuSDdh0fIfTyAULZur',
        'port' => '587'
    );
     
    $transport = new Zend_Mail_Transport_Smtp($host, $config);
     
    $mail = new Zend_Mail();
    $mail->setBodyText($message);
    $mail->setFrom($from, $sender);
    $mail->addTo($to, $recipient);
    $mail->setSubject($subject);
    $mail->send($transport);
?>
 