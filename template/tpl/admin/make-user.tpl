<?php include "header.tpl" ?>
<h2>Новый пользователь</h2>

<?=	msg(); ?>

<form action='/make-user' method='post'>
<table>

<tr>
<td>Логин</td>
<td><input name="login" type="text" value=""></td>
</tr>

<tr>
<td>E-mail</td>
<td><input name="email" type="text" value=""></td>
</tr>

<tr>
<td>Пароль</td>
<td><input name="pass" type="password"></td>
</tr>

<tr>
<td colspan="2">
<br>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="makeuser" value="Создать">
</td>
</tr>

</table>
</form>

<?php include "footer.tpl" ?>