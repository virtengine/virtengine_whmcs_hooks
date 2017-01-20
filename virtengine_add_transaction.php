include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php

function after_add_transaction( $vars ) {
        $invoiceid = $vars['invoiceid'];
        $invoice_id = isproduct_cod($invoiceid);
        $quota_array = array(array('key' => "quota_based", 'value' => $invoice_id));
        $date = date('d/m/Y', strtotime($vars['date']));

        $e = new TransactionBill();
        $e->gateway = $vars['gateway'];
        $e->amountin  = $vars['amountin'];
        $e->amountout  = $vars['amountout'];
        $e->fees = $vars['fees'];
        $e->tranid = $vars['transid'];
        $e->trandate = $date;
        $e->currency_type = "USD";
        $e->inputs = $quota_array;
        $user_id = $vars['userid'];

				//Forming the signature
				  $res = invoke_api("/v2/billingtransactions/content", $e, $user_id);
				  logActivity( json_encode( $res ) );
	}


  add_hook('AddTransaction',1,'after_add_transaction');

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
