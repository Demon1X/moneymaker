<?php
	check_admin();
	require_once 'admin_lib.php';

	@$pid = $route[1];
	if (isset ($_REQUEST['save']) AND @$_REQUEST['topic_page'] != '' AND @$_REQUEST['text_page'] != '')
    {	
		edit_page (@$pid, @$_REQUEST['hidden_page'], @$_REQUEST['topic_page'], @$_REQUEST['text_page']);
	}
	
	
	$page = get_page($pid);

	
	
?>