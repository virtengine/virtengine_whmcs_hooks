include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
include(ROOTDIR.'/includes/hooks/virtengine_accept_order.php');
<?php
function invoice_paid($vars) {
    logActivity("=Debug: ---  Accept order: STARTS");
    logActivity("=Debug: ---  Parms:".$vars);
    $invoiceid= $vars['invoiceid'];
    logActivity("=Debug: order is:".$invoiceid);
    $user_id = getInvoiceItem($invoiceid);
    logActivity("=Debug: client products is:".json_encode($user_id));
    $orders = fetch_by_user_id('tblorders',$user_id, $invoiceid);
    $order_id = $orders['orders']['order'][0]['orderid'];
    logActivity("=Debug: client products is:".$order_id);
    $products = getClientProducts($user_id, $order_id);
    $product_details = fetch_by_id('tblproducts', $products['products']['product'][0]['pid']);
    logActivity("=Debug: ordered product is:".json_encode($product_details));
    $vertice_email = fetch_user($user_id);
    $e = new OrderQuota();
    $e->account_id = $vertice_email;
    $e->name = $product_details['name'];
    $e->allowed = parse_allowed($product_details['description']);
    $e->allocated_to = " ";
    $e->inputs = [];
    $res = invoke_api('/v2/quotas/content',$e, $user_id);
    logActivity( json_encode( $res ));
}

function getInvoiceItem($invoiceid) {
				$result = select_query("tblinvoiceitems", "", array("invoiceid" => $invoiceid));
				$data = mysql_fetch_array($result);
				$description = $data['userid'];
return $description;
}

function fetch_by_user_id($tbl, $id, $invoice_id) {
$result = select_query($tbl, "", array("userid" => $id));
$apiresults = array();
while ($data = mysql_fetch_array($result)) {
	$id = $data['id'];
  $ordernum = $data['ordernum'];
	$userid = $data['userid'];
	$contactid = $data['contactid'];
	$date = $data['date'];
  $nameservers = $data['nameservers'];
	$transfersecret = $data['transfersecret'];
	$renewals = $data['renewals'];
	$promocode = $data['promocode'];
	$promotype = $data['promotype'];
	$promovalue = $data['promovalue'];
	$orderdata = $data['orderdata'];
	$amount = $data['amount'];
	$paymentmethod = $data['paymentmethod'];
	$invoiceid = $data['invoiceid'];
	$status = $data['status'];
  $ipaddress = $data['ipaddress'];
  $fraudmodule = $data['fraudmodule'];
  $fraudoutput = $data['fraudoutput'];
  $notes = $data['notes'];
  if ($invoiceid == $invoice_id) {
  $apiresults['orders']['order'][] = array(
    "orderid" => $id,
    "invoiceid" => $invoiceid,
    );
    }
  }
 return $apiresults;
}
}

add_hook("InvoicePaid",1,"invoice_paid");
class OrderQuota {
      public $account_id;
      public $name;
      public $allowed;
      public $allocated_to;
      public $inputs;
}
?>
