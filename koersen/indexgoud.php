<?php      
//Database connection settings.
//$dbhost = 'localhost';
//$dbuser = 'admin';
//$dbpass = '7bp4UgdZc';
//$dbname = 'hollandgold_margemod';
include "database.php";

$link =  mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);

if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$result = mysqli_query($link,"
SELECT
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Gold
") or die(mysqli_error($link));

$troyOnce = 32.1507466;
$row = mysqli_fetch_assoc($result);
$rateGold = $troyOnce * $row['Gold'];

echo   number_format($rateGold,0,'','.');

?>