<?php
	check_admin();
	require_once 'admin_lib.php';

	@$nid = $route[1];
	
	if (isset ($_REQUEST['save_news']))
    {	
		edit_news ($nid, @$_REQUEST['topic_news'], @$_REQUEST['ts_news'], @$_REQUEST['announce_news'], @$_REQUEST['text_news'], @$_REQUEST['show_begin_news'], @$_REQUEST['show_end_news']);
	}
		
	
	$news = get_news($nid);
?>