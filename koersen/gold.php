<?php      
// phpinfo();

// $content = file_get_contents('http://www.xmlcharts.com/live/precious-metals.php?format=json'); 

// echo "<pre>";
// var_dump($content);
// die;
// //Database connection settings.
// // $dbhost = 'localhost';
// // $dbuser = 'admin';
// // $dbpass = '7bp4UgdZc';
// // $dbname = 'hollandgold_margemod
// $dbhost = '185.37.64.19';
// $dbuser = 'hollandgold';
// $dbpass = '43aXs#nm';
// $dbname = 'hollandgold_margemod';
include "database.php";
$sql = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
$row = $sql->query("SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1")->fetch_assoc();
$sql->close();
$troyOnce = 32.1507466;
$rateGold = ($troyOnce * $row['Ask']);
echo   number_format($rateGold,0,'','.');
