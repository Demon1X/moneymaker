<?php include "header.tpl" ?>
<h2>Редактировать страницу</h2>

<br>
<form action='/edit-page/<?=@$pid?>' method='post'>
<table class="makePage">
<tr>
<td>
Отключена&nbsp;&nbsp;
<input type="checkbox" name="hidden_page" value="1" <?php if ($page['pHidden'] == 1) echo "checked='checked'"; ?>><br><br>
</td>
</tr>

<tr>
<td>
Заголовок<br>
<input name="topic_page"  type="text" value="<?=$page['pTopic']?>"><br><br>
</td>
</tr>

<tr>
<td>
Текст<br>
<textarea name="text_page" cols="100" rows="25">
<?=$page['pText']?>
</textarea><br><br>
</td>
</tr>

<tr>
<td class="td_center">
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="save" value="Сохранить">
</td>
</tr>
</table>

</form>

<?php include "footer.tpl" ?>
