<?php
	require_once 'lib/paysys.php';
		
	// Подтверждение пополенине баланса с Perfect Money
	if (isset($_REQUEST['V2_HASH']))
	{
		$PAYMENT_ID = $_REQUEST['PAYMENT_ID'];
        $PAYEE_ACCOUNT = $_REQUEST['PAYEE_ACCOUNT'];
        $PAYMENT_AMOUNT = $_REQUEST['PAYMENT_AMOUNT'];
        $PAYMENT_CURR = $_REQUEST['PAYMENT_UNITS'];
        $PAYMENT_BATCH = $_REQUEST['PAYMENT_BATCH_NUM']; 
        $PAYER_ACCOUNT = $_REQUEST['PAYER_ACCOUNT'];
        $TIMESTAMPGMT  = $_REQUEST['TIMESTAMPGMT'];

		$ALT_HASH = PERFECT_ALT;
		$ALT_HASH = mb_strtoupper(md5($ALT_HASH));
        
        $CHECK_HASH = $PAYMENT_ID.":".$PAYEE_ACCOUNT.":".$PAYMENT_AMOUNT.":".$PAYMENT_CURR.":".$PAYMENT_BATCH.":".$PAYER_ACCOUNT.":".$ALT_HASH.":".$TIMESTAMPGMT;
        $CHECK_HASH = mb_strtoupper(md5($CHECK_HASH));
        
        if ($_REQUEST['V2_HASH'] == $CHECK_HASH)
        {
	 		echo "Sucess<br>";
			echo "AMOUNT: $".$_REQUEST['PAYMENT_AMOUNT']."<br>";
			echo "BATCH: ".$_REQUEST['PAYMENT_BATCH_NUM']."<br>";
			plus ($_REQUEST['PAYMENT_ID'], $_REQUEST['PAYMENT_BATCH_NUM']);
        }
		else
			echo "Invalid v2_hash";
	}
	
?>