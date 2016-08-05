<?php include("password_protect.php"); ?>
<?php
// Database settings LIVE
mysql_connect("localhost", "hollandgold", "43aXs#nm");
mysql_select_db("hollandgold2");

// Database settings WEBDELTA
// mysql_connect("localhost", "c1all4runuser2", "6474cfqE26MpX4U");
// mysql_select_db("c1all4running02");

?>
<!DOCTYPE html>
<html lang="nl-NL">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hollandgold bestellingen</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
* {
	color: #333;
	font-family: Arial, Helvetica, sans-serif, "Myriad Pro";
	font-size: 12px;
}
th {
	font-weight: bold;
	background-color: #CF0461;
	color: #FFF;
}
tr.oneven {
	background-color: #eee;
}
tr:hover {
	background-color: #FF6;
}
-->
</style>
</head>

<body>
<table width="100%" cellpadding="5">
<tr>
<th>teller</th>
<th>id</th>
<th>gegevens</th>
<th>IP adres</th>
</tr>
<?php

$sql = "SELECT DISTINCT
			a.increment_id, 
			a.customer_email, 
			a.customer_firstname, 
			a.customer_lastname, 
			a.created_at, 
			a.remote_ip, 
			c.country_id
		FROM 
			sales_flat_order a
		LEFT JOIN  sales_flat_order_address c
			ON c.parent_id = a.entity_id
		WHERE a.created_at > '2013-12-31' 
			AND c.country_id = 'BE' 
		";
$res = mysql_query($sql);
// foutafhandeling
if (!$res) {
	print "Mysql fout: ". mysql_error() ."\n";
}
$teller = 1;
while ($row = mysql_fetch_array($res))
{
	$rowcolor = "";
	$user = $row['customer_id'];
	if ($teller % 2) {
		$rowcolor = ' class="oneven"';
	}
	echo '<tr'.$rowcolor.'>';
	echo '<td valign="top" nowrap="nowrap">'.$teller.'</td>';
	echo '<td valign="top" nowrap="nowrap"><strong>'.$row['increment_id'].'</strong><br /><span style="font-size: 11px;">'.$row['created_at'].'</span></td>';
	//echo '<td>'; print_r($rowadres); echo '</td>';
	echo '<td valign="top">'.$row['customer_firstname'].' '.$row['customer_lastname'].'<br />'.$row['customer_email'].'</td>';
	echo '<td valign="top">'.$row['remote_ip'].'</td>';
	echo '</tr>';
	$teller++;
}

?>
</table>
</body>
</html>


