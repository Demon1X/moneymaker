<?php
	check_user();

	$user = user_info($_SESSION['UID']);
	$plans = all_plans ();
	
	if (isset($_REQUEST['create']))
    {	
		try 
		{
			$good_msg = make_deposit($_SESSION['UID'], $_REQUEST['plan'], $_REQUEST['amount']);
			header('Location: deposits');
			exit;
		} 
		catch (Exception $error)
		{
			//$bad_msg =  $_TRANS[$error->getMessage()];
			$bad_msg =  $error->getMessage();
		}	
	}

?>