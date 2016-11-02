<?php include "header.tpl" ?>
<h2>Боты</h2>

<a href="/make-bot">Создать бота</a><br><br>

<table class="allBots">
<tr>
<td>Логин</td>
<td>Валюта</td>
<td>ЭПС</td>
<td>План</td>
<td>Сумма</td>
<td>Активен</td>
</tr>
<?php 
	foreach ($bots as $bot)
	{
		echo "<tr>";	
		
		echo "<td><a href='edit-bot/{$bot['uID']}'>".get_login($bot['uID'])."</a></td>";
		
		echo "<td>{$bot['Curr']}</td>";
		
		$ps = get_paysys($bot['psID']);
		echo "<td>{$ps['psName']}</td>";
		
		$plan = get_plan($bot['planID']);
		echo "<td>{$plan['pName']}</td>";
		
		echo "<td>{$bot['Amount']}</td>";
		
		if ($bot['Disabled']) 
			echo "<td>Отключен</td>";
		else
			echo "<td> Да </td>";
		
		echo "</tr>";	
	}
?>
</table>

<?php include "footer.tpl" ?>