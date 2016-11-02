<?php include "header.tpl" ?>

<h1><?= $_TRANS['Support'] ?></h1>

<?=	msg(); ?>


<form action="support" method="post">

<table class="supportTable">
<tr>
<td><?= $_TRANS['Your E-mail'] ?></td>
<td><input name="email" class="support_input" type="text" value="<?php if (is_user()) echo get_mail($_SESSION['UID']); ?>"></td>
</tr>

<tr>
<td><?= $_TRANS['Subject'] ?></td>
<td><input name="subject" class="support_input" type="text" value=""></td>
</tr>

<tr>
<td><?= $_TRANS['Message'] ?></td>
<td><textarea name="msg"  class="support_input" value=""></textarea></td>
</tr>

<tr>
<td><input name="token" type="hidden" value="<?= set_token() ?>" /></td>
<td><input type="submit" name="send" value="<?= $_TRANS['Send'] ?>"></td>
</tr>
</table>
</form>


<?php include "footer.tpl" ?>