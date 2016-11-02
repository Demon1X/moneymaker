<?php
	check_admin();
	require_once 'admin_lib.php';
	require_once 'lib/paysys.php';
	
	if (isset($_REQUEST['editps'])) 
	{
		try 
		{
			@$ps = $route[1];
			$good_msg = edit_paysys ();
			$good_msg = $_TRANS[$good_msg];
		} 
		catch (Exception $error)
		{
			$bad_msg =  $_TRANS[$error->getMessage()];
		}	
	}		
	
	
	@$ps = $route[1]; 	
	$paysys = get_paysys($ps);

?>