<?php
	check_admin();
	require_once 'admin_lib.php';

	
	if (isset ($_REQUEST['make_news']))
    {	
		make_news (@$_REQUEST['topic_news'], @$_REQUEST['ts_news'], @$_REQUEST['announce_news'], @$_REQUEST['text_news'], @$_REQUEST['show_begin_news'], @$_REQUEST['show_end_news']);
	}
	
?>