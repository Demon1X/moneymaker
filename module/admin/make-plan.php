<?php
	check_admin();
	require_once 'admin_lib.php';
	
	if (isset ($_REQUEST['make_plan']))
    {	
		make_plan ();
	}

	
?>