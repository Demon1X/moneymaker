<?php
	check_admin();
	require_once 'admin_lib.php';
	
	if (isset($_REQUEST['save'])) 
	{
		try 
		{
			$good_msg = save_settings ();
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		


?>