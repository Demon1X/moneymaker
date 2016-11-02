<?php include "header.tpl" ?>
<h2>Новый бот</h2>

<?=	msg(); ?>

<form action='/make-bot' method='post'>
<table>

<tr>
<td>Логин</td>
<td><input name="login" type="text" value="" style="width: 140px;"></td>
</tr>

<tr>
<td>Платежная система</td>
<td>
<select name="paysys" style="width: 145px;">
<?php
	foreach ($paysys as $ps) echo "<option value='".$ps['psID']."'>".$ps['psName']."</option>";
?>
</select>
</td>
</tr>

<tr>
<td>Сумма</td>
<td><input name="amount" type="text" value="10" style="width: 140px;"></td>
</tr>

<tr>
<td>План</td>
<td>
<select name="plan" style="width: 145px;">
<?php
	foreach ($plans as $plan) echo "<option value='".$plan['pID']."'>".$plan['pName']."</option>";
?>	
</select>
</td>
</tr>

<tr>
<td colspan="2" style="text-align: center;">
<br>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="newbot" value="Создать">
</td>
</tr>

</table>
</form>

<?php include "footer.tpl" ?>