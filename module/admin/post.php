<?php
	check_admin();
	require_once 'admin_lib.php';
	
	
	if (isset($_REQUEST['send'])) 
	{
		try 
		{
			$good_msg = admin_to_send ($_REQUEST['sender'], $_REQUEST['sender_email'], $_REQUEST['recipients'], $_REQUEST['subject'], $_REQUEST['msg']);
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		

	
?>