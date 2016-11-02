<?php
	check_admin();
	require_once 'admin_lib.php';

	
	@$uid = $route[1]; 
	aka_user ($uid);
	exit;
?>