<?php include "header.tpl" ?>

<!-- TOTAL STATISITCS -->
<h2><?= $_TRANS['Statistics'] ?></h2>
<table class="stata">
<tr>
<td><?=$_TRANS['Days online']?></td>
<td><?=floor((time()-strtotime("10 Aug 2016"))/86400)?></td>
</tr>

<tr>
<td><?=$_TRANS['Members']?></td>
<td><?=total_users()?></td>
</tr>

<tr>
<td><?=$_TRANS['Investments']?></td>
<td>$<?=number_format(total_in(), 2, '.', '')?></td>
</tr>

<tr>
<td><?=$_TRANS['Withdrawal']?></td>
<td>$<?=number_format(total_out(), 2, '.', '')?></td>
</tr>

<tr>
<td><?=$_TRANS['Online members']?></td>
<td><?=rand(5, 8)?></td>
</tr>

<tr>
<td><?=$_TRANS['Last update']?></td>
<td><?=date("M d, Y", time())?></td>
</tr>

</table>


<!-- INVESTMENTS -->
<h2>Investments</h2>
<table class="stata">
<tr>
<td><?=$_TRANS['User']?></td>
<td><?=$_TRANS['Amount']?></td>
</tr>
<?php 
	foreach ($in as $oper)
	{
		echo "<tr>";
		echo "<td>".get_login($oper['ouID'])."</td>";
		echo "<td>$".number_format($oper['oSum'], 2, '.', '')."</td>";
		echo "</tr>";
	}
?>	
</table>


<!-- WITHDRAWALS -->
<h2>Withdrawals</h2>
<table class="stata">
<tr>
<td><?=$_TRANS['User']?></td>
<td><?=$_TRANS['Amount']?></td>
</tr>
<?php 
	foreach ($out as $oper)
	{
		echo "<tr>";
		echo "<td>".get_login($oper['ouID'])."</td>";
		echo "<td>$".number_format($oper['oSum'], 2, '.', '')."</td>";
		echo "</tr>";
	}
?>	
</table>

<br>

<?php include "footer.tpl" ?>
