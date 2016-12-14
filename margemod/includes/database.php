<?php

$dbhost = 'localhost';
$dbuser = 'admin';
$dbpass = '7bp4UgdZc';
$dbname = 'hollandgold_margemod';

// $dbhost = '185.37.64.19';
// $dbuser = 'hollandgold';
// $dbpass = '43aXs#nm';
// $dbname = 'hollandgold_margemod';


// Database verbinding maken
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$link) {
    $to = "contact@westpointdigital.nl , rratinov@gmail.com";
    $subject = "Hollandgold error report" . date(DATE_RFC822);
    $headers = "From: contact@westpointdigital.nl";
    mail($to,$subject, date(DATE_RFC822) . ": " . mysqli_connect_error(), $headers);
    exit();
}
?>