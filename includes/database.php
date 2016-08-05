<?php

// Database gegevens instellen
$dbhost = 'localhost';
$dbuser = 'hollandgold';
$dbpass = '43aXs#nm';
$dbname = 'hollandgold_margemod';
 
// Database verbinding maken
$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
 
// Tabel selecteren
mysql_select_db($dbname) or die(mysql_error());

?>
