<?php
// Пополнение баланса пользоватлем
function add_funds ($uid, $ps, $amount)
{
	check_token ();
	$link = db_open();

	$uid    = asql($link, $uid);
	$ps = asql($link, $ps);
	$amount = asql($link, $amount);
	$amount = acomma($amount);
	
	// Проверки кооректности суммы для ввода
	$paysys = get_paysys ($ps);
	
	if ($amount < $paysys['psCashInMin']) 
	{
		$_SESSION['bad_msg']='Min: '.number_format($paysys['psCashInMin'], 2, '.', '');
		header("Location: /add-funds");
		exit;
	}
	
	if ($amount > $paysys['psCashInMax']) 
	{
		$_SESSION['bad_msg']='Max: '.number_format($paysys['psCashInMax'], 2, '.', '');
		header("Location: /add-funds");
		exit;
	}
	
	// Создаем операцию пополнения
	$oper_id = create_operation ($uid, 'ADDFUNDS', 'USD', $amount, 1, ' ');
	
	db_close($link);
	return $oper_id;
}



// Вывод средств пользователем 
function withdrawal ($uid, $ps, $amount, $curr)
{
	check_token ();
	$link = db_open();

	$uid    = asql($link, $uid);
	$ps = asql($link, $ps);
	$amount = asql($link, $amount);
	$amount = acomma($amount);
	$curr   = asql($link, $curr);
	
	$user   = user_info($uid);
	$paysys = get_paysys ($ps);
	
	
	// Проверки кооректности суммы для вывода, и наличие кошелька
	if ($amount < $paysys['psCashOutMin']) throw new Exception ('Min: '.number_format($paysys['psCashOutMin'], 2, '.', ''));
	if ($amount > $paysys['psCashOutMax']) throw new Exception ('Max: '.number_format($paysys['psCashOutMax'], 2, '.', ''));
	if ($user['uBal'.$curr] < $amount) throw new Exception ('Not enough money');
	if ($ps == 1 AND $user['uPerfectMoney'] == '') throw new Exception ('No wallet');
	if ($ps == 2 AND $user['uPayeer'] == '') throw new Exception ('No wallet');
	
	
	// Минусуем сумму с доступного баланса, и плюсуем её в ожидание		
	$query = "UPDATE `users` SET `uBalUSD` = `uBalUSD` - '$amount', 
								 `uOutUSD` = `uOutUSD` + '$amount' 
								  WHERE `uID` = '$uid' ";	
								 
	$result = db_query($link, $query);


	// Создаем операцию вывода средств
	$oper_id = create_operation ($uid, 'WITHDRAWAL', $curr, $amount, 1, ' ', $ps);
	
	// Вызываем фн. вывода средств пользователю
	$out = array ();
	$batch = '9999';
	if ($ps == 1 AND $paysys['psCashOutMode'] > 1)
	{	
		$out = spend_perfect($amount, $user['uPerfectMoney'], $oper_id);
		if (isset($out['ERROR'])) return 'Wait. The operation is processed.';
		$batch = $out['PAYMENT_BATCH_NUM'];
	}	
	else
		return 'Wait. The operation is processed.';
		
	
	// Минусуем баланс "в ожидание" 
	$query = "UPDATE `users` SET `uOutUSD` = `uOutUSD` - '$amount' WHERE uID = '$uid' ";	
	$result = db_query($link, $query); 
	
	// И апдейтим оерацию как выволенную
	$query = "UPDATE `operations` SET `oStatus`=3, `oBatch`='$batch' WHERE oID = '$oper_id' ";	
	$result = db_query($link, $query); 
	
	db_close($link);
	return "Success";
}


// Фн. делает вывод денежных средств по ранее составленной пользователем заявке
function withdrawal_later ($oper_id)
{
	$link = db_open();
	
	$oper_id = asql($link, $oper_id);
	$oper = get_oper ($oper_id);
	
	$uid = $oper['ouID'];
	$uid = asql($link, $uid);
	$user   = user_info($uid);
	
	$amount = $oper['oSum'];
	$amount = asql($link, $amount);
	$amount = round($amount, 2);
	
	$curr = $oper['oCurr'];
	$curr = asql($link, $curr);
	
	$ps = get_paysys ($oper['opsID']);
	$ps = $ps['psID'];
	
	// Вызываем фн. вывода средств пользователю
	$out = array ();
	$batch = '9999';
	if ($ps == 1) 
	{		
		$out = spend_perfect($amount, $user['uPerfectMoney'], $oper_id);
		if (isset($out['ERROR'])) throw new Exception ($out['ERROR']);
		$batch = $out['PAYMENT_BATCH_NUM'];
	}
	
	// Минусуем баланс "в ожидание" 
	$query = "UPDATE `users` SET `uOutUSD` = `uOutUSD` - '$amount' WHERE uID = '$uid' ";	
	$result = db_query($link, $query);
		
	// И апдейтим оерацию как выволенную
	$query = "UPDATE `operations` SET `oStatus`=3, `oBatch`='$batch' WHERE oID = '$oper_id' ";	
	$result = db_query($link, $query); 
	
	db_close($link);
	return "Success";
}


// Фн. отменяет ранее составленную пользователем заявку на вывод денежных средств
// и возвращает деньги из ожидания на доступный баланс
function canceled ($oper_id)
{
	//echo $oper_id;
	$link = db_open();
	
	$oper_id = asql($link, $oper_id);
	$oper = get_oper ($oper_id);
	
	$uid = $oper['ouID'];
	$uid = asql($link, $uid);
	$user   = user_info($uid);
	
	$amount = $oper['oSum'];
	$amount = asql($link, $amount);
	$amount = round($amount, 2);
	
	$curr = $oper['oCurr'];
	$curr = asql($link, $curr);
	
	$ps = get_paysys ($oper['opsID']);
	$ps = $ps['psID'];
	
	// Отмена операции по выводу средств
	if ($oper['oOperation'] == 'WITHDRAWAL')
	{	
		// Минусуем сумму с ожидания и плюсуме её на доступного баланс
		$query = "UPDATE `users` SET `uBalUSD` = `uBalUSD` + '$amount', 
									 `uOutUSD` = `uOutUSD` - '$amount' 
									  WHERE `uID` = '$uid' ";	
		$result = db_query($link, $query);
			
		// Апдейтим оерацию как отмененную
		$query = "UPDATE `operations` SET `oStatus`=2 WHERE oID = '$oper_id' ";	
		$result = db_query($link, $query); 
	}

	
	// Отмена операции на пополнение
	if ($oper['oOperation'] == 'ADDFUNDS')
	{	
		// Апдейтим оерацию как отмененную
		$query = "UPDATE `operations` SET `oStatus`=2 WHERE oID = '$oper_id' ";	
		$result = db_query($link, $query); 
	}
	
	db_close($link);
	return "Сanceled";
}


// Возвращает массив со всеми платежными системами
function all_paysys ()
{
	$link = db_open();	
	
	$query  = "SELECT * FROM `paysys` ";
	$result = db_query($link, $query); 
	
	$paysys = array();
	while ($ps = db_fetch($result)) $paysys[] = $ps;
	
	db_close($link);
	return $paysys;
}


// Возвращает массив с платежной системой
function get_paysys ($ps)
{
	$link = db_open();
	$ps   = asql($link, $ps);	
	
	$query  = "SELECT * FROM `paysys` WHERE `psID`='$ps' ";
	$result = db_query($link, $query); 
	
	$paysys = array();
	$paysys = db_fetch($result); 
	
	db_close($link);
	return $paysys;
}


// Редактирование платежной системы
function edit_paysys ()
{
	check_token ();
	$link = db_open();
	
	$disabled = asql($link, @$_REQUEST['disabled']);
	$hidden   = asql($link, @$_REQUEST['hidden']);
	
	$ps       = asql($link, $_REQUEST['ps']);	
	$name     = asql($link, $_REQUEST['name']);
	$currency = asql($link, $_REQUEST['currency']);
	$numdec   = asql($link, $_REQUEST['numdec']);
	
	$cash_in_mode = asql($link, $_REQUEST['cash_in_mode']);
	$cash_in_min  = asql($link, $_REQUEST['cash_in_min']);
	$cash_in_max  = asql($link, $_REQUEST['cash_in_max']);
	
	$cash_out_mode = asql($link, $_REQUEST['cash_out_mode']);
	$cash_out_min  = asql($link, $_REQUEST['cash_out_min']);
	$cash_out_max  = asql($link, $_REQUEST['cash_out_max']);
	
	$cash_in_comis  = asql($link, $_REQUEST['cash_in_comis']);
	$cash_out_comis = asql($link, $_REQUEST['cash_out_comis']);
	
	$query = "UPDATE `paysys` SET `psName`='$name', 
								  `psDisabled`='$disabled', 
								  `psHidden`='$hidden', 
	                              `psCurrency`='$currency',
								  `psNumDec`='$numdec',
								  `psCashInMin`='$cash_in_min',
								  `psCashInMax`='$cash_in_max',
								  `psCashOutMin`='$cash_out_min',
								  `psCashOutMax`='$cash_out_max',
								  `psCashInMode`='$cash_in_mode',
								  `psCashOutMode`='$cash_out_mode',
								  `psCashInComis`='$cash_in_comis',
								  `psCashOutComis`='$cash_out_comis'
								  WHERE `psID`= '$ps' ";	
								  
	$result = db_query($link, $query);
	db_close($link);
	
	return "Save";
}


// Фн. плюсует баланс пользователю после пополениния
function plus ($oid, $batch)
{
	$link   = db_open();
	
	$oid    = asql($link, $oid);
	$batch  = asql($link, $batch);
	
	$oper   = get_oper ($oid);
	
	// Если статус операции уже ранее была успешно выполенна, то выходим из фн.
	if ($oper['oStatus'] == 3) return 0;
	
	$amount = asql($link, $oper['oSum']);
	$uid    = asql($link, $oper['ouID']);
	
	
	$query = "UPDATE `users` SET `uBalUSD` = `uBalUSD` + '$amount' WHERE uID = '$uid' ";	
	$result = db_query($link, $query); 
	
	
	$query = "UPDATE `operations` SET `oStatus`=3, `oBatch`='$batch' WHERE oID = '$oid' ";	
	$result = db_query($link, $query); 
	
	db_close($link);	
}


// Фн. осуществляет вывод средств на Perfect Money
function spend_perfect($amount, $wallet, $oper_id)
{
	// trying to open URL to process PerfectMoney Spend request
	$f=fopen('https://perfectmoney.is/acct/confirm.asp?AccountID='.PERFECT_ID.'&PassPhrase='.PERFECT_PASS.'&Payer_Account='.PERFECT_WALLET.'&Payee_Account='.$wallet.'&Amount='.$amount.'&PAY_IN=1&PAYMENT_ID='.$oper_id, 'rb');

	if($f===false){
	   echo 'error openning url';
	}

	// getting data
	$out=array(); $out="";
	while(!feof($f)) $out.=fgets($f);

	fclose($f);

	// searching for hidden fields
	if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){
	   echo 'Ivalid output';
	   exit;
	}

	$ar="";
	foreach($result as $item){
	   $key=$item[1];
	   $ar[$key]=$item[2];
	}

	return $ar;
}

function test_perfect ()
{
	// trying to open URL to process PerfectMoney Spend request
	$f=fopen('https://perfectmoney.is/acct/balance.asp?AccountID='.PERFECT_ID.'&PassPhrase='.PERFECT_PASS, 'rb');

	if($f===false){
	   echo 'error openning url';
	}

	// getting data
	$out=array(); $out="";
	while(!feof($f)) $out.=fgets($f);

	fclose($f);

	// searching for hidden fields
	if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){
	   echo 'Ivalid output';
	   exit;
	}

	// putting data to array
	$ar="";
	foreach($result as $item){
	   $key=$item[1];
	   $ar[$key]=$item[2];
	}

	return $ar;

}

?>