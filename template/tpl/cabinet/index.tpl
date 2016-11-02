<?php include "header.tpl" ?>

<h1><?= $_TRANS['Hello'] ?>  <?=get_my_login()?></h1>

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

<!--
<tr>
<td>RUB</td>
<td><?=$user['uBalRUB']?></td>
<td><?=$user['uLockRUB']?></td>
<td><?=$user['uOutRUB']?></td>
</tr>
-->
</table>

<div class="your_inviter">
<?php 
	if (isset ($user['uRef']) and $user['uRef']!='0') 
		echo $_TRANS['Your inviter'].": ".get_login($user['uRef']);
?>
</div>


<?php include "footer.tpl" ?>