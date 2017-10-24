<?php   
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
define('PROJECT_DIRECTORY', dirname(dirname(__FILE__)));
define('MAGE_APP_DIRECTORY', PROJECT_DIRECTORY . '/app/');
define('MARGEMOD_DIRECTORY', PROJECT_DIRECTORY . '/margemod/');
require_once(MAGE_APP_DIRECTORY . 'Mage.php'); //Path to Magento
umask(0);
Mage::app();
/**
 * Temporary logging of this job
 * It should be removed if all ok.
 */
$startJobTime = time();
$startJobDate = date('Ymd_His', $startJobTime);
$pathToLogDir = MARGEMOD_DIRECTORY . '/tmp_logs/';
$logFileName = 'margemod_cronjob_' . $startJobDate . '.log';
$pathToLogFile = $pathToLogDir . $logFileName;
if ($logFile = fopen($pathToLogFile, 'a')) {
    fwrite($logFile, '---------START CRON JOB----------------------------------------------' . PHP_EOL . PHP_EOL . PHP_EOL);
    fclose($logFile);
}

// functies includen
include (MARGEMOD_DIRECTORY . 'includes/functions.php');
// Database verbinding opbouwen en prijzen ophalen. Geeft $silverRate en $goldRate terug.
include (MARGEMOD_DIRECTORY . 'includes/initialize.php');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$startTime = microtime(true);	
$website = Mage::app()->getWebsite();
$rules = Mage::getModel('catalogrule/rule')->getCollection()
    ->addWebsiteFilter($website) //filter rules for current site
    ->addIsActiveFilter(1); //filter active rules

$affectedProductIds = array();

foreach ($rules as $rule) {
    foreach($rule->getMatchingProductIds() as $single_id) {
        array_push($affectedProductIds, $single_id);
    }
}

/**
 * @var $connection Varien_Db_Adapter_Pdo_Mysql
 */
$resource = Mage::getSingleton('core/resource');
$connection = $resource->getConnection('core_read');
$tableName = $resource->getTableName('catalogrule/rule_product');
$catalogruleGroupWebsiteTableName = $resource->getTableName('catalogrule/rule_group_website');

$numberOfProducts = 0;

if($silverRate > 10 && $goldRate > 500 && $platinumRate > 1 && $palladiumRate > 1) {
	print('ok');
    if ($logFile = fopen($pathToLogFile, 'a')) {
        fwrite($logFile, '---------------------ok-------------------------------' . PHP_EOL . PHP_EOL . PHP_EOL);
        fclose($logFile);
    }

	$sql = "SELECT * FROM margemod WHERE category IN ('silver', 'gold', 'palladium', 'platinum')";
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));

    $prices = array();
    $productIds = array();
    $staffel = array();
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		//Stuksprijs berekenen
		if ($row['category'] == 'silver') {
		    $applicable_rate = $silverRate;
		} elseif ($row['category'] == 'palladium') {
		    $applicable_rate = $palladiumRate;
		} elseif ($row['category'] == 'platinum') {
		    $applicable_rate = $platinumRate;
		} else {
		    $applicable_rate = $goldRate;
		}

        $staffel[$row['products_id']] = $row['staffel'];
		array_push($productIds, $row['products_id']);
        $prices[$row['products_id']] = roundnum(($applicable_rate + $row['margin']) * $row['commission'] * $row['profit'] * $row['btw'] * $row['qty']);
	}
    $collection = Mage::getModel('catalog/product')
        ->getCollection()
        ->addAttributeToSelect('*')
        ->addAttributeToFilter('entity_id', array('in' => $productIds));
        
    foreach($collection as $product) {
        /**
         * @var $product Mage_Catalog_Model_Product
         */
        print PHP_EOL ."--------------------------------" . PHP_EOL . PHP_EOL;
        print "ID: " . $product->getId() . ";" . PHP_EOL;
        print "SKU: " . $product->getSku() . ";" . PHP_EOL;
        print "Name: " . $product->getName() . ";" . PHP_EOL;
        print "Current price: €" . number_format($product->getPrice(), 2, '.', '') . ";" . PHP_EOL;
        if ($logFile = fopen($pathToLogFile, 'a')) {
            fwrite($logFile, PHP_EOL ."--------------------------------" . PHP_EOL . PHP_EOL);
            fwrite($logFile, "ID: " . $product->getId() . ";" . PHP_EOL);
            fwrite($logFile, "SKU: " . $product->getSku() . ";" . PHP_EOL);
            fwrite($logFile, "Name: " . $product->getName() . ";" . PHP_EOL);
            fwrite($logFile, "Current price: €" . number_format($product->getPrice(), 2, '.', '') . ";" . PHP_EOL);
            fclose($logFile);
        }
        $discountQuery = '
            SELECT `t_cp`.`action_amount` AS `amount`, `t_cgw`.`customer_group_id` AS `customer_group_id`, `t_cgw`.`website_id` AS `website_id`
            FROM ' . $tableName . ' AS `t_cp`
                JOIN ' . $catalogruleGroupWebsiteTableName . ' AS `t_cgw` ON `t_cgw`.`rule_id` = `t_cp`.`rule_id`
            WHERE product_id = '. $product->getId()
        ;
        $discountResult = $connection->fetchAll($discountQuery);
        if($product->getPrice() == $prices[$product->getId()] && !in_array($product->getId(), $affectedProductIds)){
            print "New price: €" . $prices[$product->getId()] . ";". PHP_EOL;
            print "\e[1;33mPrice not changes. Skip update.\033[0m" . PHP_EOL;
            if ($logFile = fopen($pathToLogFile, 'a')) {
                fwrite($logFile, "New price: €" . $prices[$product->getId()] . ";". PHP_EOL);
                fwrite($logFile, "Price not changes. Skip update." . PHP_EOL);
                fclose($logFile);
            }
            continue;
        } else {
            if (!empty($product->getTierPrice())) {
                print "Have tier price, " . (!empty($staffel[$product->getId()]) ? "tier step " . $staffel[$product->getId()] : 'but staffel not exists or empty') . PHP_EOL;
                if ($logFile = fopen($pathToLogFile, 'a')) {
                    fwrite($logFile, "Have tier price, " . (!empty($staffel[$product->getId()]) ? "tier step " . $staffel[$product->getId()] : 'but staffel not exists or empty') . PHP_EOL);
                    fclose($logFile);
                }
                $step = 1;
                $tierPrices = $product->getTierPrice();
                $productData = array(
                    'tier_price'    => array()
                );
                foreach ($tierPrices as $tierPrice) {
                    $_data = array(
                        'cust_group'    => $tierPrice['cust_group'],
                        'price_qty'     => $tierPrice['price_qty'],
                        'price'         => number_format(($prices[$product->getId()] - ($staffel[$product->getId()] * $step)), 4, '.', ''),
                        'deleted'       => ''
                    );
                    array_push($productData['tier_price'], $_data);
                    $step++;
                }
                if (count($discountResult) > 0) {
                    foreach ($discountResult as $discountRow) {
                        $step = 1;
                        foreach ($tierPrices as $tierPrice) {
                            $_data = array(
                                'cust_group'    => $discountRow['customer_group_id'],
                                'price_qty'     => $tierPrice['price_qty'],
                                'price'         => number_format(($prices[$product->getId()] - ($staffel[$product->getId()] * $step) - $discountRow['amount']), 4, '.', ''),
                                'deleted'       => ''
                            );
                            array_push($productData['tier_price'], $_data);
                            $step++;
                        }
                    }
                }
                $product->unsetData('tier_price')->addData($productData);
            }
            $numberOfProducts++;
            print "\e[1;31mNew price: €" . $prices[$product->getId()] . "\033[0m" . ";" . PHP_EOL;
            print "Updating...". PHP_EOL;
            if ($logFile = fopen($pathToLogFile, 'a')) {
                fwrite($logFile, "New price: €" . $prices[$product->getId()] . ";" . PHP_EOL);
                fwrite($logFile, "Updating...". PHP_EOL);
                fclose($logFile);
            }
            try {
                $product->setPrice($prices[$product->getId()])->save();
                print "\e[1;32mUpdated.\033[0m" . PHP_EOL;
                if ($logFile = fopen($pathToLogFile, 'a')) {
                    fwrite($logFile, "Updated." . PHP_EOL);
                    fclose($logFile);
                }
            } catch(Exception $e) {
                print "\e[1;31m" . $e->getMessage() . "\033[0m" . PHP_EOL;
                if ($logFile = fopen($pathToLogFile, 'a')) {
                    fwrite($logFile, $e->getMessage() . PHP_EOL);
                    fclose($logFile);
                }
                $to = "contact@westpointdigital.nl , rratinov@gmail.com , evgelit@gmail.com";
                $subject = "Hollandgold error report " . date(DATE_RFC822);
                $headers = "From: contact@westpointdigital.nl";
                mail($to,$subject,date(DATE_RFC822) . ": " . $e->getMessage(), $headers);
            }
        }
    }
    print PHP_EOL . "--------------------------------" . PHP_EOL . PHP_EOL;
    if ($logFile = fopen($pathToLogFile, 'a')) {
        fwrite($logFile, PHP_EOL . "--------------------------------" . PHP_EOL . PHP_EOL);
        fclose($logFile);
    }

    if ($numberOfProducts > 0) {
        try {
            Mage::getModel('catalogrule/rule')->applyAll();
            Mage::app()->removeCache('catalog_rules_dirty');
            print ' done!';
            print PHP_EOL;
            print "Reindexing price.........";
            if ($logFile = fopen($pathToLogFile, 'a')) {
                fwrite($logFile, PHP_EOL . "--------------------------------" . PHP_EOL . PHP_EOL);
                fwrite($logFile, ' done!' . PHP_EOL);
                fwrite($logFile, "Reindexing price.........");
                fclose($logFile);
            }
            Mage::getModel('index/indexer')->getProcessByCode('catalog_product_price')->reindexAll();
            print "Applying catalog price rules.........";
            if ($logFile = fopen($pathToLogFile, 'a')) {
                fwrite($logFile, "Applying catalog price rules.........");
                fclose($logFile);
            }
        } catch (Exception $exception) {
            print 'fail';
            print $exception->getMessage() . PHP_EOL;
            if ($logFile = fopen($pathToLogFile, 'a')) {
                fwrite($logFile, 'fail' . PHP_EOL);
                fwrite($logFile, $exception->getMessage() . PHP_EOL);
                fclose($logFile);
            }
        }
        print PHP_EOL . PHP_EOL;
        if ($logFile = fopen($pathToLogFile, 'a')) {
            fwrite($logFile, PHP_EOL . PHP_EOL);
            fclose($logFile);
        }
    }
    $runTime = microtime(true) - $startTime;
    Mage::log("Elapsed time: " . $runTime . " seconds. for " . $numberOfProducts . " products.", false, "margemod.log");
    print "Done! Elapsed time: " . $runTime . " seconds. for " . $numberOfProducts . " products." . PHP_EOL . PHP_EOL;
    if ($logFile = fopen($pathToLogFile, 'a')) {
        fwrite($logFile, "Done! Elapsed time: " . $runTime . " seconds. for " . $numberOfProducts . " products." . PHP_EOL . PHP_EOL);
        fclose($logFile);
    }
} else {
    print('not ok');

    print('silverrate: '. $silverRate);
    print('goldrate: ' . $goldRate);
    print('platinumrate: ' . $platinumRate);
    print('palladiumrate: ' . $palladiumRate);
    if ($logFile = fopen($pathToLogFile, 'a')) {
        fwrite($logFile, 'not ok' . PHP_EOL . PHP_EOL);
        fwrite($logFile, 'silverrate: '. $silverRate . PHP_EOL);
        fwrite($logFile, 'goldrate: ' . $goldRate . PHP_EOL);
        fwrite($logFile, 'platinumrate: ' . $platinumRate . PHP_EOL);
        fwrite($logFile, 'palladiumrate: ' . $palladiumRate . PHP_EOL);
        fclose($logFile);
    }
}
if ($logFile = fopen($pathToLogFile, 'a')) {
    fwrite($logFile, '---------END CRON RUN-------------------------------------' . PHP_EOL);
    fclose($logFile);
}
$to = "andrewbess1982@gmail.com";
$subject = "Hollandgold margemod report";
$from = "contact@westpointdigital.nl";
include_once (MARGEMOD_DIRECTORY . '/includes/PHPMailer.php');
$email = new Zend_Mail();
$email->setFrom($from, 'Hollandgold');
$email->setSubject($subject);
$email->setBodyText('Hollandgold margemod report from ' . date(DATE_RFC822, $startJobTime) . ' with attachments');
$email->addTo('andrewbess1982@gmail.com');
$logContent = file_get_contents($pathToLogFile);
$attachment = new Zend_Mime_Part($logContent);
$attachment->filename($logFileName);
$email->addAttachment($attachment);
$email->send();
//mail($to, $subject,"Hollandgold margemod report from " . date(DATE_RFC822, $startJobTime) . ' with attachments', $headers);

?>