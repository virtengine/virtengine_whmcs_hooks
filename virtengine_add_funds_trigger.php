<?php

function add_funds_trigger( $vars ) {
	$e = new TransactionBill();
	$e->amountin  = $vars['amountin'];
	$e->amountout  = $vars['amountout'];
	$e->fees = $vars['fees'];
	$e->tranid = $vars['transid'];
	$e->trandate = $date;
	$e->currency_type = "USD";

	//Forming the signature
  $res = invoke_api("/v2/billingtransactions/content", $e);

  logActivity( json_encode( $res ) );
}


add_hook('AddTransaction',1,'add_funds_trigger');

class TansactionBill {
      public $amountin;
      public $amountout;
      public $fees;
      public $tranid;
      public $trandate;
      public $currency_type;
}


?>
