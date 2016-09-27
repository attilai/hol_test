
<?php     
//Database connection settings.
/*
   $dbhost = '185.37.64.19';
$dbuser = 'hollandgold';
$dbpass = '43aXs#nm'; 
$dbname = 'hollandgold_margemod';

// Database verbinding maken
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
*/ 
include "includes/database.php";
include "includes/errors.php";

try{
    $content = file_get_contents('http://www.xmlcharts.com/live/precious-metals.php?format=json'); 
} catch(Exception $e) {
    $to = "contact@westpointdigital.nl , rratinov@gmail.com";
    $subject = "Hollandgold error report";
    $headers = "From: contact@westpointdigital.nl";
    mail($to,$subject,$e->getMessage(), $headers);
}
if ($content === false) die('Something went wrong.'); 
foreach (json_decode($content, true) as $currency => $arr) { 
	if ($currency == 'eur') {
		foreach ($arr as $commodity => $price) { 
			// echo $commodity . ': ' . round($price * 31.1034768, 2);
			// echo PHP_EOL;
			$type = "";
			if ($commodity == "gold") {
				$type = "XAU";
			}
			if ($commodity == "palladium") {
				$type = "XPD";
			}
			if ($commodity == "platinum") {
				$type = "XPT";
			}
			if ($commodity == "silver") {
				$type = "XAG";
			}
			$prijspertroyounce = $price * 31.1034768;
			if (!empty($type)) {
				$query = "INSERT INTO margemod_quotes (Type, Rate, Bid, Ask, Currency, Timestamp) VALUES (   
					'".$type."',
					'".$prijspertroyounce."',
					'".$prijspertroyounce."',
					'".$prijspertroyounce."',
					'EUR',
					'".date('Y-m-d H:i:s')."'
				);";
				
					$result = mysqli_query($link, $query);
					if (!$result){
						echo mysqli_error($link);
					}
				    
			}
		}
    } 
}
?>