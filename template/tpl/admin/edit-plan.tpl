<?php include "header.tpl" ?>
<h2>План</h2>

<?=	msg(); ?>


<br>
<form action='/edit-plan/<?=@$pid?>' method='post'>
<table class="makePlan">

<tr>
<td>Наименование</td>
<td><input name="plan_name"  type="text" value="<?=$plan['pName']?>"></td>
</tr>

<tr>
<td>Скрыт</td>
<td style="text-align: left;"><input name="plan_hidden"  type="checkbox" value="1" <?php if ($plan['pHidden']==1) echo "checked"; ?>></td>
</tr>


<tr>
<td>Группа</td>
<td><input name="plan_group"  type="text" value="<?=$plan['pGroup']?>"></td>
</tr>

<tr>
<td>Мин</td>
<td><input name="plan_min"  type="text" value="<?=$plan['pMin']?>"></td>
</tr>

<tr>
<td>Макс</td>
<td><input name="plan_max"  type="text" value="<?=$plan['pMax']?>"></td>
</tr>

<tr>
<td>Процент</td>
<td><input name="plan_percent"  type="text" value="<?=$plan['pPercent']?>"></td>
</tr>

<tr>
<td>Длительность (ч.)</td>
<td><input name="plan_poriod"  type="text" value="<?=$plan['pPeriod']?>"></td>
</tr>

<tr>
<td>Кол-во кругов</td>
<td><input name="plan_cycles"  type="text" value="<?=$plan['pCycles']?>"></td>
</tr>

<tr>
<td>Возврат (%)</td>
<td><input name="plan_return"  type="text" value="<?=$plan['pReturn']?>"></td>
</tr>

<tr>
<td>Рефка от депозита</td>
<td><input name="plan_rdepo"  type="text" value="<?=$plan['pRefDepo']?>"></td>
</tr>

<tr>
<td>Рефка с профита</td>
<td><input name="plan_rprofit"  type="text" value="<?=$plan['pRefProfit']?>"></td>
</tr>


<tr>
<td class="td_center" colspan="2">
<input name="plan_id" type="hidden" value="<?=$plan['pID']?>" />
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="save" value="Сохранить">
</td>
</tr>
</table>

</form>


<?php include "footer.tpl" ?>
