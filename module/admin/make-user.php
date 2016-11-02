<?php
	check_admin();
	require_once 'admin_lib.php';
	
	
	if (isset($_REQUEST['makeuser'])) 
	{
		try 
		{
			$good_msg = make_user ($_REQUEST['login'], $_REQUEST['pass'], $_REQUEST['pass'], $_REQUEST['email']);
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		
	
?>