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
    $to = "support@elmaonline.nl , rratinov@gmail.com";
    $subject = "Hollandgold error report";
    $headers = "From: support@elmaonline.nl";
    mail($to,$subject, mysqli_connect_error(), $headers);
    exit();
}
?>