<?php include "header.tpl" ?>

<h1><?= $_TRANS['Entry'] ?></h1>
<?=	msg(); ?>

<form action="/login" method="post">

<table class="normalTable" >
<tr>
<td><?= $_TRANS['Login'] ?></td>
<td><input name="login" type="text" value=""></td>
</tr>

<tr>
<td><?= $_TRANS['Password'] ?></td>
<td><input name="password" type="password" value=""></td>
</tr>

<tr>
<td><input name="token" type="hidden" value="<?= set_token() ?>" /></td>
<td><input type="submit" value="<?= $_TRANS['Login'] ?>"></td>
</tr>

</table>

</form>

<div class="linkCenter">
<a href="resetpass"><?= $_TRANS['Forgot password'] ?></a>
</div>




<?php include "footer.tpl" ?>