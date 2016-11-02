<?php include "header.tpl" ?>

<h1><?= $_TRANS['Password reset'] ?></h1>
<?=	msg(); ?>

<form action="/resetpass" method="post">

<table class="normalTable">
<tr>
<td><?= $_TRANS['Your E-mail'] ?></td>
<td><input name="email" type="text"></td>
</tr>

<tr>
<td><input name="token" type="hidden" value="<?= set_token() ?>" /></td>
<td><input type="submit" name="resetpass" value="<?= $_TRANS['Reset'] ?>"></td>
</tr>	
</table>	
</form>

<?php include "footer.tpl" ?>