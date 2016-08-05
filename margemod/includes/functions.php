<?php

//functie om prijzen netjes af te ronden
function roundnum($num){

	if ($num >= 0 && $num <= 20) {
		$nearest = 0.05;
	}

	if ($num > 20 && $num <= 100) {
		$nearest = 0.10;
	}

	if ($num > 100 && $num <= 1000) {
		$nearest = 0.50;
	}

	if ($num > 1000 && $num <= 5000) {
		$nearest = 1.00;
	}

	if ($num > 5000) {
		$nearest = 5.00;
	}

	return number_format(round($num / $nearest) * $nearest, 2, '.', '');
}

function sort_by_qty($a, $b){
	if ( $a['qty'] == $b['qty'] )
		return 0;
	else if ( $a['qty'] < $b['qty'] )
		return -1;
	else
		return 1;
}

?>