<?php
	check_admin();
	require_once 'admin_lib.php';
	
	filter();
	$deposits = all_deposits();
	$plans    = all_plans ();
?>