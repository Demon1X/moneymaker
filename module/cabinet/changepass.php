<?php
	check_user();
	
	if (isset($_REQUEST['changepass']))
    {	
		try 
		{
			$good_msg = change_pass ($_REQUEST['oldpass'], $_REQUEST['pass1'], $_REQUEST['pass2']);
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}
	
	

?>