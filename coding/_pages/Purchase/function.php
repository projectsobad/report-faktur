<?php	
// tampilan
require 'view/Purchase.php';
	
// sidemenu
// require 'sidemenu/Purchase.php';

function _date_compare_array($array_1,$array_2){
	$date_1 = strtotime($array_1['_due_date']);
	$date_2 = strtotime($array_2['_due_date']);
	return $date_1 - $date_2;
}