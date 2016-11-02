<?php include "header.tpl" ?>
<h1>Админка <?=SITE_NAME?></h1>

<table class="global_stat">
<tr>
<td>Пользователи</td>
<td> <?=total_users()?></td>
</tr>

<tr>
<td>Боты</td>
<td><?=total_bots()?></td>
</tr>

<tr>
<td>Депозиты</td>
<td>$<?=number_format(total_depo(), 2, '.', '')?></td>
</tr>


<tr>
<td>CRON</td>
<td><?php echo floor((time() - CRON) / 60); ?> min</td>
</tr>

<tr>
<td>Perfect Money</td>
<td>
<?php 
	if ($paysys[0]['psDisabled'] != 1)
	{	
		$perfect = test_perfect ();
		if (isset($perfect[PERFECT_WALLET])) 
			echo '$'.$perfect[PERFECT_WALLET];
		else
            echo $perfect['ERROR'];
			//print_r ($perfect);
	}
	else echo 'Disabled';
?>
</td>
</tr>

<tr>
<td>Payeer</td>
<td>$0</td>
</tr>

</table>

<?php include "footer.tpl" ?>