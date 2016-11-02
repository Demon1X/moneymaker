<?php include "header.tpl" ?>

<h1><?= $_TRANS['History'] ?></h1>


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
			if ($oper['oStatus'] == 2) echo "<td>".$_TRANS['Сanceled']."</td>";
			if ($oper['oStatus'] == 3) echo "<td>".$_TRANS['Success']."</td>";
			echo "<td>{$oper['oBatch']}</td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}	

?>

<br>

<?php include "footer.tpl" ?>