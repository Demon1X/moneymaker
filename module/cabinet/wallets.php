<?php
	check_user();
	require_once 'lib/paysys.php';
	
	if (isset($_REQUEST['save']))
    {	
		try 
		{
			$good_msg = $_TRANS[edit_wallets(@$_REQUEST['perfect'], @$_REQUEST['payeer'])];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}
	
	$user = user_info($_SESSION['UID']);
	$paysys = all_paysys ();

?>