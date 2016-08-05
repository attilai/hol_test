<?php   
// functies includen
include ('includes/functions.php');

// connect to Magento
include('includes/connect.php');

// Database verbinding opbouwen en prijzen ophalen. Geeft $silverRate en $goldRate terug.
include ('includes/initialize.php');
  	

$startTime = microtime(true);	
$numberOfProducts = 0;
	
if($silverRate > 10 && $goldRate > 500 && $platinumRate > 1 && $palladiumRate > 1)
{
	

	print('ok');

	$sql = "SELECT * FROM margemod WHERE category IN ('silver', 'gold', 'palladium', 'platinum')";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		
		
		$pStartTime = microtime(true);	
		
		$call_array = array();
		
		//Stuksprijs berekenen
		//$applicabe_rate = $row['category'] == 'silver' ? $silverRate : $goldRate;
		if($row['category'] == 'silver'){ $applicable_rate = $silverRate; }
		else if($row['category'] == 'palladium') { $applicable_rate = $palladiumRate; }
		else if($row['category'] == 'platinum') { $applicable_rate = $platinumRate; }
		else  { $applicable_rate = $goldRate; }
        
		$item_price = roundnum(($applicable_rate + $row['margin']) * $row['commission'] * $row['profit'] * $row['btw'] * $row['qty']);
		
		//Magento product updaten
		$call_array[] = array('product.update', array($row['products_id'], array('price'=>$item_price)));
		
		// Tier prices updaten
		$tier_prices = $client->call($session, 'product_tier_price.info', $row['products_id']);
		uasort($tier_prices, 'sort_by_qty');
		
		$current_price = $item_price;
		foreach($tier_prices as &$tier_price){
			$current_price = $current_price - $row['staffel'];
			$tier_price['price'] = $current_price;
		}
		unset($tier_price);
		
		$call_array[] = array('product_tier_price.update', array($row['products_id'], $tier_prices));
		
		echo $row['products_name'].": &euro;".$item_price."<br />\n";
		
		$client->multiCall($session, $call_array);
		
		$pRunTime = microtime(true) - $pStartTime; 
		print "[Elapsed time :: ".$pRunTime." seconds.]<br />\n";
		$numberOfProducts++;

	}
} else {
 print('not ok');

 print('silverrate: '. $silverRate);
 print('goldrate: ' . $goldRate);
 print('platinumrate: ' . $platinumRate);
 print('palladiumrate: ' . $palladiumRate);

}
	
// connect to Magento
include('includes/disconnect.php');
//mail("tjallin@newtalentgroup.com", "test crontab", "iets met body");	


$runTime = microtime(true) - $startTime; 
print "[Elapsed time :: ".round($runTime)." seconds. for ". $numberOfProducts . " products]<br />\n";

?>
