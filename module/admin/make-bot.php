<?php
	check_admin();
	require_once 'admin_lib.php';
	require_once 'lib/paysys.php';
	
	
	if (isset($_REQUEST['newbot']))
    {	
		try 
		{
			$good_msg = make_bot();
			//header('Location: show-bots');
			//exit;
		} 
		catch (Exception $error)
		{
			//$bad_msg =  $_TRANS[$error->getMessage()];
			$bad_msg =  $error->getMessage();
		}	
	}
	
	$paysys = all_paysys ();
	$plans  = all_plans ();
?>