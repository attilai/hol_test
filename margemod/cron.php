<?php   
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
require_once('/home/hollandgold/domains/hollandgold.nl/public_html/app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

Mage::log("Start", false, "margemod.log");

// functies includen
include ('includes/functions.php');

// Database verbinding opbouwen en prijzen ophalen. Geeft $silverRate en $goldRate terug.
include ('includes/initialize.php');
  	

$startTime = microtime(true);	
$numberOfProducts = 0;

if($silverRate > 10 && $goldRate > 500 && $platinumRate > 1 && $palladiumRate > 1)
{

	$sql = "SELECT * FROM margemod WHERE category IN ('silver', 'gold', 'palladium', 'platinum')";
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));

    $prices = array();
    $productIds = array();
    $staffel = array();
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		//Stuksprijs berekenen
		//$applicabe_rate = $row['category'] == 'silver' ? $silverRate : $goldRate;
		if($row['category'] == 'silver'){ $applicable_rate = $silverRate; }
		else if($row['category'] == 'palladium') { $applicable_rate = $palladiumRate; }
		else if($row['category'] == 'platinum') { $applicable_rate = $platinumRate; }
		else  { $applicable_rate = $goldRate; }
        $staffel[$row['products_id']] = $row['staffel'];
		array_push($productIds, $row['products_id']); 
        $prices[$row['products_id']] = roundnum(($applicable_rate + $row['margin']) * $row['commission'] * $row['profit'] * $row['btw'] * $row['qty']);
        
	}
    $collection = Mage::getModel('catalog/product')->getCollection()
        ->addAttributeToSelect(array('price', 'name'))
        ->addAttributeToFilter('entity_id', array('in' => $productIds));
        
        foreach($collection as $product) {
            print PHP_EOL ."--------------------------------" . PHP_EOL . PHP_EOL;
            print "ID: " . $product->getId() . ";" . PHP_EOL;
            print "SKU: " . $product->getSku() . ";" . PHP_EOL;
            print "Name: " . $product->getName() . ";" . PHP_EOL;
            print "Current price: " . number_format($product->getPrice(), 2, '.', '') . "€;" . PHP_EOL;
            
            if($product->getPrice() == $prices[$product->getId()]){
                print "New price: " . $prices[$product->getId()] . "€;". PHP_EOL;                    
                print "\e[1;33mPrice not changes. Skip update.\033[0m" . PHP_EOL;
                continue;
            } else {
                if(!empty($product->getTierPrice())){                  
                    print "Have tier price, tier step " . $staffel[$product->getId()]. PHP_EOL;                    
                    $step = 1;
                    $tier_prices = $product->getTierPrice();
                    $new_tier = array();
                    foreach($tier_prices as $tier_price){
                        $updated = array('cust_group'    => 32000,
                                          'price_qty' => $tier_price['price_qty'],
                                          'price' => ($prices[$product->getId()] - ($staffel[$product->getId()] * $step)) 
                        );
                        $step++;
                        array_push($new_tier, $updated);
                    }
                    $product->unsTierPrice()->save()->setTierPrice($new_tier);
                }
                $numberOfProducts++;
                print "\e[1;31mNew price:" . $prices[$product->getId()] . "€\033[0m" . ";" . PHP_EOL;
                print "Updating...". PHP_EOL;
                try {
                    $product->setPrice($prices[$product->getId()])->getResource()->save($product);
                    print "\e[1;32mUpdated.\033[0m" . PHP_EOL;
                } catch(Exception $e) {
                    print "\e[1;31m" . $e->getMessage() . "\033[0m" . PHP_EOL;
                    $to = "contact@westpointdigital.nl , rratinov@gmail.com , evgelit@gmail.com";
                    $subject = "Hollandgold error report " . date(DATE_RFC822);
                    $headers = "From: contact@westpointdigital.nl";
                    mail($to,$subject,date(DATE_RFC822) . ": " . $e->getMessage(), $headers);
                }
            }
        }
    print PHP_EOL . "--------------------------------" . PHP_EOL . PHP_EOL;
    $runTime = microtime(true) - $startTime; 
    Mage::log("Elapsed time: " . $runTime . " seconds. for " . $numberOfProducts . " products.", false, "margemod.log");
    print "Done! Elapsed time: " . $runTime . " seconds. for " . $numberOfProducts . " products." . PHP_EOL . PHP_EOL;

} else {
 print('not ok');

 print('silverrate: '. $silverRate);
 print('goldrate: ' . $goldRate);
 print('platinumrate: ' . $platinumRate);
 print('palladiumrate: ' . $palladiumRate);

}

?>