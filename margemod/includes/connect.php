<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);*/

$client = new SoapClient('https://www.hollandgold.nl/api?wsdl');
$session = $client->login('margemod', '8675394');
//https://www.hollandgold.nl/api/?wsdl
?>