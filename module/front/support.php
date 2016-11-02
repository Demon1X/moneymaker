<?php
	if (isset($_REQUEST['send'])) {
		try 
		{
			$good_msg = to_send ($_REQUEST['email'], $_REQUEST['subject'], $_REQUEST['msg']);
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		
	
?>