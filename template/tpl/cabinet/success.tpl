<?php include "header.tpl" ?>

<h1><?= $_TRANS['Success'] ?></h1>

<div style="text-align: center;">

<?php
	if (isset($_REQUEST['V2_HASH']))
	{
 		echo "AMOUNT: $".$_REQUEST['PAYMENT_AMOUNT']."<br>";
		echo "BATCH: ".$_REQUEST['PAYMENT_BATCH_NUM']."<br>";
 	}
?>

</div>

<?php include "footer.tpl" ?>