include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php
define (CLOUD_ONDEMAND, "Cloud On demand billing");
function after_add_transaction( $vars ) {
        logActivity("=Debug: ---  Add transaction: STARTS");
        logActivity("=Debug: ---  Parms:".$vars);
        $invoiceid = $vars['invoiceid'];
        $invoice_id = getInvoiceItems($invoiceid);
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
				function getInvoiceItems($invoiceid) {
				$result = select_query("tblinvoiceitems", "", array("invoiceid" => $invoiceid));
				$data = mysql_fetch_array($result);
				$description = $data['description'];
				if (strpos($description, CLOUD_ONDEMAND) !== false) {
				   return 'false';
				} else {
				return 'true';
				}
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
