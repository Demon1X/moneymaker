<?php include "header.tpl" ?>
<h2>Операции</h2>

<!-- <a href="/make-operation">Создать</a>  -->

<a href="/make-bonus" >Бонус</a>&nbsp;&nbsp;
<a href="/make-penalty" >Штраф</a>
<br><br>

<form action='/show-operations' method='post'>
<br>
&nbsp;Логин  <input name="oper_login"  type="text" value="<?= @$_SESSION['FILTER_OPER']['oper_login']?>">
&nbsp;Валюта <input name="oper_currency"  type="text" value="<?= @$_SESSION['FILTER_OPER']['oper_currency']?>">
&nbsp;Операция <select name="oper_operation">
				<option value=""        <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == '')         echo "selected"; ?>>Все</option>
				<option value="ADDFUNDS" <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'ADDFUNDS')   echo "selected"; ?>>ADD FUNDS</option>
				<option value="WITHDRAWAL" <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'WITHDRAWAL')   echo "selected"; ?>>WITHDRAWAL</option>
				<option value="DEPOSIT" <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'DEPOSIT')   echo "selected"; ?>>DEPOSIT</option>
				<option value="ACCRUAL" <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'ACCRUAL')   echo "selected"; ?>>ACCRUAL</option>
				<option value="CLOSED"  <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'CLOSED')   echo "selected"; ?>>CLOSED</option>
				<option value="REF."    <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'REF.')   echo "selected"; ?>>REF</option>
				<option value="BONUS"   <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'BONUS')   echo "selected"; ?>>BONUS</option>
				<option value="PENALTY" <?php if (@$_SESSION['FILTER_OPER']['oper_operation'] == 'PENALTY')   echo "selected"; ?>>PENALTY</option>
             </select>

&nbsp;Статус <select name="oper_status">
				<option value=""  <?php if (@$_SESSION['FILTER_OPER']['oper_status'] == '')    echo "selected"; ?>>Все</option>
				<option value="0" <?php if (@$_SESSION['FILTER_OPER']['oper_status'] == '0')   echo "selected"; ?>>Нет</option>
				<option value="1" <?php if (@$_SESSION['FILTER_OPER']['oper_status'] == '1')   echo "selected"; ?>>Подготовлен</option>
				<option value="2" <?php if (@$_SESSION['FILTER_OPER']['oper_status'] == '2')   echo "selected"; ?>>Отмена</option>
				<option value="3" <?php if (@$_SESSION['FILTER_OPER']['oper_status'] == '3')   echo "selected"; ?>>Успех</option>
             </select>
			 
<input name="token" type="hidden" value="<?= set_token() ?>" />
&nbsp;<input type="submit" name="filterOper" value="Отфильтровать">
<?php if (isset($_SESSION['FILTER_OPER'])) echo "&nbsp;<input type='submit' name='clear_filter_oper' value='Очистить'>"; ?>
</form>

<br><br>

<table class="allOperations">

<tr>
<td>ID</td>
<td>Время</td>
<td>Пользователь</td>
<td>Валюта</td>
<td>Сумма</td>
<td>Операция</td>
<td>Статус</td>
<td>Примечание</td>
</tr>

<?php 
	foreach ($operations as $oper)
	{
		echo "<tr>";
		echo "<td>{$oper['oID']}</td>";
		echo "<td>".date("d/m/Y H:i:s",$oper['oTime'])."</td>";
		echo "<td><a href='/edit-user/{$oper['ouID']}'>".get_login($oper['ouID'])."</a></td>";
		echo "<td>{$oper['oCurr']}</td>";
		echo "<td>".number_format($oper['oSum'], 2, '.', '')."</td>";
		echo "<td>{$oper['oOperation']}</td>";
		if ($oper['oStatus'] == 0) echo "<td>Нет</td>";
		if ($oper['oStatus'] == 1) echo "<td><a href='/approve/{$oper['oID']}'>Подготовлен</a></td>";
		if ($oper['oStatus'] == 2) echo "<td>Отменен</td>";
		if ($oper['oStatus'] == 3) echo "<td>Успех</td>";
		
		if ($oper['oStatus'] == 1) 
			echo "<td><a href='/canceled/{$oper['oID']}'>Отмена</a></td>";
		else
			echo "<td>{$oper['oMemo']}</td>";
		echo "</tr>";
	}
	
?>	

</table>

<br>
<div class="pagination">
<?= pagination ('operations') ?>
</div>
<br>

<?php include "footer.tpl" ?>