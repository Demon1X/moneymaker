<?php include "header.tpl" ?>
<h2>Редактировать новость</h2>

<br>
<form action='/edit-news/<?=$news['nID']?>' method='post'>
<table class="makePage">

<tr>
<td>
Заголовок<br>
<input name="topic_news"  type="text" value="<?=$news['nTopic']?>"><br><br>
</td>
</tr>

<tr>
<td>
Дата<br>
<input name="ts_news"  type="text" value="<?=$news['nTS']?>"><br><br>
</td>
</tr>

<tr>
<td>
Анонс<br>
<textarea name="announce_news" cols="100" rows="25">
<?=$news['nAnnounce']?>
</textarea><br><br>
</td>
</tr>

<tr>
<td>
Текст<br>
<textarea name="text_news" cols="100" rows="25">
<?=$news['nText']?>
</textarea><br><br>
</td>
</tr>

<tr>
<td>
Опубликовать с<br>
<input name="show_begin_news"  type="text" value="<?=$news['nShowBegin']?>"><br><br>
</td>
</tr>

<tr>
<td>
По<br>
<input name="show_end_news"  type="text" value="<?=$news['nShowEnd']?>"><br><br>
</td>
</tr>

<tr>
<td class="td_center">
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="save_news" value="Сохранить">
</td>
</tr>
</table>

</form>

<?php include "footer.tpl" ?>
