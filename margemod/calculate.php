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

// database verbinding includen
include ('includes/database.php');

// if (count($_POST) > 0) {
// 	echo '<pre>';
// 	var_dump($_POST);
// 	echo '</pre></body></html>';
// 	die;
// }
/**/
// Controleren of formulier gepost is
// if (isset($_POST['save']) || isset($_POST['calculate'])) {
if (count($_POST) > 0) {
	
    // Aantal rijen in een variabele stoppen
    $rowCountSilver    = $_POST['rowCountSilver'];
    $rowCountGold      = $_POST['rowCountGold'];
    $rowCountPlatinum  = $_POST['rowCountPlatinum'];
    $rowCountPalladium = $_POST['rowCountPalladium'];

    // ZILVER
    // Variabelen ophalen van de form voor Zilver
    for ($i=0; $i < $rowCountSilver; $i++) {
        $silverId[]         = $_POST['silverId' . $i];
        $silverName[]       = $_POST['silverName' . $i];
        $silverQty[]        = $_POST['silverQty' . $i];
        $silverMargin[]     = $_POST['silverMargin' . $i];
        $silverCommission[] = $_POST['silverCommission' . $i];
        $silverProfit[]     = $_POST['silverProfit' . $i];
        $silverBtw[]        = $_POST['silverBtw' . $i];
        $silverStaffel[]    = $_POST['silverStaffel' . $i];
    }

    if (count($_POST) > 0) {        
        // Form variabelen in de database stoppen.
        if (isset($silverId)) {            
            foreach ($silverId as $key => $value) {		
			
                // Margemod tabel updaten
                $sql = "UPDATE `margemod` SET `qty` = '". $silverQty[$key] ."', `margin` = '". $silverMargin[$key] ."',
                    `commission` = '". $silverCommission[$key] ."', `profit` = '". $silverProfit[$key] ."', `btw` = '". $silverBtw[$key] ."',
                    `staffel` = '". $silverStaffel[$key] ."' WHERE `products_id` = '". $silverId[$key] ."';";
                mysqli_query($link, $sql) or die(mysqli_error($link));
            }
        }
    }

    // Goud
    // Variabelen ophalen van de form voor Goud
    for ($i=0; $i < $rowCountGold; $i++) {

        $goldId[]         = $_POST['goldId' . $i];
        $goldName[]       = $_POST['goldName' . $i];
        $goldQty[]        = $_POST['goldQty' . $i];
        $goldMargin[]     = $_POST['goldMargin' . $i];
        $goldCommission[] = $_POST['goldCommission' . $i];
        $goldProfit[]     = $_POST['goldProfit' . $i];
        $goldBtw[]        = $_POST['goldBtw' . $i];        
        $goldStaffel[]    = $_POST['goldStaffel' . $i];
    }

    if (count($_POST) > 0) {
        // Form variabelen in de database stoppen.
        if (isset($goldId)) {
            foreach ($goldId as $key => $value) {
                // Margemod tabel updaten
                $sql = "UPDATE `margemod` SET `qty` = '". $goldQty[$key] ."', `margin` = '". $goldMargin[$key] ."',
                    `commission` = '". $goldCommission[$key] ."', `profit` = '". $goldProfit[$key] ."', `btw` = '". $goldBtw[$key] ."',
                    `staffel` = '". $goldStaffel[$key] ."' WHERE `products_id` ='". $goldId[$key] ."';";
               mysqli_query($link, $sql) or die(mysqli_error($link));
            }
        }
    }


    // Platinum
    // Variabelen ophalen van de form voor Platinum
    for ($i=0; $i < $rowCountPlatinum; $i++) {
        $platinumId[]         = $_POST['platinumId' . $i];
        $platinumName[]       = $_POST['platinumName' . $i];
        $platinumQty[]        = $_POST['platinumQty' . $i];
        $platinumMargin[]     = $_POST['platinumMargin' . $i];
        $platinumCommission[] = $_POST['platinumCommission' . $i];
        $platinumProfit[]     = $_POST['platinumProfit' . $i];
        $platinumBtw[]        = $_POST['platinumBtw' . $i];        
        $platinumStaffel[]    = $_POST['platinumStaffel' . $i];
    }

    if (count($_POST) > 0) {
        // Form variabelen in de database stoppen.
        if (isset($platinumId)) {
            foreach ($platinumId as $key => $value) {
                // Margemod tabel updaten
                $sql = "UPDATE `margemod` SET `qty` = '". $platinumQty[$key] ."', `margin` = '". $platinumMargin[$key] ."',
                    `commission` = '". $platinumCommission[$key] ."', `profit` = '". $platinumProfit[$key] ."', `btw` = '". $platinumBtw[$key] ."',
                    `staffel` = '". $platinumStaffel[$key] ."' WHERE `products_id` ='". $platinumId[$key] ."';";
                    //echo $sql.'<br>';
                mysqli_query($link, $sql) or die(mysqli_error($link));
            }
        }
    }
    


    // Palladium
    // Variabelen ophalen van de form voor Palladium
    for ($i=0; $i < $rowCountPalladium; $i++) {
        $palladiumId[]         = $_POST['palladiumId' . $i];
        $palladiumName[]       = $_POST['palladiumName' . $i];
        $palladiumQty[]        = $_POST['palladiumQty' . $i];
        $palladiumMargin[]     = $_POST['palladiumMargin' . $i];
        $palladiumCommission[] = $_POST['palladiumCommission' . $i];
        $palladiumProfit[]     = $_POST['palladiumProfit' . $i];
        $palladiumBtw[]        = $_POST['palladiumBtw' . $i];        
        $palladiumStaffel[]    = $_POST['palladiumStaffel' . $i];
    }

    if (count($_POST) > 0) {
        // Form variabelen in de database stoppen.
        if (isset($palladiumId)) {
            foreach ($palladiumId as $key => $value) {
                // Margemod tabel updaten
                $sql = "UPDATE `margemod` SET `qty` = '". $palladiumQty[$key] ."', `margin` = '". $palladiumMargin[$key] ."',
                    `commission` = '". $palladiumCommission[$key] ."', `profit` = '". $palladiumProfit[$key] ."', `btw` = '". $palladiumBtw[$key] ."',
                    `staffel` = '". $palladiumStaffel[$key] ."' WHERE `products_id` ='". $palladiumId[$key] ."';";
                mysqli_query($link, $sql) or die(mysqli_error($link));
            }
        }
    }
 // die;   
        
} //else { 
    // ZILVER
    // Waneer de form voor het eerst gebouwd wordt dus als formulier niet gepost is Zilver query doen
    $sql = "SELECT * FROM margemod WHERE category = 'silver' ORDER BY products_name ASC";
    $result =  mysqli_query($link, $sql) or die(mysqli_error($link));
    // aantal rijen voor zilver in var stoppen
    $rowCountSilver = mysqli_num_rows($result);
    // Zilver varabelen toewijzen vanuit query om daarmee de form op te bouwen
    while($row = mysqli_fetch_array($result)) {
        $silverId[]         = $row['products_id'];
        $silverName[]       = $row['products_name'];
        $silverQty[]        = $row['qty'];
        $silverMargin[]     = $row['margin'];
        $silverCommission[] = $row['commission'];
        $silverProfit[]     = $row['profit'];
        $silverBtw[]        = $row['btw'];
        $silverStaffel[]    = $row['staffel'];
    }

    // GOUD
    // Waneer de form voor het eerst gebouwd wordt dus als formulier niet gepost is Zilver query doen
    $sql = "SELECT * FROM margemod WHERE category = 'gold' ORDER BY products_name ASC";
    $result =  mysqli_query($link, $sql) or die(mysqli_error($link));
    // aantal rijen voor zilver in var stoppen
    $rowCountGold = mysqli_num_rows($result);
    // Zilver varabelen toewijzen vanuit query om daarmee de form op te bouwen
    while($row = mysqli_fetch_array($result)) {
        $goldId[]         = $row['products_id'];
        $goldName[]       = $row['products_name'];
        $goldQty[]        = $row['qty'];
        $goldMargin[]     = $row['margin'];
        $goldCommission[] = $row['commission'];
        $goldProfit[]     = $row['profit'];
        $goldBtw[]        = $row['btw'];
        $goldStaffel[]    = $row['staffel'];
    }
    
    // PLATINUM
    // Waneer de form voor het eerst gebouwd wordt dus als formulier niet gepost is PLATINUM query doen
    $sql = "SELECT * FROM margemod WHERE category = 'platinum' ORDER BY products_name ASC";
    $result = mysqli_query($link, $sql) or die(mysqli_error($link));
    // aantal rijen voor zilver in var stoppen
    $rowCountPlatinum = mysqli_num_rows($result);
    // Zilver varabelen toewijzen vanuit query om daarmee de form op te bouwen
    while($row = mysqli_fetch_array($result)) {
        $platinumId[]         = $row['products_id'];
        $platinumName[]       = $row['products_name'];
        $platinumQty[]        = $row['qty'];
        $platinumMargin[]     = $row['margin'];
        $platinumCommission[] = $row['commission'];
        $platinumProfit[]     = $row['profit'];
        $platinumBtw[]        = $row['btw'];
        $platinumStaffel[]    = $row['staffel'];
    }
    
    // PLATINUM
    // Waneer de form voor het eerst gebouwd wordt dus als formulier niet gepost is PLATINUM query doen
    $sql = "SELECT * FROM margemod WHERE category = 'palladium' ORDER BY products_name ASC";
    $result =  mysqli_query($link, $sql) or die(mysqli_error($link));
    // aantal rijen voor zilver in var stoppen
    $rowCountPalladium = mysqli_num_rows($result);
    // Zilver varabelen toewijzen vanuit query om daarmee de form op te bouwen
    while($row = mysqli_fetch_array($result)) {
        $palladiumId[]         = $row['products_id'];
        $palladiumName[]       = $row['products_name'];
        $palladiumQty[]        = $row['qty'];
        $palladiumMargin[]     = $row['margin'];
        $palladiumCommission[] = $row['commission'];
        $palladiumProfit[]     = $row['profit'];
        $palladiumBtw[]        = $row['btw'];
        $palladiumStaffel[]    = $row['staffel'];
    }    
        
//}
?>

<h1>Marge Module</h1>
<table border="0" width="150px" cellpadding="10" cellspacing="10">
    <tr>
        <td width="100px"><center><a href="addnew.php"><img src="images/add48.gif" class="imagebutton" alt="Producten toevoegen" /><br />Producten toevoegen</a></center></td>
        <td width="100px"><center><a href="calculate.php"><img src="images/calculator.gif" class="imagebutton" alt="Prijzen berekenen" /><br />Prijzen berekenen</a></center></td>
    </tr>
</table>
<h2>Prijs berekenen <a href="#" onclick="toggle_visibility('info');"><img src="images/info.gif" class="imagebutton" alt="Extra uitleg" /></a></h2>
<div id="info" style="display:none;">
<ul>
<li>Hier kan de prijs van elk product uitgerekend worden</li>
<li>Voeg eerst alle producten toe waarvan de prijs berekend moet worden in de Margemod door '<i>Producten toevoegen</i>' te kiezen</li>
<li>De eerste keer dienen alle velden ingevuld te worden</li>
<li>Gebruik altijd de '<i>Bereken</i>' knop te zien hoe de prijzen worden ter controle</li>
<li>Wanneer de prijzen in orde zijn kunnen deze opgelagen worden met de '<i>Opslaan</i>' knop</li>
</ul>
</div>
<hr />

<form action="calculate.php" method="post">

<h2>Zilver</h2>

<!--<h3><a href="#" onclick="toggle_visibility('zilver');"><img src="images/add16.gif" class="imagebutton" alt="Uitklappen" /></a></h3>
<div id="zilver" style="display:none;">-->
<div id="zilver">
<?php
// Controleren of er producten in de Margemod zitten voor silver. Zo niet, melding geven.
if ($rowCountSilver == 0) {
    echo "<p><b>Geen producten aanwezig.</b></p><p>Klik bovenin op <i>'producten toevoegen'</i> om producten toe te voegen.</p>";
} else {
?>
<table border="0" width="100%">
    <tr>
        <td><b>ID</b></td>
        <td width="400px"><b>Naam</b></td>
        <td><b>Aantal</b></td>
        <td><b>Marge kosten</b></td>
        <td><b>Commissie</b></td>
        <td><b>Winstmarge</b></td>
        <td><b>Extra factor</b></td>
        <td><b>Staffel<br /> korting</b></td>
    </tr>
    <tr>
        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
<?php
for ($i=0; $i < $rowCountSilver; $i++ ) {
    echo '
                    <tr>
                        <td><input type="text" id="silverId' . $i . '" name="silverId' . $i . '" size="4" value="' . $silverId[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="silverName' . $i . '" name="silverName' . $i . '" size="60" value="' . $silverName[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="silverQty' . $i . '" name="silverQty' . $i . '" size="4" value="' . $silverQty[$i] . '" /></td>
                        <td><input type="text" id="silverMargin' . $i . '" name="silverMargin' . $i . '" size="6" value="' . $silverMargin[$i] . '" /></td>
                        <td><input type="text" id="silverCommission' . $i . '" name="silverCommission' . $i . '" size="6" value="' . $silverCommission[$i]. '" /></td>
                        <td><input type="text" id="silverProfit' . $i . '" name="silverProfit' . $i . '" size="6" value="' . $silverProfit[$i] . '" /></td>
                        <td><input type="text" id="silverBtw' . $i . '" name="silverBtw' . $i . '" size="4" value="' . $silverBtw[$i] . '" /> </td>
                        <td><input type="text" id="silverStaffel' . $i . '" name="silverStaffel' . $i . '" size="4" value="' . $silverStaffel[$i] . '" />                            
                        </td>
                    </tr>
                    ';
}
echo '</table><br />';
}

echo '<input type="hidden" id="rowCountSilver" name="rowCountSilver" value="' . $rowCountSilver .'" />';

?>

</div>
<hr />
<h2>Goud</h2>

<!--<h3><a href="#" onclick="toggle_visibility('goud');"><img src="images/add16.gif" class="imagebutton" alt="Uitklappen" /></a></h3>
<div id="goud" style="display:none;">-->
<div id="goud">
<?php
// Controleren of er producten in de Margemod zitten voor gold. Zoniet, melding geven.
if ($rowCountGold == 0) {
    echo "<p><b>Geen producten aanwezig.</b></p><p>Klik bovenin op <i>'producten toevoegen'</i> om producten toe te voegen.</p>";
} else {
?>
<table border="0" width="100%">
    <tr>
        <td><b>ID</b></td>
        <td width="400px"><b>Naam</b></td>
        <td><b>Aantal</b></td>
        <td><b>Marge kosten</b></td>
        <td><b>Commissie</b></td>
        <td><b>Winstmarge</b></td>
        <td><b>Extra Factor</b></td>
        <td><b>Staffel<br /> korting</b></td>
    </tr>
    <tr>
        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
<?php
for ($i=0; $i < $rowCountGold; $i++ ) {
    echo '
                    <tr>
                        <td><input type="text" id="goldId' . $i . '" name="goldId' . $i . '" size="4" value="' . $goldId[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="goldName' . $i . '" name="goldName' . $i . '" size="60" value="' . $goldName[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="goldQty' . $i . '" name="goldQty' . $i . '" size="4" value="' . $goldQty[$i] . '" /></td>
                        <td><input type="text" id="goldMargin' . $i . '" name="goldMargin' . $i . '" size="6" value="' . $goldMargin[$i] . '" /></td>
                        <td><input type="text" id="goldCommission' . $i . '" name="goldCommission' . $i . '" size="6" value="' . $goldCommission[$i]. '" /></td>
                        <td><input type="text" id="goldProfit' . $i . '" name="goldProfit' . $i . '" size="6" value="' . $goldProfit[$i] . '" /></td>
                        <td><input type="text" id="goldBtw' . $i . '" name="goldBtw' . $i . '" size="4" value="' . $goldBtw[$i] . '" /> </td>
                        <td><input type="text" id="goldStaffel' . $i . '" name="goldStaffel' . $i . '" size="4" value="' . $goldStaffel[$i] . '" />                            
                        </td>
                    </tr>
                    ';
}
echo '</table><br />';
}

echo '<input type="hidden" id="rowCountGold" name="rowCountGold" value="' . $rowCountGold .'" />';

?>

</div>

<hr />
<h2>Platinum</h2>

<!--<h3><a href="#" onclick="toggle_visibility('zilver');"><img src="images/add16.gif" class="imagebutton" alt="Uitklappen" /></a></h3>
<div id="zilver" style="display:none;">-->
<div id="platinum">
<?php
// Controleren of er producten in de Margemod zitten voor silver. Zo niet, melding geven.
if ($rowCountPlatinum == 0) {
    echo "<p><b>Geen producten aanwezig.</b></p><p>Klik bovenin op <i>'producten toevoegen'</i> om producten toe te voegen.</p>";
} else {
?>
<table border="0" width="100%">
    <tr>
        <td><b>ID</b></td>
        <td width="400px"><b>Naam</b></td>
        <td><b>Aantal</b></td>
        <td><b>Marge kosten</b></td>
        <td><b>Commissie</b></td>
        <td><b>Winstmarge</b></td>
        <td><b>Extra factor</b></td>
        <td><b>Staffel<br /> korting</b></td>
    </tr>
    <tr>
        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
<?php
for ($i=0; $i < $rowCountPlatinum; $i++ ) {
    echo '
                    <tr>
                        <td><input type="text" id="platinumId' . $i . '" name="platinumId' . $i . '" size="4" value="' . $platinumId[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="platinumName' . $i . '" name="platinumName' . $i . '" size="60" value="' . $platinumName[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="platinumQty' . $i . '" name="platinumQty' . $i . '" size="4" value="' . $platinumQty[$i] . '" /></td>
                        <td><input type="text" id="platinumMargin' . $i . '" name="platinumMargin' . $i . '" size="6" value="' . $platinumMargin[$i] . '" /></td>
                        <td><input type="text" id="platinumCommission' . $i . '" name="platinumCommission' . $i . '" size="6" value="' . $platinumCommission[$i]. '" /></td>
                        <td><input type="text" id="platinumProfit' . $i . '" name="platinumProfit' . $i . '" size="6" value="' . $platinumProfit[$i] . '" /></td>
                        <td><input type="text" id="platinumBtw' . $i . '" name="platinumBtw' . $i . '" size="4" value="' . $platinumBtw[$i] . '" /> </td>
                        <td><input type="text" id="platinumStaffel' . $i . '" name="platinumStaffel' . $i . '" size="4" value="' . $platinumStaffel[$i] . '" />
                        </td>
                    </tr>
                    ';
}
echo '</table><br />';
}

echo '<input type="hidden" id="rowCountPlatinum" name="rowCountPlatinum" value="' . $rowCountPlatinum .'" />';

?>
</div>


<hr />
<h2>Palladium</h2>

<!--<h3><a href="#" onclick="toggle_visibility('zilver');"><img src="images/add16.gif" class="imagebutton" alt="Uitklappen" /></a></h3>
<div id="zilver" style="display:none;">-->
<div id="palladium">
<?php
// Controleren of er producten in de Margemod zitten voor silver. Zo niet, melding geven.
if ($rowCountPalladium == 0) {
    echo "<p><b>Geen producten aanwezig.</b></p><p>Klik bovenin op <i>'producten toevoegen'</i> om producten toe te voegen.</p>";
} else {
?>
<table border="0" width="100%">
    <tr>
        <td><b>ID</b></td>
        <td width="400px"><b>Naam</b></td>
        <td><b>Aantal</b></td>
        <td><b>Marge kosten</b></td>
        <td><b>Commissie</b></td>
        <td><b>Winstmarge</b></td>
        <td><b>Extra factor</b></td>
        <td><b>Staffel<br /> korting</b></td>
    </tr>
    <tr>
        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
<?php
for ($i=0; $i < $rowCountPalladium; $i++ ) {
    echo '
                    <tr>
                        <td><input type="text" id="palladiumId' . $i . '" name="palladiumId' . $i . '" size="4" value="' . $palladiumId[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="palladiumName' . $i . '" name="palladiumName' . $i . '" size="60" value="' . $palladiumName[$i] . '" readonly="readonly" /></td>
                        <td><input type="text" id="palladiumQty' . $i . '" name="palladiumQty' . $i . '" size="4" value="' . $palladiumQty[$i] . '" /></td>
                        <td><input type="text" id="palladiumMargin' . $i . '" name="palladiumMargin' . $i . '" size="6" value="' . $palladiumMargin[$i] . '" /></td>
                        <td><input type="text" id="palladiumCommission' . $i . '" name="palladiumCommission' . $i . '" size="6" value="' . $palladiumCommission[$i]. '" /></td>
                        <td><input type="text" id="palladiumProfit' . $i . '" name="palladiumProfit' . $i . '" size="6" value="' . $palladiumProfit[$i] . '" /></td>
                        <td><input type="text" id="palladiumBtw' . $i . '" name="palladiumBtw' . $i . '" size="4" value="' . $palladiumBtw[$i] . '" /> </td>
                        <td><input type="text" id="palladiumStaffel' . $i . '" name="palladiumStaffel' . $i . '" size="4" value="' . $palladiumStaffel[$i] . '" />                            
                        </td>
                    </tr>
                    ';
}
echo '</table><br />';
}

echo '<input type="hidden" id="rowCountPalladium" name="rowCountPalladium" value="' . $rowCountPalladium .'" />';

?>
</div>


<hr />
<?php
if ($rowCountSilver == 0 && $rowCountGold == 0 && $rowCountPlatinum == 0 && $rowCountPalladium == 0) {
    // doe niets
} else {
    echo '<p><input value="Berekenen" name="calculate" type="submit" /> <input value="Opslaan" name="save" type="submit" /></p>';
}
?>

<br />
</form>
<br />
</body>
</html>