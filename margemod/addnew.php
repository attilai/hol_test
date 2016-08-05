<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="nl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Marge Module - Producten toevoegen</title>
<link rel="stylesheet" href="includes/style.css" type="text/css" media="all" />
<script type="text/javascript">
<!--
function toggle_visibility(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'block')
	e.style.display = 'none';
	else
	e.style.display = 'block';
}
//-->
</script>
</head>
<body>
<?php

// functies includen
include ('includes/functions.php');

// connect to Magento
include('includes/connect.php');

// databse verbinding includen
include ('includes/database.php');


// alle aanwezig producten selecteren uit de winkel
//$sql = "SELECT products_name, products_id FROM products_description WHERE language_id = '1' ORDER BY products_name ASC";
//$result = mysql_query($sql) or die(mysql_error());

$result = $client->call($session, 'product.list');


$rowCountInShop = count($result);

// array met alle producten doorlopen om deze toe te voegen aan de margemod tabel

foreach($result as $row) {
	// variabelen toewijzen
	$productId = $row['product_id'];
	$productName = $row['name'];

	// query om te kunnen controleren of product in de margemod tabel staat
	$sql1 = "SELECT * FROM margemod WHERE products_id = '" . $productId . "'";

	// echo $sql1."<br>";

	$result1 = mysqli_query($link, $sql1) or die(mysqli_error($link));

	// het aantal rijen toewijzen aan een variable om te kijken of het product al aanwezig is.
	// 
	$rowCount = mysqli_num_rows($result1);

	// Bij 0 rijen is het nog niet aanwezig in de margemod tabel, dus hier onder een insert query.
	if ($rowCount == 0) {
		// nieuwe producten toevoegen met als categorie 'none'
		$sql = "INSERT INTO `margemod` (`products_id`,  `products_name`, `qty`, `value`, `margin` ,
				`commission`, `profit`, `btw`, `price`, `total_price`, `category`)
				VALUES ('" . $productId . "', '" . $productName . "', 0, 0, '0', '0', '0', '1.00', 0, 0, 'none');";
				// echo $sql."<hr>";
		mysqli_query($link, $sql) or die(mysqli_error($link));
	}
	// Bij 1 rij is het al wel aanwzig in de margemod tabel dus een update doen zodat alles up to date is
	if ($rowCount == 1) {
		$sql = "UPDATE `margemod` SET `products_name` = '" . $productName . "' WHERE `products_id` ='" . $productId . "'";
		mysqli_query($link, $sql) or die(mysqli_error($link));
	}
}

?>


<h1>Marge Module</h1>
<table border="0" width="150px" cellpadding="10" cellspacing="10">
	<tr>
		<td width="100px"><center><a href="addnew.php"><img src="images/add48.gif" class="imagebutton" alt="Producten toevoegen" /><br />Producten toevoegen</a></center></td>
		<td width="100px"><center><a href="calculate.php"><img src="images/calculator.gif" class="imagebutton" alt="Prijzen berekenen" /><br />Prijzen berekenen</a></center></td>
	</tr>
</table>
<h2>Producten toevoegen <a href="#" onclick="toggle_visibility('info');"><img src="images/info.gif" class="imagebutton" alt="Extra uitleg" /></a></h2>
<div id="info" style="display:none;">
<ul>
<li>Hier kunnen producten toevoegen en verwijderd worden in de Margemod</li>
<li>Er kan een metaal categorie toegewezen worden per product</li>
<li>Alle producten die in de webwinkel staan worden hier automatisch ingeladen</li>
<li>Gebruik de Margemod niet wanneer er producten in de shop worden toegevoegd of gewijzigd</li>
<li>Als de prijs van een product niet meer door de Margemod berekend moet worden, zet dan de radio button op geen.</li>
<li>Gebruik de verwijder knop als er in de lijst oude producten staan die niet meer in de shop voorkomen.</li>
</ul>
</div>

<?php

// Als er producten in de shop zitten de form bouwen, anders een medling geven
if ($rowCountInShop > 0) {

?>
	<form action="addnew.php" method="post">
	<table border="0" width="800px">
		<tr>
			<td width="80px"><b>ID</b></td>
			<td width="450px"><b>Naam</b></td>
			<td><b>Zilver</b></td>
			<td><b>Goud</b></td>
			<td><b>Platinum</b></td>
			<td><b>Palladium</b></td>
			<td><b>Geen</b></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td>
			<td>&nbsp;</td><td>&nbsp;</td>
		</tr>

<?php

	if (/*isset($_GET['action']) &&*/ $_GET['action'] == 'delete') {

		$deleteId = $_GET['deleteId'];

		// Artikel uit margemod tabel verwijderen
		$sql = "DELETE FROM margemod WHERE products_id = '" . $deleteId . "'";
		mysqli_query($link, $sql) or die(mysqli_error($link));
	}

	if ('' != $_POST['save']) {

		// aantal product rijen ophalen
		$rowCount = $_POST['rowCount'];

		// post  variablen in arrays stoppen
		for ($i=0; $i < $rowCount; $i++) {
			$productIdArray[]      = $_POST['productId'      . $i];
			$productNameArray[]    = $_POST['productName'    . $i];
			$productCategoryArray[] = $_POST['productCategory' . $i];
		}
		// arrays opbouwen voor de radio buttons
		foreach ($productCategoryArray as $key => $value) {
			/*
			if ($value == 'silver') {
				$silverRadio[] = 'checked="checked"';
				$goldRadio[] = '';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = '';
				$noneRadio[] = '';
			}

			if ($value == 'gold') {
				$silverRadio[] = '';
				$goldRadio[] = 'checked="checked"';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = '';            
				$noneRadio[] = '';
			}

	        if ($value == 'platinum') {
	            $silverRadio[] = '';
	            $goldRadio[] = '';
	            $platinumRadio[] = 'checked="checked"';
	            $palladiumRadio[] = '';         
	            $noneRadio[] = '';
	        }

	        if ($value == 'palladium') {
	            $silverRadio[] = '';
	            $goldRadio[] = '';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = 'checked="checked"';         
	            $noneRadio[] = '';
	        }
	                
			if ($value == 'none') {
				$silverRadio[] = '';
				$goldRadio[] = '';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = '';			
				$noneRadio[] = 'checked="checked"';
			}*/

			// Categorien updaten bij elke product categorie
			$sql = "UPDATE `margemod` SET `category` = '" . $productCategoryArray[$key] . "' WHERE `products_id` = '" . $productIdArray[$key] . "'";

			mysqli_query($link, $sql) or die(mysqli_error());
		}
	} //else { // disabled by me :)

		// alle producten uit de shop selecteren om hiermee de formulier te gaan maken
		$sql = "SELECT * FROM margemod ORDER BY products_name ASC";
		$result = mysqli_query($link, $sql) or die(mysqli_error($link));

		// aantal producten in een var stoppen zodat bekend is hoeveel rijen er voor de form gemaakt dienen te worden.
		$rowCount = mysqli_num_rows($result);

		// Naam en id in arrays stoppen
		while($row = mysqli_fetch_assoc($result)) {
			$productIdArray[] = $row['products_id'];
			$productNameArray[] = $row['products_name'];
			$productCategoryArray[] = $row['category'];
		}

		foreach ($productCategoryArray as $value) {

			if ($value == 'silver') {
				$silverRadio[] = 'checked="checked"';
				$goldRadio[] = '';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = '';
				$noneRadio[] = '';
			}

			if ($value == 'gold') {
				$silverRadio[] = '';
				$goldRadio[] = 'checked="checked"';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = '';            
				$noneRadio[] = '';
			}
			
	        if ($value == 'platinum') {
	            $silverRadio[] = '';
	            $goldRadio[] = '';
	            $platinumRadio[] = 'checked="checked"';
	            $palladiumRadio[] = '';         
	            $noneRadio[] = '';
	        }

	        if ($value == 'palladium') {
	            $silverRadio[] = '';
	            $goldRadio[] = '';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = 'checked="checked"';         
	            $noneRadio[] = '';
	        }		

			if ($value == 'none') {
				$silverRadio[] = '';
				$goldRadio[] = '';
	            $platinumRadio[] = '';
	            $palladiumRadio[] = '';            
				$noneRadio[] = 'checked="checked"';
			}

		}
	//} // disabled by me :)
	$i = 0;

	foreach ($productIdArray as $value) {
		echo '
				<tr>
					<td><input type="text" id="productId' . $i . '" name="productId' . $i . '" size="6" value="' . $productIdArray[$i] . '" readonly="readonly" /></td>
					<td><input type="text" id="productName' . $i . '" name="productName' . $i . '" size="60" value="' . $productNameArray[$i] . '"   readonly="readonly" /></td>
					<td><input type="radio" name="productCategory' . $i . '" value="silver" '. $silverRadio[$i] .' /></td>
					<td><input type="radio" name="productCategory' . $i . '" value="gold" '. $goldRadio[$i] .' /></td>
					<td><input type="radio" name="productCategory' . $i . '" value="platinum" '. $platinumRadio[$i] .' /></td>
					<td><input type="radio" name="productCategory' . $i . '" value="palladium" '. $palladiumRadio[$i] .' /></td>
					<td><input type="radio" name="productCategory' . $i . '" value="none" '. $noneRadio[$i] .' />
					<input type="hidden" id="rowCount' . $i . '" name="rowCount" value="' . $rowCount .'" /></td>
					<td><a href="addnew.php?action=delete&amp;deleteId=' . $productIdArray[$i] . '" onclick="return confirm(\'Weet je het zeker?\')"><img src="images/delete.gif" class="imagebutton" alt="Verwijderen" /></a></td>
				</tr>
				';
		$i++;
	}

	echo '</table>';
	echo '<br />';
	echo '<input value="Opslaan" name="save" type="submit" />';
	echo '</form>';

} else {
	echo '<br /><p><b>Geen producten in de shop.</b></p>';
}

// disconnect from Magento
include('includes/disconnect.php');
?>
<br />
<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
</p>
<br />
</body>
</html>