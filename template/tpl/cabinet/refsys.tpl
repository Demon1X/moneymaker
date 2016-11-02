<?php include "header.tpl" ?>

<h1><?=$_TRANS['Friends']?></h1>

<div class="refsys">
<?=$_TRANS['Referral links'].': '?> <?php echo '<a href="http://'.$_SERVER['SERVER_NAME'].'/ref/'.get_my_login().'">http://'.$_SERVER['SERVER_NAME'].'/ref/'.get_my_login().'</a>'; ?>
<br><br>
<?=$_TRANS['Referrals']?>: <?=ref_count($_SESSION['UID'])?><br>
<?=$_TRANS['Total referral commission']?>: <?="$".number_format(ref_total($_SESSION['UID']), 2, '.', '')?>
</div>

<!--
<br>
<table class="refsys_table">
<tr>
<td><?=$_TRANS['User']?></td>
<td><?=$_TRANS['Registration']?></td>
<td><?=$_TRANS['Deposits']?></td>
<td><?=$_TRANS['Amount']?></td>
</tr>
</table>
-->
<br>
<?php 
	if (count($operations))
	{
		echo "<h2>".$_TRANS['History']."</h2>";
		echo "<table class='ref'>";
		
		echo "<tr>";
		echo "<td>".$_TRANS['Time']."</td>";
		echo "<td>".$_TRANS['Operation']."</td>";
		echo "<td>".$_TRANS['Amount']."</td>";
		echo "<td>".$_TRANS['Memo']."</td>";
		echo "</tr>";
		
		foreach ($operations as $oper)
		{
			echo "<tr>";
			echo "<td>".date("d/m/Y H:i", $oper['oTime'])."</td>";
			echo "<td>".$_TRANS[$oper['oOperation']]."</td>";
			echo "<td>$".number_format($oper['oSum'], 2, '.', '')."</td>";
			echo "<td>{$oper['oMemo']}</td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}	

?>
<br>

<?php include "footer.tpl" ?>