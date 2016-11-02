<?php include "header.tpl" ?>
<h2>Депозиты</h2>

<a href="/make-deposit">Создать</a>

<br><br>

<form action='/show-deposits' method='post'>
<br>
&nbsp;Логин  <input name="depo_login"     type="text" value="<?= @$_SESSION['FILTER_DEPO']['depo_login']?>">
&nbsp;Валюта <input name="depo_currency"  type="text" value="<?= @$_SESSION['FILTER_DEPO']['depo_currency']?>">
&nbsp;План <select name="depo_plan" style="width: 155px;">
<option value='%'>Все</option>
<?php
	
	foreach ($plans as $plan) 
	{
		$focus = '';
		if (@$_SESSION['FILTER_DEPO']['depo_plan'] == $plan['pID']) $focus = "selected";
		echo "<option value='".$plan['pID']."' $focus >".$plan['pName']."</option>";
	}
?>	
</select>

&nbsp;Статус <select name="depo_status">
				<option value=""        <?php if (@$_SESSION['FILTER_DEPO']['depo_status'] == '')         echo "selected"; ?>>Все</option>
				<option value="1" <?php if (@$_SESSION['FILTER_DEPO']['depo_status'] == '1')   echo "selected"; ?>>Активен</option>
				<option value="0" <?php if (@$_SESSION['FILTER_DEPO']['depo_status'] == '0')   echo "selected"; ?>>Закрыт</option>
             </select>

<input name="token" type="hidden" value="<?= set_token() ?>" />
&nbsp;<input type="submit" name="filterDepo" value="Отфильтровать">
<?php if (isset($_SESSION['FILTER_DEPO'])) echo "&nbsp;<input type='submit' name='clear_filter_depo' value='Очистить'>"; ?>
</form>

<br><br>


<table class="showDepo">

<tr>
<th>ID</th>
<th>Дата начала</th>
<th>Пользователь</th>
<th>Сумма</th>
<th>План</th>
<th>Последнее<br>начисление</th>
<th>Следующиее<br>начисление</th>
<th>Всего<br>начислений</th>
<th>Статус</th>
</tr>


<?php
	foreach ($deposits as $deposit)
	{
//        if (is_bot($deposit['duID'])) $a='fake';
  //      else $a = 'true';
        
        
		$plan = get_plan ($deposit['dpID']);
		echo "<tr>";
		echo "<td>{$deposit['dID']}</td>";
		echo "<td>".date("d-m-Y H:i:s", $deposit['dBegin'])."</td>";
	    echo "<td><a href='/edit-user/{$deposit['duID']}'>".user_login($deposit['duID'])."</a></td>";
		echo "<td>".number_format($deposit['dAmount'], 2, '.', '')."</td>";
		echo "<td>".plan_name($deposit['dpID'])."</td>";
		
		if ($deposit['dTotalWorked'] > 0)
			echo "<td>".date("d/m/Y H:i", $deposit['dEnd'])."</td>";
		else
			echo "<td> - </td>";	
		
		if ($deposit['dStatus']) 
            echo "<td>".date("d/m/Y H:i", $deposit['dEnd']+$plan['pPeriod']*3600)."</td>";
		else
			echo "<td> - </td>";	
		
		echo "<td>{$deposit['dTotalWorked']}</td>";
		
		if ($deposit['dStatus'])
			echo "<td>Активен</td>";
		else
			echo "<td>Закрыт</td>";	
		
		echo "</tr>";
	}
?>


</table>
<br><br>

<div class="pagination">
<?= pagination ('deposits') ?>
</div>
<br>

<?php include "footer.tpl" ?>