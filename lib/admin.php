<?php
// Возвращает массив со всеми пользователями в системе
function all_users ()
{
	check_token ();
	$link   = db_open();
	
	// login
	if (@$_SESSION['FILTER']['login'] != '')
		$login = asql ($link, $_SESSION['FILTER']['login']);
	else
		$login = '%';
	
	// group
	if (@$_SESSION['FILTER']['group'] != '')
		$group = asql ($link, $_SESSION['FILTER']['group']);
	else
		$group = '%';
		
	// email
	if (@$_SESSION['FILTER']['email'] != '')
		$email = asql ($link, $_SESSION['FILTER']['email']);
	else
		$email = '%';

	// status
	if (@$_SESSION['FILTER']['status'] != '')
		$status = asql ($link, $_SESSION['FILTER']['status']);
	else
		$status = '%';
	
	// inviter
	if (@$_SESSION['FILTER']['inviter'] != '')
		$inviter = get_id(asql ($link, $_SESSION['FILTER']['inviter']));
	else
		$inviter = '%';


	// Узнаем кол-во страниц 
	$query  = "SELECT COUNT( * ) AS COUNT FROM  `users`   WHERE `uLogin`  LIKE '$login' 
	                                                       AND  `uGroup`  LIKE '$group' 
									                       AND  `uMail`   LIKE '$email' 
									                       AND  `uStatus` LIKE '$status'
														   AND  `uRef`    LIKE '$inviter' ";
	
	$result = db_query($link, $query); 
	$count = db_fetch($result);
	$_SESSION['PAGINATION'] = $count['COUNT'];
		
	
	// Запрос
	$query  = "SELECT * FROM `users`  WHERE `uLogin`  LIKE '$login' 
	                                   AND  `uGroup`  LIKE '$group' 
									   AND  `uMail`   LIKE '$email' 
									   AND  `uStatus` LIKE '$status'
									   AND  `uRef`    LIKE '$inviter'
									   ORDER BY `uID` DESC ";

 
	$limit = 1;
	if (isset($GLOBALS['route'][1])) $limit = intval ($GLOBALS['route'][1]);
	
	$limit = $limit - 1;
	$limit = $limit * 20;
	$query .= "LIMIT $limit, 20 "; 

	
	$result = db_query($link, $query); 
	
	$users = array();
	while ($user = db_fetch($result)) $users[] = $user;
	
	db_close($link);
	return $users;
}


// Создание нового пользователя
function make_user ($login, $pass1, $pass2, $email, $fake = 0)
{
	check_token ();
	valid_signup ($login, $pass1, $pass2, $email);
	
	// Создаем нового пользователя
	$link  = db_open();
	
	$login = asql($link, $login);
	$pass1 = asql($link, md5($pass1));
	$email = asql($link, $email);
	$fake  = asql($link, $fake);
	$ip    = asql($link, $_SERVER['REMOTE_ADDR']);
	
	$query = "INSERT INTO  users  (uID, uGroup, uLogin, uPass, uMail, uLevel, uIP, uRegTime, uBan, uGMT, uStatus, uLastVisit, uFake) 
	                       VALUES (NULL, '', '$login', '$pass1', '$email', '1', '$ip', '".time()."', '', '0', 'active', '".time()."', '$fake'); ";
	
	db_query($link, $query); 
	db_close($link);
	
	// Деламем переход на список всех пользователей или ботов
    if ($fake) 
		header ("Location: show-bots");	
	else	
		header ("Location: show-users");	
}


// Редактирование уже существующего пользователя
function edit_user ($uid, $group, $login, $email, $pass, $level, $inviter, $status)
{
	check_token ();
	$link  = db_open();
	
	$uid    = asql ($link, $uid); 
	$group  = asql ($link, $group); 
	$login  = asql ($link, $login); 
	$email  = asql ($link, $email); 
	$level  = asql ($link, $level); 
	$status = asql ($link, $status);
	$inviter = get_id ($inviter);
	$inviter = asql ($link, $inviter);
	
	if ($pass!='')
	{		
		$pass = asql($link, md5($pass));
		$query = "UPDATE users SET uLogin = '$login', 
		                           uGroup = '$group', 
								   uMail = '$email', 
								   uPass = '$pass', 
								   uLevel = '$level', 
								   uRef = '$inviter', 
								   uStatus = '$status' 
								   WHERE uID = '$uid'
								   ";	
	}		
	else
	{	
		$query = "UPDATE users SET uLogin = '$login', 
		                           uGroup = '$group', 
								   uMail = '$email', 
								   uLevel =' $level', 
								   uRef = '$inviter', 
								   uStatus = '$status' 
								   WHERE uID = '$uid'
								   ";
	}
		
	db_query($link, $query); 
	db_close($link);
	
	return "Saved";
}


// Вход под другим пользователем
function aka_user ($uid)
{
	$_SESSION['AUTH']=1;
	$_SESSION['UID']=$uid;
	$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
	
	header ("Location: /".DEFAULT_CABINET);
	exit;
}


// Возвращает массив со всеми операциями
function all_operations () 
{
	check_token ();
	$link = db_open();

	// login
	if (@$_SESSION['FILTER_OPER']['oper_login'] != '')
		$login = asql ($link, get_id($_SESSION['FILTER_OPER']['oper_login']));
	else
		$login = '%';
	
	// currency
	if (@$_SESSION['FILTER_OPER']['oper_currency'] != '')
		$currency = asql ($link, $_SESSION['FILTER_OPER']['oper_currency']);
	else
		$currency = '%';
		
	// operation
	if (@$_SESSION['FILTER_OPER']['oper_operation'] != '')
		$operation = asql ($link, $_SESSION['FILTER_OPER']['oper_operation']);
	else
		$operation = '%';
	
	// status
	if (@$_SESSION['FILTER_OPER']['oper_status'] != '')
		$status = asql ($link, $_SESSION['FILTER_OPER']['oper_status']);
	else
		$status = '%';
	
	// Узнаем кол-во страниц 
	$query  = "SELECT COUNT( * ) AS COUNT FROM  `operations` WHERE `ouID`  LIKE '$login' 
	                                                         AND  `oCurr`  LIKE '$currency' 
									                         AND  `oOperation` LIKE '$operation'
															 AND  `oStatus`    LIKE '$status' ";
	
	$result = db_query($link, $query); 
	$count = db_fetch($result);
	$_SESSION['PAGINATION_OPER'] = $count['COUNT'];

	// Запрос
	$query  = "SELECT * FROM  `operations` WHERE `ouID`  LIKE '$login' 
	                                       AND  `oCurr`  LIKE '$currency' 
						                   AND  `oOperation` LIKE '$operation'
										   AND  `oStatus`    LIKE '$status' 
						  				   ORDER BY `oID` DESC ";

 
	$limit = 1;
	if (isset($GLOBALS['route'][1])) $limit = intval ($GLOBALS['route'][1]);
	
	$limit = $limit - 1;
	$limit = $limit * 20;
	$query .= "LIMIT $limit, 20 "; 
	
	$result = db_query($link, $query); 
	$operations = array ();
	while ($oper = db_fetch($result)) $operations[] = $oper;
	
	db_close($link);
	return $operations;
}


// Возвращает массив со всеми депозитами
function all_deposits () 
{
	check_token ();
	$link = db_open();

	// login
	if (@$_SESSION['FILTER_DEPO']['depo_login'] != '')
		$login = asql ($link, get_id($_SESSION['FILTER_DEPO']['depo_login']));
	else
		$login = '%';
	
	// currency
	if (@$_SESSION['FILTER_DEPO']['depo_currency'] != '')
		$currency = asql ($link, $_SESSION['FILTER_DEPO']['depo_currency']);
	else
		$currency = '%';
		
	// plan
	if (@$_SESSION['FILTER_DEPO']['depo_plan'] != '')
		$plan = asql ($link, $_SESSION['FILTER_DEPO']['depo_plan']);
	else
		$plan = '%';

	// status
	if (@$_SESSION['FILTER_DEPO']['depo_status'] != '')
		$status = asql ($link, $_SESSION['FILTER_DEPO']['depo_status']);
	else
		$status = '%';
	
	// Узнаем кол-во страниц 
	$query  = "SELECT COUNT( * ) AS COUNT FROM  `deposits` WHERE `duID`      LIKE '$login' 
	                                                       AND   `dCurrency` LIKE '$currency' 
														   AND   `dpID`      LIKE '$plan' 
														   AND   `dStatus`   LIKE '$status' ";
	
	$result = db_query($link, $query); 
	$count = db_fetch($result);
	$_SESSION['PAGINATION_DEPO'] = $count['COUNT'];

	// Запрос
	$query  = "SELECT * FROM  `deposits` WHERE `duID`     LIKE '$login' 
	                                     AND  `dCurrency` LIKE '$currency' 
										 AND  `dpID`      LIKE '$plan' 
										 AND  `dStatus`   LIKE '$status' 
						  				 ORDER BY `dID` DESC ";

 
	$limit = 1;
	if (isset($GLOBALS['route'][1])) $limit = intval ($GLOBALS['route'][1]);
	
	$limit = $limit - 1;
	$limit = $limit * 20;
	$query .= "LIMIT $limit, 20 "; 
	
	$result = db_query($link, $query); 
	$deposits = array ();
	while ($depo = db_fetch($result)) $deposits[] = $depo;
	
	db_close($link);
	return $deposits;
}


// Создать план
function make_plan ()
{
	check_token();

	$link   = db_open();
	
	$group   = asql($link, @$_REQUEST['plan_group']);
	$name    = asql($link, @$_REQUEST['plan_name']);
	$min     = asql($link, @$_REQUEST['plan_min']);
	$max     = asql($link, @$_REQUEST['plan_max']);
	$percent = asql($link, @$_REQUEST['plan_percent']);
	$period  = asql($link, @$_REQUEST['plan_poriod']);
	$cycles  = asql($link, @$_REQUEST['plan_cycles']);
	$return  = asql($link, @$_REQUEST['plan_return']);
	$rdepo   = asql($link, @$_REQUEST['plan_rdepo']);
	$rprofit = asql($link, @$_REQUEST['plan_rprofit']);
	$hidden  = asql($link, @$_REQUEST['plan_hidden']);
		
	$query = "INSERT INTO `plans` (pGroup, pName, pMin, pMax, pPercent, pPeriod, pCycles, pReturn, pRefDepo, pRefProfit, pHidden)
				  VALUES ('$group', '$name', '$min', '$max', '$percent', '$period', '$cycles', '$return', '$rdepo', '$rprofit', '$hidden') "; 
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	header('Location: show-plans');
	exit;
}


// Редактировать план
function edit_plan ()
{
	check_token();

	$link   = db_open();
	
	$pid     = asql($link, @$_REQUEST['plan_id']);
	$group   = asql($link, @$_REQUEST['plan_group']);
	$name    = asql($link, @$_REQUEST['plan_name']);
	$min     = asql($link, @$_REQUEST['plan_min']);
	$max     = asql($link, @$_REQUEST['plan_max']);
	$percent = asql($link, @$_REQUEST['plan_percent']);
	$period  = asql($link, @$_REQUEST['plan_poriod']);
	$cycles  = asql($link, @$_REQUEST['plan_cycles']);
	$return  = asql($link, @$_REQUEST['plan_return']);
	$rdepo   = asql($link, @$_REQUEST['plan_rdepo']);
	$rprofit = asql($link, @$_REQUEST['plan_rprofit']);
	$hidden  = asql($link, @$_REQUEST['plan_hidden']);
	
	$query = "UPDATE `plans` SET `pGroup` = '$group', 
  								 `pName` = '$name', 
								 `pMin` = '$min', 
								 `pMax` = '$max', 
								 `pPercent` = '$percent', 
								 `pPeriod` = '$period', 
								 `pCycles` = '$cycles', 
								 `pReturn` = '$return', 
								 `pRefDepo` = '$rdepo', 
								 `pRefProfit` = '$rprofit', 
								 `pHidden` = '$hidden'
								  WHERE `pID` = '$pid' ";
				  
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	header('Location: /show-plans');
	exit;
}


// Создать страницу
function make_page ($hidden = 0, $topic = '', $text = '')
{
	check_token();

	$link   = db_open();
	
	$hidden = asql($link, $hidden);
	$topic  = asql($link, $topic);
	$text   = asql($link, $text);
		
	$query = "INSERT INTO `pages` (pHidden, pTopic, pText)
				  VALUES ('$hidden', '$topic', '$text') "; 
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	header('Location: show-pages');
	exit;
}


// Редактирование страницы
function edit_page ($pid, $hidden, $topic, $text)
{
	check_token();

	$link   = db_open();
	
	$pid    = asql($link, $pid);
	$hidden = asql($link, $hidden);
	$topic  = asql($link, $topic);
	$text   = asql($link, $text);
	
	$query = "UPDATE `pages` SET `pHidden` = '$hidden', 
		                         `pTopic`  = '$topic', 
								 `pText`   = '$text' 
								  WHERE `pID` = '$pid' ";
				  
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	header('Location: /show-pages');
	exit;

}


// Создать новую новость
function make_news ($topic, $ts, $announce, $text, $show_begin, $show_end)
{
	check_token();

	$link   = db_open();
	
	$topic       = asql($link, $topic);
	$ts          = asql($link, $ts);
	$announce    = asql($link, $announce);
	$text        = asql($link, $text);
	$show_begin  = asql($link, $show_begin);
	$show_end    = asql($link, $show_end);
	
	$show_begin = strtotime($show_begin);	
	$show_end = strtotime($show_end);	
	
	$query = "INSERT INTO `news` (nTS, nTopic, nAnnounce, nText, nShowBegin, nShowEnd)
				  VALUES ('$ts', '$topic', '$announce', '$text', '$show_begin', '$show_end') "; 
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	header('Location: show-news');
	exit;
}


// Редактирование новости
function edit_news ($nid, $topic, $ts, $announce, $text, $show_begin, $show_end)
{
	check_token();

	$link   = db_open();
	
	$nid         = asql($link, $nid);
	$topic       = asql($link, $topic);
	$ts          = asql($link, $ts);
	$announce    = asql($link, $announce);
	$text        = asql($link, $text);
	$show_begin  = asql($link, $show_begin);
	$show_end    = asql($link, $show_end);
	
	$query = "UPDATE `news` SET `nTopic`     = '$topic', 
								`nTS`        = '$ts', 
								`nAnnounce`  = '$announce', 
								`nText`      = '$text',
								`nShowBegin` = '$show_begin',
								`nShowEnd`   = '$show_end'
								 WHERE `nID` = '$nid' ";
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	header('Location: /show-news');
	exit;
}


// Отправка письма с админки
function admin_to_send ($sender, $sender_email, $recipients, $subject, $msg)
{
	check_token ();
	valid_admin_to_send ($sender, $sender_email, $recipients, $subject, $msg);
	
	
	$recipients = explode("\n", $recipients);
	
	// Заголовки
	$subject = "=?utf-8?B?". base64_encode($subject). "?=";
	$headers = 'From: '.$sender_email."\n";	
	$headers .= 'Content-type: text/html; charset="utf-8"'."\n";	
		
	
	foreach ($recipients as $email) 
	{
		$email = trim ($email);
		
		if ($email == '#all#') 
		{
			$users = all_users();
			foreach ($users as $user) mail ($user['uMail'], $subject, $msg, $headers);
			continue;
		}
		
		if ($email == '#spam#') 
		{	
			$spam = file_get_contents ('lib/email.txt');
			$spam = explode("\n", $spam);
	
			foreach ($spam as $email) mail ($email, $subject, $msg, $headers);
			continue;
		}
		
		mail ($email, $subject, $msg, $headers);	
	}
	
	
	
	
	return 'E-mail to send';
}


// Валидация полей, отправки письма из админки
function valid_admin_to_send ($sender, $sender_email, $recipients, $subject, $msg)
{
	if ($sender == '') throw new Exception ('Enter name sender');
	if ($sender_email == '') throw new Exception ('Please type your e-mail');
	if (!preg_match('/^([_a-z0-9\.]+)@([_a-z0-9\.]+)$/is', $sender_email)) throw new Exception ('Sender E-mail incorrect');

	if ($recipients == '') throw new Exception ('Please type e-mails recipients');
		
	if ($subject == '') throw new Exception ('Enter your subject');
	if ($msg == '') throw new Exception ('Enter your message');

}


// Фн. возвращает e-mail пользователя, в форму отправки письма
function send_user () 
{
	if (isset($_REQUEST['route'])) 
	{		
		$route = $_REQUEST['route'];
		$route = rtrim($route, '/');
		$route = explode('/', $route);
				
		if (isset($route[1]))
		{
			$uid = $route[1];
			$user = user_info ($uid);
			return $user['uMail'];
		}
	}		
		
		
	return "";
}


// Фн. возвращает логин пользователя, в форму отправки письма
function send_login () 
{
	if (isset($_REQUEST['route'])) 
	{		
		$route = $_REQUEST['route'];
		$route = rtrim($route, '/');
		$route = explode('/', $route);
				
		if (isset($route[1]))
		{
			$uid = $route[1]; 
			$user = user_info ($uid);
			return ": <b>".$user['uLogin']."<b>";
		}
	}		
		
		
	return "";
}


// Фн. удалЯет объект: пользователь, новость, страницу, отзыв и т.д.
function delete_object ($object_type, $object_id)
{
	$link   = db_open();
	
	$object_id = asql($link, $object_id);
		
	// Удалить новость			  
	if ($object_type == 'news')
	{		
		$query = "DELETE FROM `news` WHERE `nID`='$object_id' ";
		$result = db_query($link, $query); 
		db_close($link);	
	
		header('Location: /show-news');
		exit;
	}
	
	// Удалить страницу
	if ($object_type == 'pages')
	{		
		$query = "DELETE FROM `pages` WHERE `pID`='$object_id' ";
		$result = db_query($link, $query); 
		db_close($link);	
	
		header('Location: /show-pages');
		exit;
	}
		
	db_close($link);
	header('Location: '.$_SERVER['REQUEST_URI']);
	exit;
}


// Сохранение основных настроек
function save_settings ()
{
	check_token();

	$link   = db_open();
	
	$SITE_NAME        = asql($link, $_REQUEST['SITE_NAME']);
	$USE_HTTPS        = asql($link, $_REQUEST['USE_HTTPS']);
	$TECHNICAL_WORK   = asql($link, $_REQUEST['TECHNICAL_WORK']);
	$SIGNUP_CLOSED    = asql($link, $_REQUEST['SIGNUP_CLOSED']);
	$MIN_PASSWORD     = asql($link, $_REQUEST['MIN_PASSWORD']);
	$BUNCH_SESSION_IP = asql($link, $_REQUEST['BUNCH_SESSION_IP']);
	$SUPPORT_EMAIL    = asql($link, $_REQUEST['SUPPORT_EMAIL']);
	$DEFAULT_LANG     = asql($link, $_REQUEST['DEFAULT_LANG']);
	
	$query = "UPDATE `settings` SET `SITE_NAME`         = '$SITE_NAME', 
								    `USE_HTTPS`         = '$USE_HTTPS', 
								    `TECHNICAL_WORK`    = '$TECHNICAL_WORK', 
								    `SIGNUP_CLOSED`     = '$SIGNUP_CLOSED',
								    `MIN_PASSWORD`      = '$MIN_PASSWORD',
								    `BUNCH_SESSION_IP`  = '$BUNCH_SESSION_IP',
								    `SUPPORT_EMAIL`     = '$SUPPORT_EMAIL',
								    `DEFAULT_LANG`      = '$DEFAULT_LANG' ";
		
	$result = db_query($link, $query); 
	db_close($link);	

	
	header('Location: '.$_SERVER['REQUEST_URI']);
	exit;
	// return 'Saved';
}


// Создать бонус
function make_bonus ($curr, $login, $amount, $memo = '')
{
	check_token();

	if (!get_id($login)) 
	{
		header('Location: show-operations');
		exit;
	}

	$link   = db_open();
	
	$curr     = asql($link, $curr);
	$currency = 'uBal'.$curr;
	$login    = asql($link, $login);
	$amount   = asql($link, $amount);
	$amount   = acomma ($amount);
	$uid      = get_id ($login);
	$uid      = asql($link, $uid);
			
	$query = "UPDATE `users` SET `$currency` = `$currency` + '$amount' WHERE uID = '$uid' ";	
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	// Создаем операцию
	create_operation ($uid, 'BONUS', $curr, $amount, 3, $memo);

	header('Location: show-operations');
	exit;
}


// Сделать штраф
function make_penalty ($curr, $login, $amount, $memo = '')
{
	check_token();
	
	if (!get_id($login)) 
	{
		header('Location: show-operations');
		exit;
	}

	$link   = db_open();
	
	$curr     = asql($link, $curr);
	$currency = 'uBal'.$curr;
	$login    = asql($link, $login);
	$amount   = asql($link, $amount);
	$amount   = acomma ($amount);
	$uid      = get_id ($login);
	$uid      = asql($link, $uid);
			
	$query = "UPDATE `users` SET `$currency` = `$currency` - '$amount' WHERE uID = '$uid' ";	
		
	$result = db_query($link, $query); 
	db_close($link);	
	
	// Создаем операцию
	create_operation ($uid, 'PENALTY', $curr, $amount, 3, $memo);

	header('Location: show-operations');
	exit;
}


// Создание нового бота
function make_bot ()
{
	$link   = db_open();
	
	$login   = asql($link, $_REQUEST['login']);
	$ps      = asql($link, $_REQUEST['paysys']);
	$plan_id = asql($link, $_REQUEST['plan']);
	$amount  = asql($link, $_REQUEST['amount']);
	
	// Создаем нового пользователя
	make_user ($login, 'qweasd666', 'qweasd666', $login.'@'.$_SERVER['SERVER_NAME'], 1);
	
	// Создаем запись с новым ботом
	$uid = get_id($login);
	$uid = asql($link, $uid);
	$query = "INSERT INTO `bots`  (uID, Curr, psID, planID, Amount) 
	                       VALUES ('$uid', 'USD', '$ps', '$plan_id', '$amount'); ";

    db_query($link, $query); 
	db_close($link);	
	
	return "Make new Bot!";
}


// Фн. редактировать бота
function edit_bot ($uid)
{
	$link = db_open();
	
	$uid      = asql($link, $uid);
	$ps       = asql($link, $_REQUEST['paysys']);
	$plan_id  = asql($link, $_REQUEST['plan']);
	$amount   = asql($link, $_REQUEST['amount']);

	if (isset($_REQUEST['disabled'])) 
		$disabled = asql($link, $_REQUEST['disabled']);
	else
		$disabled = 0;
	
	$query = "UPDATE `bots` SET psID = '$ps', 
		                           planID = '$plan_id', 
								   Amount = '$amount', 
								   Disabled = '$disabled' 
								   WHERE uID = '$uid'
								   ";	


	db_query($link, $query); 
	db_close($link);
	return "Save";	
}


// Фн. возвращает true если гзер бот
function is_bot ($uid)
{
	$link = db_open();
	$uid  = asql($link, $uid);
	
	$query  = "SELECT count(*) FROM users WHERE uid='$uid' AND `uFake`='1' ";
	$result = db_query ($link, $query);
	$check  = db_fetch ($result);
	db_close($link);


	if ($check)
		return true;
	else
		return false;
}


?>