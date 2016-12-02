<?php

function add_funds_trigger( $vars ) {

	$date = date('d/m/Y', strtotime($vars['date']));
	$e = new TransactionBill();
	$e->gateway = $vars['gateway'];
	$e->amountin  = $vars['amountin'];
	$e->amountout  = $vars['amountout'];
	$e->fees = $vars['fees'];
	$e->tranid = $vars['transid'];
	$e->trandate = $date;
	$e->currency_type = "USD";
	$user_id = $vars['userid'];

	//Forming the signature
  $res = invoke_api("/v2/billingtransactions/content", $e, $user_id);

  logActivity( json_encode( $res ) );
}


add_hook('AddTransaction',1,'add_funds_trigger');

class TransactionBill {
	    public $gateway;
      public $amountin;
      public $amountout;
      public $fees;
      public $tranid;
      public $trandate;
      public $currency_type;
}


?>
