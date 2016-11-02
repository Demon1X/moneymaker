<?php
	check_user();
	require_once 'lib/paysys.php';
	
	
	if (isset($_REQUEST['addfunds']))
    {	
		try 
		{
			$oper_id = add_funds ($_SESSION['UID'], $_REQUEST['paysys'], $_REQUEST['amount']);
		} 
		catch (Exception $error)
		{
			$bad_msg =  $error->getMessage();
		}	
	}	
	
?>