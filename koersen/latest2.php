<?php     
   
//Database connection settings.
include "database.php";

// Database verbinding maken
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$result = mysqli_query($link, "
SELECT
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAU_ASK,
	(SELECT Rate FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAU_RATE,
	(SELECT Bid FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAU_BID,
	
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAG_ASK,
	(SELECT Rate FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAG_RATE,
	(SELECT Bid FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XAG_BID,
	
	(SELECT Ask FROM margemod_quotes WHERE TYPE = 'XPT' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPT_ASK,
	(SELECT Rate FROM margemod_quotes WHERE TYPE = 'XPT' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPT_RATE,
	(SELECT Bid FROM margemod_quotes WHERE TYPE = 'XPT' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPT_BID,
	
	(SELECT Ask FROM margemod_quotes WHERE TYPE = 'XPD' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPD_ASK,
	(SELECT Rate FROM margemod_quotes WHERE TYPE = 'XPD' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPD_RATE,
	(SELECT Bid FROM margemod_quotes WHERE TYPE = 'XPD' ORDER BY Timestamp DESC LIMIT 0 , 1)  as XPD_BID
") or die(mysqli_error($link));

$troyOnce = 32.1507466;
$row = mysql_fetch_assoc($result);

//print_r($row);
 header ("Content-Type:text/xml");

 echo '<koersen>
<XAU>
	<RATE>'.($troyOnce * $row['XAU_RATE']).'</RATE>
	<BID>'.($troyOnce * $row['XAU_BID']).'</BID>
	<ASK>'.($troyOnce * $row['XAU_ASK']).'</ASK>
</XAU>
<XAG>
	<RATE>'.($troyOnce * $row['XAG_RATE']).'</RATE>
	<BID>'.($troyOnce * $row['XAG_BID']).'</BID>
	<ASK>'.($troyOnce * $row['XAG_ASK']).'</ASK>
</XAG>
<XPT>
	<RATE>'.($troyOnce * $row['XPT_RATE']).'</RATE>
	<BID>'.($troyOnce * $row['XPT_BID']).'</BID>
	<ASK>'.($troyOnce * $row['XPT_ASK']).'</ASK>
</XPT>
<XPD>
	<RATE>'.($troyOnce * $row['XPD_RATE']).'</RATE>
	<BID>'.($troyOnce * $row['XPD_BID']).'</BID>
	<ASK>'.($troyOnce * $row['XPD_ASK']).'</ASK>
</XPD>
</koersen>';

 /*
 echo '<koersen>
		 <XAU>'.number_format(($troyOnce * $row['XAU']),0,'','').'</XAU>
		 <XAG>'.number_format(($troyOnce * $row['XAG']),0,'','').'</XAG>
		 <XPT>'.number_format(($troyOnce * $row['XPT']),0,'','').'</XPT>
		 <XPD>'.number_format(($troyOnce * $row['XPD']),0,'','').'</XPD>
 </koersen>';
 */
?>