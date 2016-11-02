<?php include "header.tpl" ?>

<h1><?= $_TRANS['Sign up'] ?></h1>
<?=	msg(); ?>

<?php
	if (SIGNUP_CLOSED) 
	{	
		echo "<div class='bad_msg'>Sign up closed</div>";
	}
	else
	{	
?>

<form action="/signup" method="post">

<table class="normalTable">
<tr>
<td><?= $_TRANS['Login'] ?></td>
<td><input name="login" type="text" value="<?= @$_REQUEST['login']?>"></td>
</tr>

<tr>
<td><?= $_TRANS['E-mail'] ?></td>
<td><input name="email" type="text" value="<?= @$_REQUEST['email']?>"></td>
</tr>

<tr>
<td><?= $_TRANS['Password'] ?></td>
<td><input name="pass1" type="password"  value=""></td>
</tr>

<tr>
<td><?= $_TRANS['Repeat passowrd'] ?></td>
<td><input name="pass2" type="password"  value=""></td>
</tr>

<?php 
	if (isset($_SESSION['INVITER'])) {
?>		
<tr>
<td><?= $_TRANS['Your inviter'] ?></td>
<td><?=get_my_login_inviter($_SESSION['INVITER'])?></td>
</tr>
<?php } ?>


<tr>
<td>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input name="inviter" type="hidden" value="<?=$_SESSION['INVITER']?>" />
</td>
<td><input type="submit" name="signup" value="<?= $_TRANS['Sign up'] ?>"></td>
</tr>	
</table>

</form>

<?php 
	} include "footer.tpl" 
?>