<?php
	check_user();
	require_once 'lib/paysys.php';
	
	$paysys = all_paysys ();
	
	$operations = array ();
	//$operations = user_operations($_SESSION['UID'], 'ADDFUNDS');
?>