<?php
	check_user();
		
	//$user = user_info($_SESSION['UID']);
	$operations = user_operations($_SESSION['UID'], 'REF.');
	
	
?>