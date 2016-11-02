<?php include "header.tpl" ?>
<h2>Почта</h2>

<?=	msg(); ?>

<div class="post">

<form action="/post" method="post">

<table>
<tr>
<td><b>Отправитель</b></td>
<td><input name="sender" type="text" value="Admin"></td>
</tr>

<tr>	
<td><b>E-mail отправителя</b></td>
<td><input name="sender_email" type="text" value="<?= SUPPORT_EMAIL ?>"></td>
</tr>

<tr>	
<td>Получатели<?= send_login () ?></td>
<td><textarea name="recipients"  cols="32" rows="7" "><?= send_user() ?></textarea></td>
</tr>	
</table>	

<hr>

<table>	

<tr>
<td>
<?= $_TRANS['Subject'] ?><br>
<input name="subject" type="text" value="">
</td>
</tr>	

<tr>
<td><?= $_TRANS['Message'] ?><br>
<textarea name="msg"  cols="80" rows="10" value=""></textarea><br><br>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="send" value="<?= $_TRANS['Send'] ?>">
</td>	
</tr>

</table>		

</form>
</div>

<?php include "footer.tpl" ?>