
include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');

<?php

function after_accept_order($vars) {
    logActivity("=Debug: ---  Accept order: STARTS");
    logActivity("=Debug: ---  Parms:".$vars);

    $order_id= $vars['orderid'];

    $user_id= $vars['userid']

    logActivity("=Debug: user is:".$user_id);

    logActivity("=Debug: order is:".$order_id);

    $client_order_details = fetch_by_id('tblclientdetails', $user_id)

    logActivity("=Debug: client products is:".json_encode($client_order_details));

    $product_id = fetch_column_in_result($client_order_details, 'products', 'pid')

    logActivity(json_encode($product_id));

    $product_details = fetch_by_id('tblproducts', $product_id)

    logActivity("=Debug: products is:".json_encode($product_id));

    /*$e = new Quotas();
    $e->id = $vars['email'];
    $e->account_id = $vars['account_id'];
    $e->name = $vars['name'];
    $e->cost = $vars['cost'];
    $e->allowed->cpu = $vars['cpu'];
    $e->allowed->ram = $vars['email'];
    $e->allowed->disk = $vars['disk'];
    $e->allowed->disk_type = $vars['disk_type'];
    $e->allocated_to = " ";
    $e->inputs = [];
    $e->created_at = " ";
    $e->updated_at = " ";

    $res = invoke_api('/v2/quotas/content',$e, $user_id);
    logActivity( json_encode( $res ) );
    */
}

function getClientProducts() {
$result = select_query("tblhosting", "COUNT(*)", $where, "", "", "", "tblproducts ON tblproducts.id=tblhosting.packageid INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid");
$data = mysql_fetch_array($result);
$totalresults = $data[0];
$limitstart = (int)$limitstart;
$limitnum = (int)$limitnum;

if (!$limitnum) {
	$limitnum = 999999;
}

$result = select_query("tblhosting", "tblhosting.*,tblproducts.name AS productname,tblproductgroups.name AS groupname,(SELECT CONCAT(name,'|',ipaddress,'|',hostname) FROM tblservers WHERE tblservers.id=tblhosting.server) AS serverdetails,(SELECT tblpaymentgateways.value FROM tblpaymentgateways WHERE tblpaymentgateways.gateway=tblhosting.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1) AS paymentmethodname", $where, "tblhosting`.`id", "ASC", "" . $limitstart . "," . $limitnum, "tblproducts ON tblproducts.id=tblhosting.packageid INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid");
$apiresults = array("result" => "success", "clientid" => $clientid, "serviceid" => $serviceid, "pid" => $pid, "domain" => $domain, "totalresults" => $totalresults, "startnumber" => $limitstart, "numreturned" => mysql_num_rows($result));

if (!$totalresults) {
	$apiresults['products'] = "";
}



while ($data = mysql_fetch_array($result)) {
	$id = $data['id'];
	$userid = $data['userid'];
	$orderid = $data['orderid'];
	$pid = $data['packageid'];
	$name = $data['productname'];
	$groupname = $data['groupname'];
	$server = $data['server'];
	$regdate = $data['regdate'];
	$domain = $data['domain'];
	$paymentmethod = $data['paymentmethod'];
	$paymentmethodname = $data['paymentmethodname'];
	$firstpaymentamount = $data['firstpaymentamount'];
	$recurringamount = $data['amount'];
	$billingcycle = $data['billingcycle'];
	$nextduedate = $data['nextduedate'];
	$domainstatus = $data['domainstatus'];
	$username = $data['username'];
	$password = decrypt($data['password']);
	$notes = $data['notes'];
	$subscriptionid = $data['subscriptionid'];
	$promoid = $data['promoid'];
	$ipaddress = $data['ipaddress'];
	$overideautosuspend = $data['overideautosuspend'];
	$overidesuspenduntil = $data['overidesuspenduntil'];
	$ns1 = $data['ns1'];
	$ns2 = $data['ns2'];
	$dedicatedip = $data['dedicatedip'];
	$assignedips = $data['assignedips'];
	$diskusage = $data['diskusage'];
	$disklimit = $data['disklimit'];
	$bwusage = $data['bwusage'];
	$bwlimit = $data['bwlimit'];
	$lastupdate = $data['lastupdate'];
	$serverdetails = $data['serverdetails'];
	$serverdetails = explode("|", $serverdetails);
	$customfieldsdata = array();
	$customfields = getCustomFields("product", $pid, $id, "on", "");
	foreach ($customfields as $customfield) {
		$customfieldsdata[] = array("id" => $customfield['id'], "name" => $customfield['name'], "value" => $customfield['value']);
	}
  }
}

add_hook("AcceptOrder",1,"after_accept_order");

class Quotas {
      public $id;
      public $account_id;
      public $name;
      public $cost;
      public $allowed;
      public $allocated_to;
      public $inputs;
      public $created_at;
      public $updated_at;
}

?>
