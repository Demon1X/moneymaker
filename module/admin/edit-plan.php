<?php
	check_admin();
	require_once 'admin_lib.php';

	@$pid = $route[1];
	if (isset ($_REQUEST['save']))
    {	
		edit_plan ();
	}
	
	$plan = get_plan($pid);
?>