<?php
	check_admin();
	require_once 'admin_lib.php';

	
	if (isset ($_REQUEST['make_page']))
    {	
		make_page (@$_REQUEST['hidden_page'], @$_REQUEST['topic_page'], @$_REQUEST['text_page']);
	}
	
?>