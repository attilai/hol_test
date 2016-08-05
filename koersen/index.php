<?php      

//Database connection settings.
include "database.php";
// $dbhost = '185.37.64.19';
// $dbuser = 'hollandgold';
// $dbpass = '43aXs#nm';
// $dbname = 'hollandgold_margemod';

// Database verbinding maken
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$result = mysqli_query($link, "
SELECT
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAU' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Gold,
    (SELECT Ask FROM margemod_quotes WHERE TYPE = 'XAG' ORDER BY Timestamp DESC LIMIT 0 , 1)  as Silver
") or die(mysqli_error($link));

$troyOnce = 32.1507466;
$row = mysqli_fetch_assoc($result);
$rateGold = ($troyOnce * $row['Gold']);
$rateSilver = ($troyOnce * $row['Silver']);

echo '<li class="active gold">
            <a href="javascript:void(0);" id="tab-gold"><i class="chart-status">&nbsp;</i><span>Goud<br>&euro; '.number_format($rateGold,0,'','.').' / kg</span><i class="chart-tab gold">&nbsp;</i></a>

        </li>
        <li class="silver">
            <a href="javascript:void(0);" id="tab-silver2"><i class="chart-status">&nbsp;</i><span>Zilver<br>&euro; '.number_format($rateSilver, 0,'',',').' / kg</span><i class="chart-tab silver">&nbsp;</i></a>
        </li>';
?>
