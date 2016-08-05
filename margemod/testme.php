<?php


$content = file_get_contents('http://www.xmlcharts.com/live/precious-metals.php?format=json'); 
echo $content;