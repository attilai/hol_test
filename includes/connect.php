<?php
$client = new SoapClient('http://www.hollandgold.nl/api/?wsdl');
$session = $client->login('margemod', '8675394');
?>
