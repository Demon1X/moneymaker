<?php include "header.tpl" ?>

<h1><?= $_TRANS['Wallets'] ?></h1>

<?=	msg(); ?>

<?php 
	// Подсчитываем кол-во доступных ПС
	$count=0;
	foreach ($paysys as $ps) if ($ps['psHidden'] == 0 ) $count++;

	if ($count) { 

?>

<form action="wallets" method="post">
<table class="normalTable">

<!-- Perfect Money -->
<?php 
	$ps = get_paysys (1);
	if ($ps['psHidden'] == 0) {
?>
<tr>
<td>Perfect Money</td>
<td><input name="perfect" type="text" value="<?=$user['uPerfectMoney']?>" placeholder="U12345678"></td>
</tr>	
<?php } ?>

<!-- Payeer -->
<?php 
	$ps = get_paysys (2);
	if ($ps['psHidden'] == 0) {
?>
<tr>
<td>Payeer</td>
<td><input name="payeer" type="text" value="<?=@$user['uPayeer']?>" placeholder="P12345678"></td>
</tr>	
<?php } ?>


<tr>	
<td>&nbsp;</td>
<td>
<input name="token" type="hidden" value="<?= set_token() ?>" />
<input type="submit" name="save" value="<?= $_TRANS['Save'] ?>">
</td>
</tr>	

</table>

</form>
<?php } ?>


<?php include "footer.tpl" ?>