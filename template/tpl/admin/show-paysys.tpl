<?php include "header.tpl" ?>
<h2>Платежные системы</h2>

<table class="allPaySys">
<tr>
<td>ID</td>
<td>Наименование</td>
<td>Валюта</td>
<td>Отключен</td>
<td>Скрыт</td>
<td>Пополнение</td>
<td>Вывод</td>
<td>Тест</td>
</tr>

<?php
	foreach ($paysys as $ps)
	{
		echo "<tr>";
		echo "<td>".$ps['psID']."</td>";
		echo "<td><a href='/edit-paysys/{$ps['psID']}'>".$ps['psName']."</a></td>";
		echo "<td>".$ps['psCurrency']."</td>";
		
		if ($ps['psDisabled'])
			echo "<td>X</td>";
		else
			echo "<td>&nbsp;</td>";

		if ($ps['psHidden'])
			echo "<td>X</td>";
		else
			echo "<td>&nbsp;</td>";
		
		if ($ps['psCashInMode'])
			echo "<td>*</td>";
		else
			echo "<td>&nbsp;</td>";
		
		if ($ps['psCashOutMode'])
			echo "<td>*</td>";
		else
			echo "<td>&nbsp;</td>";

		echo "<td>TEST</td>";
		echo "</tr>";
	}
?>

</table>


<?php include "footer.tpl" ?>