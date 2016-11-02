<?php include "header.tpl" ?>

<h1><?= $_TRANS['Change password'] ?></h1>

<?=	msg(); ?>

<form action="changepass" method="post">
<table class="normalTable">
<tr>
<td><?= $_TRANS['Password'] ?></td>
<td><input name="oldpass" type="password"></td>
</tr>

<tr>
<td><?= $_TRANS['New password'] ?></td>
<td><input name="pass1" type="password"></td>
</tr>

<tr>
<td><?= $_TRANS['Repeat new passowrd'] ?></td>
<td><input name="pass2" type="password"></td>
</tr>

<tr>
<td colspan="2" class="td_center">
<br><br>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="changepass" value="<?= $_TRANS['Change password'] ?>">
</td>
</tr>	
</table>	
</form>


<?php include "footer.tpl" ?>