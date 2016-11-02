<?php
	check_admin();
	require_once 'admin_lib.php';
	
	// Если переход со страницы редактирования пользователя, то показываем все операции этого пользователя
	if (preg_match("/edit-user/", $_SERVER['HTTP_REFERER']))
	{
		$user = explode('/', $_SERVER['HTTP_REFERER']);
		@$uid = $user[4]; 	
		$_SESSION['FILTER_OPER']['oper_login'] = get_login($uid);
	}
	
	filter();
	$operations = all_operations ();

?>