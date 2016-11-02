<?php
	check_admin();
	require_once 'admin_lib.php';

	
	if (isset($_REQUEST['edituser'])) 
	{
		try 
		{
			@$uid = $route[1];
			$good_msg = edit_user ($uid, $_REQUEST['group'], $_REQUEST['login'], $_REQUEST['email'], $_REQUEST['pass'], $_REQUEST['level'], $_REQUEST['inviter'], $_REQUEST['status']);
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		
	
	
	@$uid = $route[1]; 	
	$user = user_info($uid);
?>