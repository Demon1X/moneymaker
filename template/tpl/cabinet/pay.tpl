<?php include "header.tpl" ?>

<h1>PAY</h1>

<?=	msg(); ?>

<div style="text-align: center;">

<!-- PERFECT MONEY SCI -->
<?php if ($_REQUEST['paysys'] == 1) { ?>
	<form action="https://perfectmoney.is/api/step1.asp" method="POST" name="SCI_FORM">
	<input type="hidden" name="PAYEE_ACCOUNT" value="<?=PERFECT_ID?>">
	<input type="hidden" name="PAYEE_NAME" value="<?=PERFECT_NAME?>">
	<input type="hidden" name="PAYMENT_ID" value="<?=$oper_id?>">
	<input type="hidden" name="PAYMENT_AMOUNT" value="<?=acomma($_REQUEST['amount'])?>">
	<input type="hidden" name="PAYMENT_UNITS" value="USD">
	<input type="hidden" name="STATUS_URL" value="http://<?=$_SERVER['HTTP_HOST']?>/status">
	<input type="hidden" name="PAYMENT_URL" value="http://<?=$_SERVER['HTTP_HOST']?>/success">
	<input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
	<input type="hidden" name="NOPAYMENT_URL" value="http://<?=$_SERVER['HTTP_HOST']?>/fail">
	<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
	<input type="hidden" name="SUGGESTED_MEMO" value="payment from <?=get_my_login()?>">
	<input type="hidden" name="BAGGAGE_FIELDS" value="">
	<input type="submit" name="PAYMENT_METHOD" value="Pay Now!">
	</form>
<?php } ?>

</div>

<!-- Auto Sumbit form -->
<script>
 document.SCI_FORM.submit();
</script>
<?php include "footer.tpl" ?>