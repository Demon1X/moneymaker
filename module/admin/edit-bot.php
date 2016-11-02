<?php
	check_admin();
	require_once 'admin_lib.php';
	require_once 'lib/paysys.php';

	
	@$uid = $route[1]; 	
	if (isset($_REQUEST['savebot']))
    {	
		$good_msg = edit_bot($uid);
		header("Location: /show-bots");
		exit;
	}
	
	$bot = get_bot($uid);

	$paysys = all_paysys ();
	$plans  = all_plans ();
?>