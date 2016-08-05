<?php     

//Database connection settings.
// $dbhost = 'localhost';
// $dbuser = 'admin';
// $dbpass = '7bp4UgdZc';
// $dbname = 'hollandgold_margemod';
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
include "database.php";
$result = mysqli_query($link, "
SELECT
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Gold,
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Silver,
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XPD' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Palladium,
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XPT' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Platinum
") or die(mysqli_error($link));

$row = mysqli_fetch_assoc($result);
$goldRate = ($row['Gold']);
$silverRate = ($row['Silver']);
$palladiumRate = ($row['Palladium']);
$platinumRate = ($row['Platinum']);

?>
