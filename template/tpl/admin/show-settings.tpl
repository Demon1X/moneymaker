<?php include "header.tpl" ?>

<h2>Настройки</h2>
<?=msg()?>

<form action='/show-settings' method='post'>

<table style="mainSettings">
<tr>
<td>Имя сайта</td>
<td><input name="SITE_NAME" type="text" value="<?=SITE_NAME?>"></td>
</tr>

<tr>
<td>E-mail формы обратной связи</td>
<td><input name="SUPPORT_EMAIL" type="text" value="<?=SUPPORT_EMAIL?>"></td>
</tr>

<tr>
<td>Язык по умолчанию</td>
<td><input name="DEFAULT_LANG" type="text" value="<?=DEFAULT_LANG?>">
</td>
</tr>

<tr>
<td>Технические работы</td>
<td>
<input name="TECHNICAL_WORK" type="hidden" value="0">
<input name="TECHNICAL_WORK" type="checkbox" value="1" <?php if (TECHNICAL_WORK) echo 'checked'?>>
</td>
</tr>

<td colspan="2">
<h3>Пользователи</h3>
</td>
</tr>

<tr>
<td>Закрыть регистрацию новых пользователей</td>
<td>
<input name="SIGNUP_CLOSED" type="hidden" value="0">
<input name="SIGNUP_CLOSED" type="checkbox" value="1" <?php if (SIGNUP_CLOSED) echo 'checked'?>>
</td>
</tr>

<td colspan="2">
<h3>Безопасность</h3>
</td>
</tr>

<tr>
<td>Минимальная длина пароля</td>
<td><input name="MIN_PASSWORD" type="text" value="<?=MIN_PASSWORD?>"></td>
</tr>

<tr>
<td>Использовать только HTTPS</td>
<td>
<input name="USE_HTTPS" type="hidden" value="0">
<input name="USE_HTTPS" type="checkbox" value="1" <?php if (USE_HTTPS) echo 'checked'?>>
</td>
</tr>

<tr>
<td>Привязка сиссий к IP</td>
<td>
<input name="BUNCH_SESSION_IP" type="hidden" value="0">
<input name="BUNCH_SESSION_IP" type="checkbox" value="1" <?php if (BUNCH_SESSION_IP) echo 'checked'?>>
</td>
</tr>

<td colspan="2">
<br>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="save" value="Сохранить">
</td>
</tr>

</table>

</form>

<?php include "footer.tpl" ?>