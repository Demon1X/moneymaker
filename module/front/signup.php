<?php
	if (isset($_REQUEST['signup'])) 
	{
		try 
		{
			$good_msg = signup ($_REQUEST['login'], $_REQUEST['pass1'], $_REQUEST['pass2'], $_REQUEST['email'], $_REQUEST['inviter']);
		} 
		catch (Exception $error)
		{
			
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		

?>