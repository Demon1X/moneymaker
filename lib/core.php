<?php
// Подклчение к БД
function db_open ()
{
	$link = mysqli_connect (DB_HOST, DB_USER, DB_PASS, DB_NAME) or die ("Error: ".mysqli_connect_error());
	mysqli_set_charset ($link, "utf8");
	return $link;
}


// Закрытие БД
function db_close ($link)
{
	mysqli_close ($link);
}


// Запрос в БД
function db_query ($link, $query)
{
	$result = mysqli_query ($link, $query) or die ("Error: ".mysqli_error($link)); 
	return $result;
}


// Получить одну запись из результата запроса
function db_fetch ($result)
{
	$row = mysqli_fetch_assoc ($result);
	return $row;
}


// Anti SQL-inject
function asql ($link, $str)
{
	$str = mysqli_real_escape_string ($link, $str);
	return $str;
}


// Anti XSS
function axss ($str)
{
	$str = htmlspecialchars ($str);
	return $str;
}


// Заменяет в строке все запятые на точки
function acomma ($str)
{
	$str = str_replace(',','.',$str);
	return $str;
}


// Установка глобальных настроек
function install_settings ()
{
	// Берем настройки из БД
	$link  = db_open();
	
	$query    = "SELECT * FROM `settings` ";	
	$result   = db_query($link, $query); 
	$settings = db_fetch ($result);
	
	db_close ($link);	
	
	// Имя сайта
	define ('SITE_NAME', $settings['SITE_NAME']); 
	
	// Использовать HTTPS
	define('USE_HTTPS', $settings['USE_HTTPS']);  

	// Технические работы
	define('TECHNICAL_WORK', $settings['TECHNICAL_WORK']);
	
	// Регистрация новых пользователей закрыта
	define('SIGNUP_CLOSED', $settings['SIGNUP_CLOSED']);
	
	// Минимальная длина пароля
	define('MIN_PASSWORD', $settings['MIN_PASSWORD']);
	
	// Привязка сиссий к IP
	define('BUNCH_SESSION_IP', $settings['BUNCH_SESSION_IP']);
	
	// E-mail формы обратной связи
	define('SUPPORT_EMAIL', $settings['SUPPORT_EMAIL']);
	
	// Язык по умолчанию
	define ('DEFAULT_LANG', $settings['DEFAULT_LANG']);
	
	// Время последнего запуска CRON
	define ('CRON', $settings['CRON']);
}	


// Установка токена, для защиты от CSRF
function set_token ()
{	
	$token = md5(uniqid(mt_rand() . microtime()));
	$_SESSION['token'] = $token;
	return $token;
}


// Проверка существование токена
function check_token ()
{
	@$token = $_REQUEST['token'];
	if($_SESSION['token'] != $token) 
	{
		$_SESSION['token'] = '';
		header('Location: '.$_SERVER['REQUEST_URI']);
		exit;
	} 
}


// Редирект на https
function go_https ()
{
	if ($_SERVER['SERVER_PORT'] != '443') header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	// exit;
}


// Определяем по браузеру язык пользователя.
function lang ()
{
	// Если в сессии уже стоит язык, и такой языковой файл есть, тогда подключаем его и выходим из фн.
	if (isset ($_SESSION['LANG']) AND file_exists('lib/lang/'.$_SESSION['LANG'].'.php'))
	{	
		return 'lib/lang/'.$_SESSION['LANG'].'.php';
	}
	
	
	// Если в сессии не стоит язык, то смотрим по браузеру пользователя, и проверяем наличие такого языковой файла, если есть тогда подключаем его и выходим из фн.
	if (isset ($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND file_exists('lib/lang/'.substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2).'.php'))
	{	
		$_SESSION['LANG'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		return 'lib/lang/'.substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2).'.php';
	}
	
	
	// Если не один из вариатов не прошел, то по умолчанию выставляем DEFAULT_LANG и подключаем его
	$_SESSION['LANG'] = DEFAULT_LANG;
	return 'lib/lang/'.DEFAULT_LANG.'.php';
}


// Установка нужного языка
function set_lang ()
{
	@ $lang = $_REQUEST['route'];
	@ $lang = rtrim($lang, '/');
	@ $lang = explode('/', $lang);
	@ $lang = $lang[1];
	$_SESSION['LANG'] = $lang;

	
	if (!isset($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER']= '/';
	header ("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}


// Создание пользователя при регистрации
function signup ($login, $pass1, $pass2, $email, $inviter)
{
	if (SIGNUP_CLOSED) throw new Exception ("Sign up closed");
	valid_signup ($login, $pass1, $pass2, $email);
	
	// Создаем нового пользователя
	$link  = db_open();
	
	$login   = asql($link, $login);
	$pass1   = asql($link, md5($pass1));
	$email   = asql($link, $email);
	$inviter = asql($link, $inviter);
	$ip      = asql($link, $_SERVER['REMOTE_ADDR']);
	
	$query = "INSERT INTO  users  (uID, uGroup, uLogin, uPass, uMail, uLevel, uIP, uRegTime, uBan, uGMT, uStatus, uLastVisit, uRef) 
	                       VALUES (NULL, '', '$login', '$pass1', '$email', '1', '$ip', '".time()."', '', '0', 'active', '".time()."', '$inviter') ";
	
	db_query($link, $query); 
	

	// Деламем переход в кабинет, после регисртации пользователя
    $query = "SELECT uID FROM users WHERE uLogin='$login'";	
	$result = db_query($link, $query); 
	$user = db_fetch ($result);
	
	$_SESSION['AUTH']=1;
	$_SESSION['UID']=$user['uID'];
	$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
	
	header ("Location: ".DEFAULT_CABINET);
	exit;
	
	db_close($link);
}


// Проверка корректности введенных в форме данных для регистрации нового пользователя
function valid_signup ($login, $pass1, $pass2, $email)
{
	if ($login == '') throw new Exception ('Please type your login');
	if (!preg_match('/^[a-z0-9]+$/s', $login)) throw new Exception ('Login incorrect format');
	if ($email == '') throw new Exception ('Please type your e-mail');
	if (!preg_match('/^([_a-z0-9\.\-]+)@([_a-z0-9\.\-]+)$/is', $email)) throw new Exception ('E-mail incorrect');
	if ($pass1 == '') throw new Exception ('Please type your password');
	if ($pass2 == '') throw new Exception ('Please repeat type your password');
	if ($pass1 != $pass2) throw new Exception ('Password and repeat password not equal');
	if (strlen($pass1) < MIN_PASSWORD)  throw new Exception ('Minimum password length of ');
	
	$link = db_open();
	
	// Проверка есть ли уже пользователь с таким же логином
	$login  = asql($link, $login);
	$query  = "SELECT COUNT(*) AS 'COUNT' FROM users WHERE uLogin='$login'";
	
	$result = db_query ($link, $query);
	$result = db_fetch($result);
	
	$result = $result['COUNT'];
	if ($result != 0) throw new Exception ('This login is already used');
	
	// Проверка есть ли уже пользователь с таким же почтовым ящиком
	$email  = asql($link, $email);
	$query  = "SELECT COUNT(*) AS 'COUNT' FROM users WHERE uMail='$email' ";
	
	$result = db_query ($link, $query);
	$result = db_fetch($result);
	
	$result = $result['COUNT'];
	if ($result != 0) throw new Exception ('This email is already used');

	db_close($link);	
}


// Фн. Вход пользователя
function login ($login, $pass)
{
	check_token();
	
	if ($login == '') throw new Exception ('Please type your login');
	if ($pass == '')  throw new Exception ('Please type your password');

	
	$link = db_open();
	
	$login  = asql($link, $login);
	$pass   = asql($link, md5($pass));
	
	$query  = "SELECT *,  COUNT(*) AS 'COUNT' FROM users WHERE BINARY uLogin='$login' AND uPass='$pass' OR uMail='$login' AND uPass='$pass' ";
	$result = db_query ($link, $query);
	$user   = db_fetch($result);

	db_close($link);	
	
	
	$count = $user['COUNT'];
	if ($count == 0) throw new Exception ('Login or password incorrect');
	if ($user['uStatus'] == 'inactive')  throw new Exception ('This account inactive');
	
	$_SESSION['AUTH'] = 1;
	$_SESSION['UID'] = $user['uID'];
	$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
		
			
	visit();			
	header ("Location: ".DEFAULT_CABINET);
	exit;
}


// Запись IP и вермение посещения кабинета пользователем
function visit()
{
	$link  = db_open();
	
	$ip   = asql($link, $_SERVER['REMOTE_ADDR']);
	$time = asql($link, time());
	$uid  = asql($link, $_SESSION['UID']);
	
	$query = "UPDATE users SET uIP='$ip', 
	                           uLastVisit='$time' 
							   WHERE uID='$uid' ";
	
	$result = db_query($link, $query); 
	db_close($link);

}


// Выход пользователя
function logout ()
{
	$_SESSION = array ();
	session_destroy();
		
	header ('Location: '.DEFAULT_MODULE);
	exit;
}


// Изменение пароля
function change_pass ($oldpass, $newpass1, $newpass2)
{
	check_token ();
	valid_change_pass($oldpass, $newpass1, $newpass2);
	
	$link = db_open();
	
	$newpass1 = asql($link, md5($newpass1));
	$uid      = asql($link, $_SESSION['UID']);
	$oldpass  = asql($link, md5($oldpass));
	
	$query = "UPDATE `users` SET `uPass`='$newpass1' WHERE uID='$uid' AND uPass='$oldpass' ";
	db_query ($link, $query);
	
	db_close($link);
	
	return 'Password successfully changed';
}


// Проверка данных формы изменения пароля
function valid_change_pass($oldpass, $newpass1, $newpass2) 
{
	if ($oldpass == '')  throw new Exception ('Please type your old password');
	if ($newpass1 == '') throw new Exception ('Please type your new password');
	if ($newpass2 == '') throw new Exception ('Please repeat your new password');
	if ($newpass1 != $newpass2) throw new Exception ('Password and repeat password not equal');
	if (strlen($newpass1) < MIN_PASSWORD) throw new Exception ('Minimum password length of ');
	
	$link = db_open();
	
	$uid      = asql($link, $_SESSION['UID']);
	$oldpass  = asql($link, md5($oldpass));

	$query  = "SELECT COUNT(*) AS 'COUNT' FROM users WHERE uID='$uid' AND uPass='$oldpass' ";
	$result = db_query ($link, $query);
	
	$result = db_fetch($result);
	$result = $result['COUNT'];
	
	db_close($link);	
	if ($result == 0) throw new Exception ('Old password incorrect');
}


// Востановления пароля, если пользователь забыл
function reset_pass ($email)
{
	check_token ();
	valid_reset_pass ($email);
	
	$newpass = '';
	for ($i = 0; $i<10; $i++) $newpass = $newpass.''.mt_rand ( 0, 9);
	
	
	$link    = db_open();
	$email   = asql($link, $email);
	
	$query = "UPDATE `users` SET `uPass`='".md5($newpass)."' WHERE uMail='$email' ";
	db_query ($link, $query);
	db_close($link);
	
	
	mail ($email, 'New password', $newpass, 'From: '.SUPPORT_EMAIL.'\n');
	
	return 'Password successfully reset';
}


// Проверка данных формы востановления пароля
function valid_reset_pass ($email)
{
	$link  = db_open();
	$email = asql($link, $email);
	
	$query  = "SELECT COUNT(*) AS 'COUNT' FROM users WHERE uMail='$email' ";
	$result = db_query ($link, $query);
	$result = db_fetch($result);
	$result = $result['COUNT'];
	
	db_close($link);	
	
	if ($result == 0) throw new Exception ('No such e-mail');
}


// Отправка письма с формы обратной связи
function to_send ($email, $subject, $msg)
{
	check_token ();
	valid_send ($email, $subject, $msg);
	
	$subject = "=?utf-8?B?". base64_encode($subject). "?=";
	
	$login = "";
	if (isset($_SESSION['UID'])) $login = get_login($_SESSION['UID']);
	
	$msg = $login."\n\n".$msg;
	
	$headers = 'From: '.$email."\n";	
	$headers .= 'Content-type: text/html; charset="utf-8"'."\n";	
		
	mail (SUPPORT_EMAIL, $subject, $msg, $headers);
	
	return 'E-mail to send';
}


// Проверка корректности введеных данных в форме обратной связи
function valid_send ($email, $subject, $msg)
{
	if ($email == '') throw new Exception ('Please type your e-mail');
	if (!preg_match('/^([_a-z0-9\.]+)@([_a-z0-9\.]+)$/is', $email)) throw new Exception ('E-mail incorrect');
	if ($subject == '') throw new Exception ('Enter your subject');
	if ($msg == '') throw new Exception ('Enter your message');
}


// Проверка является ли пользователь авторизованным на сайте
function check_user()
{
	if (!isset($_SESSION['AUTH']))
	{
		header ('Location: login');
		exit;
	}
		
	if (BUNCH_SESSION_IP AND $_SESSION['IP'] != $_SERVER['REMOTE_ADDR']) logout();
}


// Проверка является ли пользователь админом
function check_admin()
{
	if (!isset($_SESSION['AUTH']))
	{
		header ('Location: '.DEFAULT_MODULE);
		exit;
	}
	
	if (BUNCH_SESSION_IP AND $_SESSION['IP'] != $_SERVER['REMOTE_ADDR']) logout();
	
	
	$link = db_open();
	$uid  = asql($link, $_SESSION['UID']);
	
	$query  = "SELECT * FROM users WHERE uID='$uid' AND uLevel='99'";
	$result = db_query($link, $query); 
		
	if (!db_fetch($result)) 
	{	
		header ('Location: '.DEFAULT_MODULE);
		exit;
	}
	
	db_close($link);
}


// Фн. возвращает логин по UID
function get_login ($uid)
{
	$link = db_open();
	$uid  = asql($link, $uid);
	
	$query  = "SELECT uLogin FROM users WHERE uID='$uid' ";
	$result = db_query ($link, $query);
	$login  = db_fetch ($result);
	$login  = $login['uLogin'];
	
	db_close($link);
	return $login;
}


// Фн. возвращает логин по UID из сессии пользователя
function get_my_login ()
{
	$link = db_open();
	$uid  = asql($link, $_SESSION['UID']);
	
	$query  = "SELECT uLogin FROM users WHERE uID='$uid' ";
	$result = db_query ($link, $query);
	$login  = db_fetch ($result);
	$login  = $login['uLogin'];
	
	db_close($link);
	return $login;
}


// Фн. возвращает логин аплайна по UID из сессии
function get_my_login_inviter ()
{
	$link = db_open();
	$uid  = $_SESSION['INVITER'];
	
	$query  = "SELECT uLogin FROM users WHERE uID='$uid' ";
	$result = db_query ($link, $query);
	$login  = db_fetch ($result);
	$login  = $login['uLogin'];
	
	db_close($link);
	return $login;
}


// Фн. возвращает uid по логину
function get_id ($login)
{
	$link  = db_open();
	$login = asql($link, $login);
	
	$query  = "SELECT `uID` FROM users WHERE `uLogin`='$login' ";
	$result = db_query ($link, $query);
	$uid  = db_fetch ($result);
	$uid  = $uid['uID'];
	
	db_close($link);
	return $uid;
}


// Фн. возвращает oper по oID
function get_oper ($oid)
{
	$link  = db_open();
	$oid   = asql($link, $oid);
	
	$query  = "SELECT * FROM `operations` WHERE `oID`='$oid' ";
	$result = db_query ($link, $query);
	$oper   = db_fetch ($result);
		
	db_close($link);
	return $oper;
}


// Фн. возвращает e-mail по UID из сессии
function get_mail ()
{
	$link = db_open();
	$uid  = asql($link, $_SESSION['UID']);
	
	$query  = "SELECT uMail FROM users WHERE uID='$uid' ";
	$result = db_query ($link, $query);
	$mail  = db_fetch ($result);
	$mail  = $mail['uMail'];
	
	db_close($link);
	return $mail;
}


// Если пользователь авторизован фн. возвращает истину, инача ложь
function is_user ()
{
	if (isset($_SESSION['AUTH'])) 
		return true;
	else
		return false;
}


// Если пользователь является админом фн. возвращает истину, инача ложь
function is_admin ()
{
	if (!isset($_SESSION['AUTH'])) return false;
	
	$link = db_open();
	$uid  = asql($link, $_SESSION['UID']);
	
	$query  = "SELECT * FROM users WHERE uID='$uid' AND uLevel='99'";
	$result = db_query($link, $query); 
	
	if (!db_fetch($result)) return false;
	
	db_close($link);
	return true;
}


// Возвращает массив со всей инфой о пользователе
function user_info ($uid)
{
	$link = db_open();
	$uid  = asql($link, $uid);
	
	$query  = "SELECT * FROM users WHERE uID='$uid' ";
	$result = db_query($link, $query); 
	
	$user = db_fetch($result);
	db_close($link);
	
	return $user;
}


// Фн. возвращает логин по UID
function user_login ($uid)
{
	$link = db_open();
	$uid  = asql($link, $uid);
	
	$query  = "SELECT uLogin FROM users WHERE uID='$uid' ";
	$result = db_query ($link, $query);
	$login  = db_fetch ($result);
	$login  = $login['uLogin'];
	
	db_close($link);
	return $login;
}


// Фн. возвращает план по pID
function plan_name ($pid)
{
	$link = db_open();
	$pid  = asql($link, $pid);
	
	$query  = "SELECT pName FROM plans WHERE pID='$pid' ";
	$result = db_query ($link, $query);
	$name  = db_fetch ($result);
	$name  = $name['pName'];
	
	db_close($link);
	return $name;
}


// Возвращает массив со всеми страницами
function all_pages ()
{
	$link   = db_open();
	
	$query  = "SELECT * FROM `pages` ORDER BY pID DESC";
	$result = db_query($link, $query); 
	
	$pages = array();
	while ($page = db_fetch($result)) $pages[] = $page;
	
	db_close($link);
	return $pages;
}


// Возвращает массив со всеми новостями
function all_news ()
{
	$link   = db_open();
	
	$query  = "SELECT * FROM `news` ORDER BY nID DESC";
	$result = db_query($link, $query); 
	
	$news = array();
	while ($novelty = db_fetch($result)) $news[] = $novelty;
	
	
	db_close($link);
	return $news;
}


// Возвращает массив со страницей, если страница скрыта выбрасывает на главную
function page ($pid)
{
	$link = db_open();
	$pid  = asql($link, $pid);
	
	$query  = "SELECT * FROM pages WHERE pID='$pid' ";
	$result = db_query($link, $query); 
	
	$page = '';
	$page = db_fetch($result);
	db_close($link);
	
	if ($page['pHidden'] == 1 AND !is_admin()) 
	{
		header ('Location: /'.DEFAULT_MODULE);
		exit;
	}	
	
	return $page;
}


// Возвращает массив с планом
function get_plan ($pid)
{
	$link = db_open();
	$pid  = asql($link, $pid);
	
	$query  = "SELECT * FROM plans WHERE pID='$pid' ";
	$result = db_query($link, $query); 
	
	$plan = '';
	$plan = db_fetch($result);
	db_close($link);
	
	return $plan;
}


// Возвращает массив со страницей
function get_page ($pid)
{
	$link = db_open();
	$pid  = asql($link, $pid);
	
	$query  = "SELECT * FROM pages WHERE pID='$pid' ";
	$result = db_query($link, $query); 
	
	$page = '';
	$page = db_fetch($result);
	db_close($link);
	
	return $page;
}


// Возвращает массив с новостью
function get_news ($nid)
{
	$link = db_open();
	$nid  = asql($link, $nid);
	
	$query  = "SELECT * FROM news WHERE nID='$nid' ";
	$result = db_query($link, $query); 
	
	$news = '';
	$news = db_fetch($result);
	db_close($link);
	
	return $news;
}


// Возврат div'a с сообщением
function msg()
{
	if (isset($GLOBALS['good_msg'])) return "<div class='good_msg'>".$GLOBALS['good_msg']."</div>";
	if (isset($GLOBALS['bad_msg'])) return "<div class='bad_msg'>".$GLOBALS['bad_msg']."</div>";
	if (isset($GLOBALS['notice_msg'])) return "<div class='notice_msg'>".$GLOBALS['notice_msg']."</div>";
}


//Изменение настроек пользователя
function set_settings ($gmt)
{
	check_token ();
	
	$link   = db_open();
		
	$gmt = asql ($link, $gmt);
	$uid = asql ($link, $_SESSION['UID']);
	
	$query = "UPDATE `users` SET `uGMT`='$gmt' WHERE uID='$uid' ";
	$result = db_query($link, $query); 
		
	db_close($link);
	
	return 'Saved';
}


//Изменение кошельков
function edit_wallets ($perfect = '', $payeer = '')
{
	check_token ();
	
	$link   = db_open();
		
	$perfect = asql ($link, $perfect);
	$payeer  = asql ($link, $payeer);
	$uid     = asql ($link, $_SESSION['UID']);
	
	// Проверка на корректность формата кошелька
	if ($perfect != '' AND !preg_match("/^U[0-9]{7,9}+$/", $perfect)) throw new Exception ('Wrong format');
	if ($payeer  != '' AND !preg_match("/^P[0-9]{7,9}+$/", $payeer))  throw new Exception ('Wrong format');
	
	$query = "UPDATE `users` SET `uPerfectMoney`='$perfect', `uPayeer`='$payeer' WHERE uID='$uid' ";
	$result = db_query($link, $query); 
		
	db_close($link);
	
	return 'Saved';
}


// Заносим в сессию параметры фильтрации данных
function filter()
{
	// Очистка фильтра пользователей
	if (isset($_REQUEST['clear_filter']))
	{
		unset($_SESSION['FILTER']);
		return 0;
	}	
	
	// login
	if (isset($_REQUEST['login'])) $_SESSION['FILTER']['login'] = $_REQUEST['login'];
	if (!isset($_SESSION['FILTER']['login'])) $_SESSION['FILTER']['login'] = '';
	
	// group
	if (isset($_REQUEST['group'])) $_SESSION['FILTER']['group'] = @$_REQUEST['group'];
	if (!isset($_SESSION['FILTER']['group'])) $_SESSION['FILTER']['group'] = '';
	
	// email
	if (isset($_REQUEST['email'])) $_SESSION['FILTER']['email'] = @$_REQUEST['email'];
	if (!isset($_SESSION['FILTER']['email'])) $_SESSION['FILTER']['email'] = '';
	
	// status
	if (isset($_REQUEST['status'])) $_SESSION['FILTER']['status'] = @$_REQUEST['status'];
	if (!isset($_SESSION['FILTER']['status'])) $_SESSION['FILTER']['status'] = '';
	
	// inviter
	if (isset($_REQUEST['inviter'])) $_SESSION['FILTER']['inviter'] = @$_REQUEST['inviter'];
	if (!isset($_SESSION['FILTER']['inviter'])) $_SESSION['FILTER']['inviter'] = '';

	
	// -------------------------------------------------------------------------------------------------------------------
	// Очистка фильтра операций
	if (isset($_REQUEST['clear_filter_oper']))
	{
		unset($_SESSION['FILTER_OPER']);
		return 0;
	}	
	
	// login
	if (isset($_REQUEST['oper_login'])) $_SESSION['FILTER_OPER']['oper_login'] = @$_REQUEST['oper_login'];
	if (!isset($_SESSION['FILTER_OPER']['oper_login'])) $_SESSION['FILTER_OPER']['oper_login'] = '';
	
	// currency
	if (isset($_REQUEST['oper_currency'])) $_SESSION['FILTER_OPER']['oper_currency'] = @$_REQUEST['oper_currency'];
	if (!isset($_SESSION['FILTER_OPER']['oper_currency'])) $_SESSION['FILTER_OPER']['oper_currency'] = '';

	// operation
	if (isset($_REQUEST['oper_operation'])) $_SESSION['FILTER_OPER']['oper_operation'] = @$_REQUEST['oper_operation'];
	if (!isset($_SESSION['FILTER_OPER']['oper_operation'])) $_SESSION['FILTER_OPER']['oper_operation'] = '';
	
	// status
	if (isset($_REQUEST['oper_status'])) $_SESSION['FILTER_OPER']['oper_status'] = @$_REQUEST['oper_status'];
	if (!isset($_SESSION['FILTER_OPER']['oper_status'])) $_SESSION['FILTER_OPER']['oper_status'] = '';

	
	// -------------------------------------------------------------------------------------------------------------------
	// Очистка фильтра депозитов
	if (isset($_REQUEST['clear_filter_depo']))
	{
		unset($_SESSION['FILTER_DEPO']);
		return 0;
	}	
	
	// login
	if (isset($_REQUEST['depo_login'])) $_SESSION['FILTER_DEPO']['depo_login'] = @$_REQUEST['depo_login'];
	if (!isset($_SESSION['FILTER_DEPO']['depo_login'])) $_SESSION['FILTER_DEPO']['depo_login'] = '';
	
	// currency
	if (isset($_REQUEST['depo_currency'])) $_SESSION['FILTER_DEPO']['depo_currency'] = @$_REQUEST['depo_currency'];
	if (!isset($_SESSION['FILTER_DEPO']['depo_currency'])) $_SESSION['FILTER_DEPO']['depo_currency'] = '';
	
	// plan
	if (isset($_REQUEST['depo_plan'])) $_SESSION['FILTER_DEPO']['depo_plan'] = @$_REQUEST['depo_plan'];
	if (!isset($_SESSION['FILTER_DEPO']['depo_plan'])) $_SESSION['FILTER_DEPO']['depo_plan'] = '';

	// status
	if (isset($_REQUEST['depo_status'])) $_SESSION['FILTER_DEPO']['depo_status'] = @$_REQUEST['depo_status'];
	if (!isset($_SESSION['FILTER_DEPO']['depo_status'])) $_SESSION['FILTER_DEPO']['depo_status'] = '';

}


// Постраничный вывод (ссылки)
function pagination ($page) 
{
	if ($page=='users') $count = @$_SESSION['PAGINATION'];
	if ($page=='operations') $count = @$_SESSION['PAGINATION_OPER'];
	if ($page=='deposits') $count = @$_SESSION['PAGINATION_DEPO'];
	$count = ceil ($count / 20);

	if (isset($GLOBALS['route'][1]))
		$current = $GLOBALS['route'][1];
	else
		$current = 1;
	
	$links = '';
	$route   = axss (@$GLOBALS['route'][0]);
	for ($i=1; $i<=$count; $i++) 
	{
		if ($current != $i) 
			$links.="<a href='/$route/$i'>$i</a> ";
		else
			$links.="$i ";
	}		

	return $links;	
}


// CRON фн. производит начисление по депозитам
function cron ()
{
	$link   = db_open();
		
	// Достаем все планы
	$query = "SELECT * FROM `plans` ";
	$result = db_query($link, $query); 
	
	$plans = array ();
	while ($plan = db_fetch($result)) $plans[] = $plan;

	// Достаем все активные депозиты
	$query = "SELECT * FROM `deposits` WHERE dStatus=1";
	$result = db_query($link, $query); 
	
	// И проходимся по ним
	while ($deposit = db_fetch($result))
	{
		// Ищем план соответсвующий текущему депозиту
		foreach ($plans as $plan) 
		{
			if ($plan['pID'] == $deposit['dpID'])
			{ 
				echo "<b>Plan {$plan['pName']} for Deposit #{$deposit['dID']}</b><br>";
				break;
			}				
		}
		
		
		// Производим начисление по депозиту, если это требуется
		if ( ($deposit['dEnd']+3600*$plan['pPeriod']) <= time() )
		{
			if ($plan['pNoAccrual'] != 1)
			{
				$sum = $deposit['dAmount']*($plan['pPercent']/100);
				echo "<b>ACCURAL! Amount: ".$sum." Last accural: ".date("d-m-Y H:i", $deposit['dEnd'])."</b><hr>";
								
				$uid          = asql($link, $deposit['duID']);
				$currency     = asql($link, $deposit['dCurrency']);
				$amount       = asql($link, $deposit['dAmount']);
				$user         = user_info ($uid);
				$user_balance = asql($link, $user['uBal'.$currency]);
				$user_lock    = asql($link, $user['uLock'.$currency]);
				$dID          = asql($link, $deposit['dID']);
				$worked       = intval ($deposit['dTotalWorked']) + 1;
				$time         = time();
				
				// Меняем дату последнего начисления в депозите
				$query = "UPDATE deposits SET dEnd='$time', dTotalWorked='$worked' WHERE dID='$dID' ";
				db_query($link, $query); 

				
				// Производим начисления на баланс пользователю
				$sum = $sum + $user_balance;
				$query = "UPDATE `users` SET `uBal".$currency."`='$sum' WHERE uID='$uid' ";
				db_query($link, $query); 
				
				// Создаем операцию начисления
				create_operation ($deposit['duID'], 'ACCRUAL', $deposit['dCurrency'], $deposit['dAmount']*($plan['pPercent']/100), 3, '<i>#'.$worked.'</i> '.$plan['pName']);
				
				// Производим начисление рефки
				ref_profit ($uid, $deposit['dpID'], $deposit['dAmount'], $currency);	

				
				// Закрытие депозита если он отработал положенное ему кол-во кругов 
				if ($worked >= $plan['pCycles'])
				{
					echo " CLOSE DEPOSIT #{$dID} TOTAL CUCLES";
					// Изменяем статус депозита на закрытый
					$query = "UPDATE deposits SET dStatus='0' WHERE dID='$dID' ";
					db_query($link, $query); 
					
					// Возврат депозита на баланс пользователю
					$back_amount = $amount * $plan['pReturn'] / 100 ;
					$query = "UPDATE `users` SET `uBal".$currency."`= `uBal".$currency."` + '$back_amount', 
												 `uLock".$currency."`= `uLock".$currency."` - '$amount' 
					   							  WHERE uID='$uid' ";
												  
					db_query($link, $query);
					create_operation ($uid, 'CLOSED', $currency, $deposit['dAmount'], 3, $plan['pName']);			
				}
			}	
			else
			{	
				echo "<b>Not accural, plan close for this deposit!</b><hr>";
			}				
		}
		else
		{
			echo "<b>NOT need accural! Last time ".date("d-m-y H:i",$deposit['dEnd'])."</b><hr>";
		}	
		
		echo "<pre>";
		print_r ($deposit);
		echo "</pre><hr>";	
	}	
	
	echo "<b>Current time ".date("d-m-y H:i",time())."</b> ";
	db_close($link);
	
	// Вызываем ботов
	live_bots();
	
	// Записываем время последнего запуска крона
	cron_last_run () ;
}

// Фн. записывает время последнего запуска крона
function cron_last_run () 
{
	$link = db_open();
	
	$query = "UPDATE `settings` SET `CRON` = ".time();
	db_query($link, $query);
	
	db_close($link);
}


// Начисление рефки с прибыли реферала
function ref_profit ($uid, $pid, $amount, $curr = 'USD')
{
	$link = db_open();	
	
	$uid      = asql($link, $uid);
	$pid      = asql($link, $pid);
	$amount   = asql($link, $amount);
	$currency = $curr;
	$curr     = asql($link, $curr);
	$curr     = 'uBal'.$curr;
	
	
	$user = user_info ($uid);
	$uid = $user['uRef'];
	$ref_user = user_info ($uid);
	$ref_bal = $ref_user[$curr];
	$plan = plan_info ($pid);
	
	// Если у пользователя нет реферала, то просто выходим
	if (!$ref_user) return 0;
	
	if ($plan['pRefProfit'] > 0)
	{
		$ref = $ref_bal + (($amount * $plan['pPercent'] / 100) * $plan['pRefProfit'] / 100);
				
		$query = "UPDATE users SET $curr ='$ref' 
							   WHERE uID ='$uid' ";
	
		db_query($link, $query);
		
		// Создаем операцию
		create_operation ($uid, 'REF.', $currency, (($amount * $plan['pPercent'] / 100) * $plan['pRefProfit'] / 100), 3, $user['uLogin']);	
	}
	
	db_close($link);
}


// Создать операцию
function create_operation ($uid, $type, $currency, $sum, $status, $memo = '', $ps = 0)
{
	$link = db_open();
	
	$uid      = asql($link, $uid);
	$type     = asql($link, $type);
	$sum      = asql($link, $sum);
	$status   = asql($link, $status);
	$currency = asql($link, $currency);
	$memo     = asql($link, $memo);
	$ps       = asql($link, $ps);
	$time     = time();
	
	// Создаем новую операцию
	$query = "INSERT INTO  operations  (`oTime`, `ouID`, `oCurr`, `opsID`, `oSum`, `oOperation`, `oMemo`, `oStatus`) 
	                            VALUES ('$time', '$uid', '$currency', '$ps', '$sum', '$type', '$memo', '$status') ";
	db_query($link, $query); 
	
	$oper_id = mysqli_insert_id($link);
	db_close($link);
	return $oper_id;
}


// Изменить статус операций
function edit_operation ($oid, $status)
{
	$link = db_open();
	
	$uid    = asql($link, $uid);
	$status = asql($link, $status);
	
	db_close($link);
}


// Возвращает массив со всеми депозитами
function user_deposits ($uid = '%')
{
	$link = db_open();	
	
	$uid = asql($link, $uid);
	$query = "SELECT * FROM `deposits` WHERE `duID` LIKE '$uid'  ORDER BY `dID` DESC";
	$result = db_query($link, $query); 
	
	$deposits = array ();
	while ($deposit = db_fetch($result)) $deposits[] = $deposit;
	
	db_close($link);
	return $deposits;
}


// Возвращает массив со всеми планами
function all_plans ()
{
	$link = db_open();	
	
	$query = "SELECT * FROM `plans` ";
	$result = db_query($link, $query); 
	
	$plans = array ();
	while ($plan = db_fetch($result)) $plans[] = $plan;
	
	db_close($link);
	return $plans;
}


// Возвращает массив с планом
function plan_info ($pid)
{
	$link = db_open();	
	
	$pid    = asql($link, $pid);
	$query  = "SELECT * FROM `plans` WHERE `pID`='$pid' ";
	$result = db_query($link, $query); 
	
	$plan = db_fetch($result);
	
	db_close($link);
	return $plan;
}


// Создание депозита
function make_deposit ($uid, $pid, $amount, $curr = 'USD')
{
	$link = db_open();	
	
	$uid    = asql($link, $uid);
	$pid    = asql($link, $pid);
	$amount = acomma ($amount);
	$amount = asql($link, $amount);
	$curr   = asql($link, $curr);
	
	$user = user_info($uid);
	$plan = plan_info ($pid);
	
	// Обработка ошибок
	if ($amount > $user['uBal'.$curr]) throw new Exception ('You have enough money');
	if ($amount < $plan['pMin']) throw new Exception ('Minimum amount: $'.number_format($plan['pMin'], 2, '.', ''));
	if ($amount > $plan['pMax']) throw new Exception ('Maximum amount: $'.number_format($plan['pMax'], 2, '.', ''));
	
	// Добавляем новый депозит
	$query = "INSERT INTO  deposits  (duID, dpID, dStatus, dCurrency, dBegin, dEnd, dAmount, dTotalWorked) 
	                       VALUES ('$uid', '$pid', '1', '$curr', '".time()."', '".time()."', '$amount', '0') ";
	
	$result = db_query($link, $query);
	
	// Блокировка суммы депозита
	$query = "UPDATE `users` SET `uBal".$curr."`= `uBal".$curr."` - '$amount', 
	                             `uLock".$curr."`= `uLock".$curr."` + '$amount' 
								 WHERE uID='$uid' ";

    db_query($link, $query);
	db_close($link);
	
	// Создаем операцию
	create_operation ($uid, 'DEPOSIT', $curr, $amount, 3, $plan['pName']);
	
	// Начисляем рефку
	ref_depo ($uid, $pid, $amount);
}


// Начисление рефки от депозита
function ref_depo ($uid, $pid, $amount, $curr = 'USD')
{
	$link = db_open();	
	
	$uid      = asql($link, $uid);
	$pid      = asql($link, $pid);
	$amount   = asql($link, $amount);
	$currency = asql($link, $curr);
	$curr     = asql($link, $curr);
	$curr     = 'uBal'.$curr;
	
	
	$user = user_info ($uid);
	$uid = $user['uRef'];
	$ref_user = user_info ($uid);
	$ref_bal = $ref_user[$curr];
	$plan = plan_info ($pid);
	
	// Если у пользователя нет реферала, то просто выходим
	if (!$ref_user) return 0;
	
	if ($plan['pRefDepo'] > 0)
	{
		$ref = $ref_bal + ($amount * $plan['pRefDepo'] / 100);
		
		$query = "UPDATE users SET $curr ='$ref' 
							   WHERE uID ='$uid' ";
	
		db_query($link, $query);
		
		// Создаем операцию
		create_operation ($uid, 'REF.', $currency, ($amount * $plan['pRefDepo'] / 100), 3, $user['uLogin']);
	}	
	
	db_close($link);
}


// Установка в куки приглашающего
function set_inviter ($login) 
{
	if ($uid = get_id ($login)) $_SESSION['INVITER'] = $uid;	
	header('Location: /');
	exit;

}


// Возвращает массив со всеми операциями пользователя
function user_operations ($uid, $oper = '%') 
{
	check_token ();
	$link = db_open();

	$uid  = asql($link, $uid);
	$oper = asql($link, $oper);
	
	$query = "SELECT * FROM `operations` WHERE `ouID`='$uid' AND `oOperation` LIKE '$oper' ORDER BY `oID` DESC";
	$result = db_query($link, $query); 
	
	$operations = array ();
	while ($oper = db_fetch($result)) $operations[] = $oper;
	
	db_close($link);
	return $operations;
}


// Возвращает массив со всеми операциями, для статистики
function statistics_operation ($oper, $limit = 10) 
{
	$link = db_open();

	$oper = asql($link, $oper);
	$limit = intval($limit);
	
	$query = "SELECT * FROM `operations` WHERE `oOperation`='$oper' AND `oStatus`='3' ORDER BY `oID` DESC LIMIT 0, $limit ";
	$result = db_query($link, $query); 
	
	$operations = array ();
	while ($oper = db_fetch($result)) $operations[] = $oper;
	
	db_close($link);
	return $operations;
}


// Возвращает кол-во пользователей в системе
function total_users () 
{
	$link = db_open();

	$query = "SELECT COUNT(*) AS TOTAL FROM `users` ";
	$result = db_query($link, $query); 
	
	$total = db_fetch($result);
	$total = $total['TOTAL'];
	
	db_close($link);
	return $total;
}

// Возвращает кол-во ботов в системе
function total_bots () 
{
	$link = db_open();

	$query = "SELECT COUNT(*) AS TOTAL FROM `bots` ";
	$result = db_query($link, $query); 
	
	$total = db_fetch($result);
	$total = $total['TOTAL'];
	
	db_close($link);
	return $total;
}

// Возвращает сумму всех активных депозитов
function total_depo () 
{
	$link = db_open();

	$query = "SELECT SUM(`dAmount`) AS TOTAL FROM `deposits` WHERE `dStatus`='1' ";
	$result = db_query($link, $query); 
	
	$total = db_fetch($result);
	$total = $total['TOTAL'];
	
	db_close($link);
	return $total;
}




// Возвращает сумму всех инвестиций
function total_in () 
{
	$link = db_open();

	$query = "SELECT SUM(`oSum`) AS TOTAL FROM `operations` WHERE `oOperation`='ADDFUNDS' ";
	$result = db_query($link, $query); 
	
	$total = db_fetch($result);
	$total = $total['TOTAL'];
	
	db_close($link);
	return $total;
}


// Возвращает сумму всех инвестиций
function total_out () 
{
	$link = db_open();

	$query = "SELECT SUM(`oSum`) AS TOTAL FROM `operations` WHERE `oOperation`='WITHDRAWAL' ";
	$result = db_query($link, $query); 
	
	$total = db_fetch($result);
	$total = $total['TOTAL'];
	
	db_close($link);
	return $total;
}


// Фн. возвращает текущее время
function clock ()
{
	return date("H:i:s", time());
}


// Фн. возвращает кол-во рефералов у пользователя
function ref_count ($uid) 
{
	check_token ();
	$link = db_open();

	$uid  = asql($link, $uid);
		
	$query = "SELECT COUNT(*) AS COUNT FROM `users` WHERE `uRef`='$uid' ";
	$result = db_query($link, $query); 
	
	$count = db_fetch($result);
	$count = $count['COUNT'];
	
	db_close($link);
	return $count;
}


// Фн. возвращает сумму всех реферальских 
function ref_total ($uid) 
{
	check_token ();

	$link = db_open();
	$uid  = asql($link, $uid);
		
	$query = "SELECT SUM(`oSum`) AS TOTAL FROM `operations` WHERE `ouID`='$uid' AND `oOperation`='REF.' ";
	$result = db_query($link, $query); 
	
	$row = db_fetch($result);
	$total = $row['TOTAL'];
			
	db_close($link);
	return $total;
}


// Фн. возвращает массив со всеми ботами
function all_bots ()
{
	$link   = db_open();
	
	$query = "SELECT * FROM `bots` ORDER BY `uID` DESC";
	$result = db_query($link, $query); 
	
	$bots = array();
	while ($bot = db_fetch($result)) $bots[] = $bot;

	db_close($link);	
	return $bots;
}


// Фн. возвращает массив с ботом по его uid
function get_bot ($uid)
{
	$link = db_open();
	$uid  = asql($link, $uid);
	
	$query = "SELECT * FROM `bots` WHERE `uID`='$uid' ";
	$result = db_query($link, $query); 
	
	$bot = db_fetch($result);

	db_close($link);	
	return $bot;
}


// Фн. управления активными ботами (ввод\вывод денежных средств, создание новых депозитов по истечению старых)
function live_bots()
{
	$link = db_open();
	$bots = all_bots ();
	
	// Производим операции вывода средств
	foreach ($bots as $bot)
	{
		// Если бот отключен, то пропускаем его
		if ($bot['Disabled']) continue;
		
		$uid    = asql($link, $bot['uID']);
		$curr   = asql($link, $bot['Curr']);
		$ps     = asql($link, $bot['psID']);
		$plan   = asql($link, $bot['planID']);
		$amount = asql($link, $bot['Amount']);
		
		$user = user_info ($uid);
		$out  = asql($link, $user['uBalUSD']);
		
		// Производим "вывод" средство если надо
		if ($user['uBalUSD'] > 0)
		{
			// Создаем операцию вывода средств
			create_operation ($uid, 'WITHDRAWAL', $curr, $out, 3, '9999', $ps);

			// Обнуляем доступный баланс
			$query = "UPDATE `users` SET `uBalUSD` = '0' WHERE `uID` = '$uid' ";	
			db_query($link, $query);
		}	
		
		// Создаем новый депозит если надо
		if ($user['uLockUSD'] == 0)
		{	
			// Производим пополнение баланса пользователю
			$query = "UPDATE `users` SET `uBalUSD` = '$amount' WHERE `uID` = '$uid' ";	
			db_query($link, $query);
			
			// Создаем операцию пополнения 
			create_operation ($uid, 'ADDFUNDS', $curr, $amount, 3, '9999', $ps);
			
			// Создаем боту новый депозит
			make_deposit($uid, $plan, $amount);
		}	
	}
	
	db_close($link);	
}

?>