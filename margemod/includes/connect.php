<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);*/


//https://www.hollandgold.nl/api/?wsdl
try {
    $client = new SoapClient('https://www.hollandgold.nl/api/soap/?wsdl');
    $session = $client->login('margemod', '8675394');
    print $session . PHP_EOL;
}

catch(Exception $e) {
    $to = "contact@westpointdigital.nl , rratinov@gmail.com , evgelit@gmail.com";
    $subject = "Hollandgold error report" . date(DATE_RFC822);
    $headers = "From: contact@westpointdigital.nl";
    mail($to,$subject,date(DATE_RFC822) . ": " . $e->getMessage(), $headers);
}

?> 