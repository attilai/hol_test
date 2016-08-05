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
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Silver
") or die(mysqli_error($link));

$troyOnce = 32.1507466;
$row = mysqli_fetch_assoc($result);
$rateSilver = $troyOnce * $row['Silver'];

echo   number_format($rateSilver, 0,',','');

?>
