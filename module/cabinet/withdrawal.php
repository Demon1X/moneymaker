<?php
	check_user();
	require_once 'lib/paysys.php';
	

	if (isset($_REQUEST['withdrawal']))
    {	
		try 
		{
			$good_msg = withdrawal ($_SESSION['UID'], $_REQUEST['paysys'], $_REQUEST['amount'], $_REQUEST['curr']);
		} 
		catch (Exception $error)
		{
			//$bad_msg =  $_TRANS[$error->getMessage()];
			$bad_msg =  $error->getMessage();
		}	
	}
	
	$paysys = all_paysys ();
	
	$operations = array ();
	//$operations = user_operations($_SESSION['UID'], 'WITHDRAWAL');
?>