<?php
	check_user();
	
	if (isset($_REQUEST['save']))
    {	
		try 
		{
			$good_msg = set_settings($_REQUEST['gmt']);
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}

	$user = user_info($_SESSION['UID']);		
?>