<?php include "header.tpl" ?>
<h2>Редактировать бота <?=get_login($bot['uID'])?></h2>

<?=	msg(); ?>

<form action='/edit-bot/<?=$uid?>' method='post'>
<table>

<tr>
<td>Отключен</td>
<td><input type="checkbox" name="disabled" value="1" <?php if ($bot['Disabled']) echo "checked";?> > </td>
</tr>	

<tr>
<td>Платежная система</td>
<td>
<select name="paysys" style="width: 145px;">
<?php
	foreach ($paysys as $ps) 
	{
		$select = "";
		if ($bot['psID'] == $ps['psID']) $select = "selected";
		echo "<option value='".$ps['psID']."' $select >".$ps['psName']."</option>";
	}	
?>
</select>
</td>
</tr>

<tr>
<td>План</td>
<td>
<select name="plan" style="width: 145px;">
<?php
	foreach ($plans as $plan) 
	{
		$select = "";
		if ($bot['planID'] == $plan['pID']) $select = "selected";
		echo "<option value='".$plan['pID']."' $select >".$plan['pName']."</option>";
	}
?>	
</select>
</td>
</tr>

<tr>
<td>Сумма</td>
<td><input name="amount" type="text" value="<?=$bot['Amount']?>" style="width: 140px;"></td>
</tr>

</td>
</tr>

<tr>
<td colspan="2" style="text-align: center;">
<br>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="savebot" value="Сохранить">
</td>
</tr>

</table>
</form>


<?php include "footer.tpl" ?>