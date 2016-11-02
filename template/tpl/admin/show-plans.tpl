<?php include "header.tpl" ?>
<h2>Планы</h2>

<a href="/make-plan" >Добавить</a>

<br><br>

<table class="allPlans">

<tr>
<th>ID</th>
<th>Группа</th>
<th>Скрыт</th>
<th>Наименование</th>
<th>Мин</th>
<th>Макс</th>
<th>Процент</th>
<th>Длит.</th>
<th>Круги</th>
<th>Возврат</th>
<th>Рефка от<br>депозита</th>
<th>Рефка c<br>профита</th>
</tr>

<?php 
	foreach ($plans as $plan)
	{
		echo "<tr>";	
		echo "<td>".$plan['pID']."</td>";
		echo "<td>".$plan['pGroup']."</td>";
		echo "<td>".$plan['pHidden']."</td>";
		echo "<td><a href='/edit-plan/".$plan['pID']."'>".$plan['pName']."</a></td>";
		echo "<td>".number_format($plan['pMin'], 2, '.', '')."</td>";
		echo "<td>".number_format($plan['pMax'], 2, '.', '')."</td>";
		echo "<td>".$plan['pPercent']."</td>";
		echo "<td>".$plan['pPeriod']."</td>";
		echo "<td>".$plan['pCycles']."</td>";
		echo "<td>".$plan['pReturn']."</td>";
		echo "<td>".$plan['pRefDepo']."</td>";
		echo "<td>".$plan['pRefProfit']."</td>";
		echo "</tr>";	
	}	
?>

</table>



<?php include "footer.tpl" ?>