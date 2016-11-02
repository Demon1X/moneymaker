<?php include "header.tpl" ?>

<b>TIME <?=clock()?></b>
<h1><?=$_TRANS['Deposits']?></h1>

<div style="text-align: center;">
<a href="/new-deposit"><?= $_TRANS['Make Deposit'] ?></a>
</div>

<br><br>

<?php if (count($deposits) > 0 ) { ?>
<table class="user_deposits">

<tr>
<td><?=$_TRANS['Start']?></td>
<td><?=$_TRANS['Amount']?></td>
<td><?=$_TRANS['Plan']?></td>
<td><?=$_TRANS['Last accrual']?></td>
<td><?=$_TRANS['Next accrual']?></td>
<td><?=$_TRANS['Accruals count']?></td>
<td><?=$_TRANS['Status']?></td>
</tr>


<?php
	foreach ($deposits as $deposit)
	{
		$plan = get_plan ($deposit['dpID']);

		echo "<tr>";
		echo "<td>".date("d/m/Y H:i", $deposit['dBegin'])."</td>";
		echo "<td>$".number_format($deposit['dAmount'], 2, '.', '')."</td>";
		echo "<td>{$plan['pName']}</td>";
		if ($deposit['dEnd'] > 0 AND $deposit['dTotalWorked'] > 0)
		{
			echo "<td>".date("d/m/Y H:i", $deposit['dEnd'])."</td>";
			if ($deposit['dStatus'] > 0) 
				echo "<td>".date("d/m/Y H:i", $deposit['dEnd']+$plan['pPeriod']*3600)."</td>";
			else
				echo "<td> - </td>";
		}
		else
		{
			echo "<td> - </td>";
			echo "<td>".date("d/m/Y H:i", $deposit['dBegin']+$plan['pPeriod']*3600)."</td>";
		}
		
		echo "<td>{$deposit['dTotalWorked']}</td>";
		
		if ($deposit['dStatus'])
			echo "<td>".$_TRANS['Active']."</td>";
		else
			echo "<td>".$_TRANS['Close']."</td>";	
		
		echo "</tr>";
	}
?>

</table>
<br>
<?php } ?>

<?php include "footer.tpl" ?>