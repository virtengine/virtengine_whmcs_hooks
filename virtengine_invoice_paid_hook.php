include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php
function invoice_paid($vars) {
    $invoiceid= $vars['invoiceid'];
    $tranaction_data = fetch_data_for_transaction("tblaccounts",$invoiceid);
    common_add_transaction($tranaction_data);
    if (isproduct_cod($invoiceid) == "true") {
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
      $res = invoke_api('/v2/quotas/content',$e,$vertice_email,$user_id);
      logActivity( json_encode( $res ));

}
}
function common_add_transaction( $vars ) {
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
        $email = fetch_user($user_id);
				//Forming the signature
				  $res = invoke_api("/v2/billingtransactions/content", $e,$email,$user_id);
				  logActivity( json_encode( $res ) );
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
  if (strpos($description, CLOUD_ONDEMAND) !== false) {
     return 'false';
  } else {
  return 'true';
  }
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

function parse_allowed($string) {
  $array = explode("\n", $string);
  $result = array();
  foreach ($array as $value) {
    $arr = explode('-', $value);
     array_push($result, array("key" => trim($arr[0]), "value" => trim($arr[1])));
  }
  return $result;
 }

function getClientProducts($client_id, $order_id) {
  $where = array();
  if ($client_id) {
      $where["tblhosting.userid"] = $client_id;
  }

  $result = select_query("tblhosting", "COUNT(*)", $where, "", "", "", "tblproducts ON tblproducts.id=tblhosting.packageid INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid");

  $data = mysql_fetch_array($result);
  $totalresults = $data[0];
  $limitstart = (int)$limitstart;
  $limitnum = (int)$limitnum;
  if (!$limitnum) {
	   $limitnum = 999999;
   }

   $result = select_query("tblhosting", "tblhosting.*,tblproducts.name AS productname,tblproductgroups.name AS groupname,(SELECT CONCAT(name,'|',ipaddress,'|',hostname) FROM tblservers WHERE tblservers.id=tblhosting.server) AS serverdetails,(SELECT tblpaymentgateways.value FROM tblpaymentgateways WHERE tblpaymentgateways.gateway=tblhosting.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1) AS paymentmethodname", $where, "tblhosting`.`id", "ASC", "" . $limitstart . "," . $limitnum, "tblproducts ON tblproducts.id=tblhosting.packageid INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid");

   $apiresults = array();
   while ($data = mysql_fetch_array($result)) {
	    $id = $data['id'];
	    $userid = $data['userid'];
	    $orderid = $data['orderid'];
	    $pid = $data['packageid'];
	    $name = $data['productname'];
      $groupname = $data[''];
	    $promoid = $data['promoid'];
	    $ipaddress = $data['ipaddress'];
	    $dedicatedip = $data['dedicatedip'];
	    $assignedips = $data['assignedips'];
	    $diskusage = $data['diskusage'];
	    $disklimit = $data['disklimit'];
	    $bwusage = $data['bwusage'];
	    $bwlimit = $data['bwlimit'];
	    $lastupdate = $data['lastupdate'];
	    $serverdetails = $data['serverdetails'];
	    $serverdetails = explode("|", $serverdetails);

      if ($order_id == $orderid) {
          $apiresults['products']['product'][] = array(
            "id" => $id,
            "clientid" => $userid,
            "orderid" => $orderid,
            "pid" => $pid,
            "name" => $name,
            "groupname" => $groupname,
            "status" => $domainstatus,
            "promoid" => $promoid,
            "dedicatedip" => $dedicatedip,
            "assignedips" => $assignedips,
            "notes" => $notes,
            "diskusage" => $diskusage,
            "disklimit" => $disklimit,
            "bwusage" => $bwusage,
            "bwlimit" => $bwlimit,
            "lastupdate" => $lastupdate,
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
