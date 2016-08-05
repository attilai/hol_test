<?php     
   
//Database connection settings.
//$dbhost = 'localhost';
//$dbuser = 'admin';
//$dbpass = '7bp4UgdZc';
//$dbname = 'hollandgold_margemod';
include "database.php";

// Database verbinding maken
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$result = mysqli_query($link, "
SELECT
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAU,
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAG,
	(SELECT Ask FROM margemod_quotes WHERE TYPE = 'XPT' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPT,
	(SELECT Ask FROM margemod_quotes WHERE TYPE = 'XPD' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPD
") or die(mysqli_error($link));

$troyOnce = 32.1507466;
$row = mysqli_fetch_assoc($result);

//print_r($row);
 header ("Content-Type:text/xml");
 echo '<koersen>
 <XAU>'.number_format(($troyOnce * $row['XAU']),0,'','').'</XAU>
 <XAG>'.number_format(($troyOnce * $row['XAG']),0,'','').'</XAG>
 <XPT>'.number_format(($troyOnce * $row['XPT']),0,'','').'</XPT>
 <XPD>'.number_format(($troyOnce * $row['XPD']),0,'','').'</XPD>
 </koersen>';

?>
