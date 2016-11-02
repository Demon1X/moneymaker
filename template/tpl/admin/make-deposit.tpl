<?php include "header.tpl" ?>
<h2>Новый депозит <?=@$user['uLogin']?></h2>

<?=	msg(); ?>

<?php
	if (isset($user))
	{
?>
<br>
<table class="user_balance">

<tr>
<td><?=$_TRANS['Currency account']?></td>
<td><?=$_TRANS['Available']?></td>
<td><?=$_TRANS['Busy']?></td>
<td><?=$_TRANS['Pending']?></td>
</tr>

<tr>
<td>USD</td>
<td><?=number_format($user['uBalUSD'], 2, '.', '')?></td>
<td><?=number_format($user['uLockUSD'], 2, '.', '')?></td>
<td><?=number_format($user['uOutUSD'], 2, '.', '')?></td>
</tr>

<tr>
<td>EUR</td>
<td><?=number_format($user['uBalEUR'], 2, '.', '')?></td>
<td><?=number_format($user['uLockEUR'], 2, '.', '')?></td>
<td><?=number_format($user['uOutEUR'], 2, '.', '')?></td>
</tr>

<tr>
<td>RUB</td>
<td><?=$user['uBalRUB']?></td>
<td><?=$user['uLockRUB']?></td>
<td><?=$user['uOutRUB']?></td>
</tr>

<tr>
<td>BTC</td>
<td><?=$user['uBalBTC']?></td>
<td><?=$user['uLockBTC']?></td>
<td><?=$user['uOutBTC']?></td>
</tr>

</table>
<br>
<?php } ?>


<form action="make-deposit" method="post">

<table class="new_depo">
<tr>
<td><?=$_TRANS['Plan']?> </td>
<td>
<select name="plan" style="width: 155px;">
<?php
	foreach ($plans as $plan) echo "<option value='".$plan['pID']."'>".$plan['pName']."</option>";
?>	
</select>
</td>			
</tr>

<tr>
<td><?=$_TRANS['Amount']?> &nbsp;</td>
<td><input name="amount" type="text"></td>
</tr>

<tr>
<td><?=$_TRANS['User']?> &nbsp;</td>
<td><input name="login" type="text"></td>
</tr>

<tr>	
<td>&nbsp;</td>
<td>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="create" value="<?=$_TRANS['Create']?>">
</td>
</tr>

</table>

</form>


<?php include "footer.tpl" ?>
