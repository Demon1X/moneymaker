<?php
	check_admin();
	require_once 'admin_lib.php';	
	
	if (isset ($_REQUEST['make_bonus']))
    {	
		make_bonus (@$_REQUEST['curr'], @$_REQUEST['login'], @$_REQUEST['amount'], @$_REQUEST['memo']);
	}
	
?>