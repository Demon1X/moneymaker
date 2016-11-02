<?php include "header.tpl" ?>
<h2>Бонус</h2>

<br><br>

<form action='/make-bonus' method='post'>

<table class="makeBonus">
<tr>
<td>Валюта&nbsp;&nbsp;</td>
<td>
<select name="curr" style="width: 125px;">
	<option>USD</option>
	<option>EUR</option>
	<option>RUB</option>
	<option>BTC</option>
</select>
</td>

</tr>


<tr>
<td>Логин</td>
<td><input name="login"  type="text" value=""></td>
</td>
</tr>


<tr>
<td>Сумма</td>
<td><input name="amount"  type="text" value=""></td>
</td>
</tr>

<tr>
<td>Примечание</td>
<td><input name="memo"  type="text" value=""></td>
</td>
</tr>

<tr>
<td class="td_center" colspan="2">
<input name="token" type="hidden" value="<?=set_token()?>" />
<input type="submit" name="make_bonus" value="Создать">
</td>
</tr>

</table>

</form>



<?php include "footer.tpl" ?>