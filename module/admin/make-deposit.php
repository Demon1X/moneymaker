<?php
	check_admin();
	require_once 'admin_lib.php';
	
	$plans = all_plans ();
	
	if (isset($_REQUEST['create']))
    {	
		$user = user_info(get_id($_REQUEST['login']));
		
		try 
		{
			$good_msg = make_deposit(get_id($_REQUEST['login']), $_REQUEST['plan'], $_REQUEST['amount']);
			header('Location: show-deposits');
			exit;
		} 
		catch (Exception $error)
		{
			//$bad_msg =  $_TRANS[$error->getMessage()];
			$bad_msg =  $error->getMessage();
		}	
	}
?>