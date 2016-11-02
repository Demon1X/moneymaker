<?php include "header.tpl" ?>
<h2>Редактировать ПС</h2>

<?=	msg(); ?>

<form action="/edit-paysys/<?=$ps?>" method="post">
<table>	
<tr>
<td>Отключена</td>
<td><input type="checkbox" name="disabled" value="1" <?php if ($paysys['psDisabled']) echo "checked";?> > </td>
</tr>	

<tr>
<td>Скрыта</td>
<td><input type="checkbox" name="hidden" value="1" <?php if ($paysys['psHidden']) echo "checked";?> > </td>
</tr>	


<tr>
<td>Наименование</td>
<td><input name="name" type="text" value="<?=$paysys['psName']?>"></td>
</tr>	


<tr>
<td>Валюта</td>
<td><input name="currency" type="text" value="<?=$paysys['psCurrency']?>"></td>
</tr>	

<tr>
<td>Кол-во знаков после запятой</td>
<td><input name="numdec" type="text" value="<?=$paysys['psNumDec']?>"></td>
</tr>	

<tr>
<td>Пополнение режим</td>
<td>
<select name="cash_in_mode">
	<option value="0"   <?php if ($paysys['psCashInMode'] == '0')   echo "selected"; ?>>Отключено</option>
	<option value="1" <?php if ($paysys['psCashInMode'] == '1') echo "selected"; ?>>Через мерчант</option>
</select>
</td>
</tr>	


<tr>
<td>Пополнение мин. сумма</td>
<td><input name="cash_in_min" type="text" value="<?=$paysys['psCashInMin']?>"></td>
</tr>	

<tr>
<td>Пополнение макс. сумма</td>
<td><input name="cash_in_max" type="text" value="<?=$paysys['psCashInMax']?>"></td>
</tr>	

<tr>
<td>Вывод режим</td>
<td>
<select name="cash_out_mode">
	<option value="0"   <?php if ($paysys['psCashOutMode'] == '0')   echo "selected"; ?>>Отключено</option>
	<option value="1" <?php if ($paysys['psCashOutMode'] == '1') echo "selected"; ?>>Ручной</option>
	<option value="2" <?php if ($paysys['psCashOutMode'] == '2') echo "selected"; ?>>Инстант</option>
	<option value="3" <?php if ($paysys['psCashOutMode'] == '3') echo "selected"; ?>>Автомат</option>
</select>
</td>
</tr>	

<tr>
<td>Вывод мин. сумма</td>
<td><input name="cash_out_min" type="text" value="<?=$paysys['psCashOutMin']?>"></td>
</tr>	

<tr>
<td>Вывод макс. сумма</td>
<td><input name="cash_out_max" type="text" value="<?=$paysys['psCashOutMax']?>"></td>
</tr>	

<tr>
<td>Комиссия на пополнение</td>
<td><input name="cash_in_comis" type="text" value="<?=$paysys['psCashInComis']?>"></td>
</tr>	

<tr>
<td>Комиссия на вывод</td>
<td><input name="cash_out_comis" type="text" value="<?=$paysys['psCashOutComis']?>"></td>
</tr>	
	

<tr>
<td colspan="2" class="td_center">
	<input name="ps" type="hidden" value="<?= $paysys['psID'] ?>" />
	<input name="token" type="hidden" value="<?= set_token() ?>" />
	<input type="submit" name="editps" value="<?= $_TRANS['Save'] ?>">
</td>	
</tr>	

</table>	
</form>


<?php include "footer.tpl" ?>
