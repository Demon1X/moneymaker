<?php
	if (isset($_REQUEST['email'])) 
	{
		try 
		{
			$good_msg = reset_pass ($_REQUEST['email']);
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		

	
	
?>