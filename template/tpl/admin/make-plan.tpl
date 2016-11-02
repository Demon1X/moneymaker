<?php include "header.tpl" ?>
<h2>Новый план</h2>

<?=	msg(); ?>

<br>
<form action='/make-plan' method='post'>
<table class="makePlan">

<tr>
<td>Наименование</td>
<td><input name="plan_name"  type="text" value=""></td>
</tr>

<tr>
<td>Скрыт</td>
<td style="text-align: left;"><input name="plan_hidden"  type="checkbox" value="1"></td>
</tr>


<tr>
<td>Группа</td>
<td><input name="plan_group"  type="text" value="0"></td>
</tr>

<tr>
<td>Мин</td>
<td><input name="plan_min"  type="text" value="10"></td>
</tr>

<tr>
<td>Макс</td>
<td><input name="plan_max"  type="text" value="5000"></td>
</tr>

<tr>
<td>Процент</td>
<td><input name="plan_percent"  type="text" value="0"></td>
</tr>

<tr>
<td>Длительность (ч.)</td>
<td><input name="plan_poriod"  type="text" value="24"></td>
</tr>

<tr>
<td>Кол-во кругов</td>
<td><input name="plan_cycles"  type="text" value="1"></td>
</tr>

<tr>
<td>Возврат (%)</td>
<td><input name="plan_return"  type="text" value="100"></td>
</tr>

<tr>
<td>Рефка от депозита</td>
<td><input name="plan_rdepo"  type="text" value="0"></td>
</tr>

<tr>
<td>Рефка с профита</td>
<td><input name="plan_rprofit"  type="text" value="0"></td>
</tr>


<tr>
<td class="td_center" colspan="2">
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="make_plan" value="Создать">
</td>
</tr>
</table>

</form>


<?php include "footer.tpl" ?>
