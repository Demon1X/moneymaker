<?php include "header.tpl" ?>

<h2>Новости</h2>

<a href="/make-news">Создать новость</a><br><br>


<table class="allNews">
<tr>
<th>ID</th>
<th>Заголовок</th>
<th>Анонс</th>
<th>Отображать с</th>
<th>По</th>
<th>Дата</th>
<th>Удалить</th>
<!-- <th>Просмотр</th> -->
</tr>


<?php

	foreach ($news as $novelty)
	{
		echo "<tr>";
		echo "<td>{$novelty['nID']}</td>";
		echo "<td>{$novelty['nTopic']}</td>";
		echo "<td><a href='/edit-news/{$novelty['nID']}'>{$novelty['nAnnounce']}</a></td>";
		echo "<td>".date("d-m-Y, H:i:s", $novelty['nShowBegin'])."</td>";
		echo "<td>".date("d-m-Y, H:i:s", $novelty['nShowEnd'])."</td>";
		echo "<td>{$novelty['nTS']}</td>";
		echo "<td><a href='/delete/news/{$novelty['nID']}' class='delete_link'>Удалить</a></td>";
		// echo "<td><a href='news/{$novelty['nID']}' target='_blank'>www.{$_SERVER['HTTP_HOST']}/news/{$novelty['nID']}</a></td>";
		echo "</tr>";
	}

?>

</table>


<?php include "footer.tpl" ?>