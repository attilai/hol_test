<?php     

//Database connection settings.
$dbhost = 'localhost';
$dbuser = 'hollandgold';
$dbpass = '43aXs#nm';
$dbname = 'hollandgold_margemod';

// Database verbinding maken
$link = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect: ' . mysql_error());
if ($link) {
    // Tabel selecteren
    $db = mysql_select_db($dbname) or die(mysql_error());    
} 

$result = mysql_query("
SELECT
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Gold,
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Silver
") or die(mysql_error());

$row = mysql_fetch_assoc($result);
$goldRate = ($row['Gold']);
$silverRate = ($row['Silver']);

?>