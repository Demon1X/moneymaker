<?php
	check_user();
	
	if (isset($_REQUEST['save']))
    {	
		try 
		{
			$good_msg = $_TRANS[edit_info($_REQUEST['sex'], $_REQUEST['age'], $_REQUEST['country'], $_REQUEST['town'], $_REQUEST['profession'], $_REQUEST['salary'])];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}
	
	
?>