<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);*/


//https://www.hollandgold.nl/api/?wsdl
try {
    $client = new SoapClient('https://www.hollandgold.nl/api/?wsdl');
    $session = $client->login('margemod', '8675394');
}

catch(Exception $e) {
    $to = "support@elmaonline.nl , rratinov@gmail.com";
    $subject = "Hollandgold error report";
    $headers = "From: support@elmaonline.nl";
    mail($to,$subject,$e->getMessage(), $headers);
}

?>