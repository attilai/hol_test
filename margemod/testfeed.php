
<?php 
/*
Voor ip adres en domeinnaam
$domain = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : null; 
$output = file_get_contents('http://www.xmlcharts.com/ip-domain/'.$domain); 
if (is_string($output)) die($output); 
*/
?>




<?php 

header('Content-Type: text/plain'); 
$content = file_get_contents('http://www.xmlcharts.com/live/precious-metals.php?format=json'); 
if ($content === false) die('Something went wrong.'); 
foreach (json_decode($content, true) as $currency => $arr) { 
	echo $currency . ': ' . var_dump($arr);
	echo PHP_EOL;
	if ($currency == 'eur') {
		foreach ($arr as $commodity => $price) { 
			// echo $commodity . ': ' . round($price * 31.1034768, 2);
			// echo PHP_EOL;
		}
    }
}
echo PHP_EOL;
echo PHP_EOL;
/*
function get_price($type){
    // define the SOAP client using the url for the service
    $client = new soapclient('http://www.xignite.com/xMetals.asmx?WSDL', array('trace' => 1));

    // create an array of parameters 
    $param = array(
        'Types' => $type,
        'Currency' => 'EUR');

    // add authentication info
    $xignite_header = new SoapHeader('http://www.xignite.com/services/', "Header", array("Username" => "jraijmans@gmail.com", "Password" => "1alleskomtgoud"));
    $client->__setSoapHeaders(array($xignite_header));				   

    // call the service, passing the parameters and the name of the operation 
    $result = $client->GetRealTimeMetalQuotes($param);

    // assess the results 
    if (is_soap_fault($result)) {
        return false;
    } else {        
        return $result->GetRealTimeMetalQuotesResult;
    }
}

$rateList = get_price('XAU,XAG,XPT,XPD');
print_r($rateList);
*/
?>