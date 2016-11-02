<?php include "header.tpl" ?>

<h1><?= $_TRANS['Add funds'] ?></h1>


<?php
	// Печатаем сообщение с ошибкой, если она есть
	if (isset($_SESSION['bad_msg'])) 
	{
		$GLOBALS['bad_msg']=$_SESSION['bad_msg'];
		echo msg(); 
		unset($_SESSION['bad_msg']);
	}		
	
	// Подсчитываем кол-во доступных ПС
	$count=0;
	foreach ($paysys as $ps) if ($ps['psHidden'] == 0 ) $count++;

	if ($count) { 

?>

<form action="pay" method="post">

<table class="new_depo">

<tr>
<td><?=$_TRANS['Payment system']?>&nbsp;</td>
<td>
<?php 
	//Если кол-во ПС больше 1ой, то делаем список
	if ($count > 1)
	{
		echo '<select name="paysys" style="width: 155px;">';
		foreach ($paysys as $ps)
		{
			if ($ps['psHidden'] == 0 ) echo "<option value='".$ps['psID']."'>".$ps['psName']."</option>";
		}
		echo '</select>';
	}
	else
	{	
		// Если одна, то делаем одно скрытое поле
		foreach ($paysys as $ps)
		{
			if ($ps['psHidden'] == 0 )
			{
				echo "<input name='paysys' type='hidden' value='{$ps['psID']}'>";
				echo $ps['psName'];
			}
		}
	}		
?>
</td>			
</tr>


<tr>
<td><?=$_TRANS['Amount']?> &nbsp;</td>
<td><input name="amount" type="text"></td>
</tr>

<tr>	
<td>&nbsp;</td>
<td>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="addfunds" value="<?=$_TRANS['Create']?>">
</td>
</tr>

</table>

</form>
<?php } ?>

<br>
<?php 
	if (count($operations))
	{
		echo "<table class='history'>";
		
		echo "<tr>";
		echo "<td>".$_TRANS['Time']."</td>";
		echo "<td>".$_TRANS['Operation']."</td>";
		echo "<td>".$_TRANS['Amount']."</td>";
		echo "<td>".$_TRANS['Memo']."</td>";
		echo "<td>".$_TRANS['Status']."</td>";
		echo "<td>".$_TRANS['Batch']."</td>";
		echo "</tr>";
		
		foreach ($operations as $oper)
		{
			echo "<tr>";
			echo "<td>".date("d/m/Y H:i", $oper['oTime'])."</td>";
			echo "<td>".$_TRANS[$oper['oOperation']]."</td>";
			echo "<td>$".number_format($oper['oSum'], 2, '.', '')."</td>";
			echo "<td>{$oper['oMemo']}</td>";
			if ($oper['oStatus'] == 1) echo "<td>".$_TRANS['Wait']."</td>";
			if ($oper['oStatus'] == 2) echo "<td>".$_TRANS['Fail']."</td>";
			if ($oper['oStatus'] == 3) echo "<td>".$_TRANS['Success']."</td>";
			echo "<td>{$oper['oBatch']}</td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}	

?>
<br>

<?php include "footer.tpl" ?>