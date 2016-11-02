<?php
	check_admin();
	require_once 'admin_lib.php';

	@$object_type = $route[1]; 
	@$object_id   = $route[2]; 
	
	delete_object ($object_type, $object_id);

?>