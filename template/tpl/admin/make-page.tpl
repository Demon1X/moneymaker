<?php include "header.tpl" ?>
<h2>Новая страница</h2>

<br>
<form action='/make-page' method='post'>
<table class="makePage">
<tr>
<td>
Отключена&nbsp;&nbsp;
<input type="checkbox" name="hidden_page" value="1"><br><br>
</td>
</tr>

<tr>
<td>
Заголовок<br>
<input name="topic_page"  type="text" value=""><br><br>
</td>
</tr>

<tr>
<td>
Текст<br>
<textarea name="text_page" cols="100" rows="25">
</textarea><br><br>
</td>
</tr>

<tr>
<td class="td_center">
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="make_page" value="Создать">
</td>
</tr>
</table>

</form>

<?php include "footer.tpl" ?>
