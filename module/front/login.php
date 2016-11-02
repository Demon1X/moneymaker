<?php
	if (isset($_REQUEST['login'])) 
	{
		try 
		{
			$good_msg = $_TRANS[login ($_REQUEST['login'], $_REQUEST['password'])];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		
	
	
?>