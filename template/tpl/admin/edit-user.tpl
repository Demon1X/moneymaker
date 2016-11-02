<?php include "header.tpl" ?>
<h2>Пользователь: <?= $user['uLogin'] ?></h2>

<?=	msg(); ?>

<form action="/edit-user/<?=$uid?>" method="post">
<table>	
<tr>
<td>Регистрация<br><br></td>
<td><?= date("d-m-Y / H:i:s",$user['uRegTime']) ?><br><br></td>
</tr>	

<tr>
<td>Последний визит<br><br></td>
<td><?= date("d-m-Y / H:i:s",$user['uLastVisit']) ?><br><br></td>
</tr>	


<tr>
<td>IP<br><br></td>
<td><?= $user['uIP'] ?><br><br></td>
</tr>	


<tr>
<td>Группа</td>
<td><input name="group" type="text" value="<?= $user['uGroup'] ?>"></td>
</tr>	

<tr>
<td>Логин</td>
<td><input name="login" type="text" value="<?= $user['uLogin'] ?>"></td>
</tr>	

<tr>
<td>E-mail</td>
<td><input name="email" type="text" value="<?= $user['uMail'] ?>"></td>
</tr>	

<tr>
<td>Пароль</td>
<td><input name="pass" type="text" value=""></td> 
</tr>	


<tr>
<td>Уровень доступа</td>
<td><input name="level" type="text" value="<?= $user['uLevel'] ?>"></td>
</tr>	


<tr>
<td>Реферал</td>
<td><input name="inviter" type="text" value="<?=get_login($user['uRef'])?>"></td>
</tr>	


<tr>
<td>Статус</td>
<td>
<select name="status">
	<option value="active"   <?php if ($user['uStatus'] == 'active')   echo "selected"; ?>>active</option>
	<option value="inactive" <?php if ($user['uStatus'] == 'inactive') echo "selected"; ?>>inactive</option>
</select>
</td>
</tr>	

<tr>
<td colspan="2" class="td_center">
	<input name="uid" type="hidden" value="<?= $user['uID'] ?>" />
	<input name="token" type="hidden" value="<?= set_token() ?>" />
	<input type="submit" name="edituser" value="<?= $_TRANS['Save'] ?>">
</td>	
</tr>	

</table>	
</form>


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
<td><?=number_format($user['uBalRUB'], 2, '.', '')?></td>
<td><?=number_format($user['uLockRUB'], 2, '.', '')?></td>
<td><?=number_format($user['uOutRUB'], 2, '.', '')?></td>
</tr>

<tr>
<td>BTC</td>
<td><?=$user['uBalBTC']?></td>
<td><?=$user['uLockBTC']?></td>
<td><?=$user['uOutBTC']?></td>
</tr>


</table>


<br><br>
<a href="/aka-user/<?= $user['uID'] ?>" >Войти как пользователь</a>&nbsp;&nbsp;
<a href="/post/<?= $user['uID'] ?>" >Отправить сообщение</a>&nbsp;&nbsp;
<a href="/show-operations" >Все операции пользоватлея</a><br>
<br><br>

<?php include "footer.tpl" ?>