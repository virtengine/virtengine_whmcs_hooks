include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
include(ROOTDIR.'/includes/hooks/virtengine_accept_order.php');
<?php
function invoice_paid($vars) {
    $invoiceid= $vars['invoiceid'];

    if (isCloudOnDemand($invoiceid)) {
        after_add_transaction($vars)
    } else {
      $user_id = fetch_userid_for_invoiceitem($invoiceid);
      $orders = fetch_order_by_user_id('tblorders',$user_id, $invoiceid);
      $order_id = $orders['orders']['order'][0]['orderid'];
      $products = getClientProducts($user_id, $order_id);
      $product_details = fetch_by_id('tblproducts', $products['products']['product'][0]['pid']);
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
}

function fetch_userid_for_invoiceitem($invoiceid) {
				$result = select_query("tblinvoiceitems", "", array("invoiceid" => $invoiceid));
				$data = mysql_fetch_array($result);
	      $user_id = $data['userid'];
        return  $user_id;
}


function isproduct_cod($invoiceid) {
				$result = select_query("tblinvoiceitems", "", array("invoiceid" => $invoiceid));
				$data = mysql_fetch_array($result);
	      $description = $data['description'];
        $testing_cod = (strpos($description, CLOUD_ONDEMAND) !== false) ;
        logActivity("> testing cod =" + $testing_cod);
        return $testing_cod;
}

function fetch_order_by_user_id($tbl, $id, $invoice_id) {
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

add_hook("InvoicePaid",1,"invoice_paid");
class OrderQuota {
      public $account_id;
      public $name;
      public $allowed;
      public $allocated_to;
      public $inputs;
}
?>
