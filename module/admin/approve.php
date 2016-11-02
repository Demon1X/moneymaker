<?php
	check_admin();
	require_once 'admin_lib.php';
	require_once 'lib/paysys.php';

	
	if (isset($route[1])) 
	{
		try 
		{
			@$oper_id = $route[1];
			$good_msg = withdrawal_later ($oper_id);
		} 
		catch (Exception $error)
		{
			$bad_msg =  $error->getMessage();
		}	
	
	}		
	
?>