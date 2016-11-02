<?php include "header.tpl" ?>

<h1><?= $_TRANS['Settings'] ?></h1>

<?=	msg(); ?>

<form action='/settings' method='post'>

<table class="normalTable">

<tr>
<td><?= $_TRANS['Date of registration'] ?></td>
<td><?= date('d M Y H:i:s',$user['uRegTime']) ?></td>
</tr>

<tr>
<td><?= $_TRANS['Your login'] ?></td>
<td><?= $user['uLogin'] ?><td>
</tr>

<tr>
<td><?= $_TRANS['Your E-mail'] ?></td>
<td><?= $user['uMail'] ?><td>
</tr>

<tr>
<td><?= $_TRANS['Time zone'] ?></td>
<td><input name="gmt" type="text" class="gmt" value="<?= $user['uGMT'] ?>"></td>
</tr>

<tr>
<td><?= $_TRANS['Your IP'] ?></td>
<td><?= $user['uIP'] ?></td>
</tr>

<tr>
<td><?= $_TRANS['Last visit'] ?></td>
<td><?= date('d M Y H:i:s',$user['uLastVisit']) ?></td>
</tr>

<tr>
<td  colspan="2" class="td_center">
<br><br>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="save" value="<?= $_TRANS['Save'] ?>"></td>
</tr>

<tr>
<td colspan="2" class="td_center">
<br><br><br><br>
<a href="changepass"><?= $_TRANS['Change password'] ?></a>
</td>
</tr>
</table>
</form>



<?php include "footer.tpl" ?>