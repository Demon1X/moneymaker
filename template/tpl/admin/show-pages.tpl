<?php include "header.tpl" ?>

<h2>Страницы</h2>

<a href="/make-page" >Создать новую страницу</a><br><br>


<table class="allPages">
<tr>
<th>ID</th>
<th>Страница</th>
<th>Скрыта</th>
<th>Ссылка</th>
<th>Удалить</th>
</tr>

<?php
	foreach ($pages as $page)
	{
		echo "<tr>";
		echo "<td>{$page['pID']}</td>";
		echo "<td><a href='/edit-page/{$page['pID']}'>{$page['pTopic']}</a></td>";
		echo "<td>{$page['pHidden']}</td>";
		echo "<td><a href='/page/{$page['pID']}' target='_blank'>www.{$_SERVER['HTTP_HOST']}/page/{$page['pID']}</a></td>";
		echo "<td><a href='/delete/pages/{$page['pID']}' class='delete_link'>Удалить</a></td>";
		echo "</tr>";
	}
?>

</table>


<?php include "footer.tpl" ?>
