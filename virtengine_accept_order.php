
include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');

<?php

function after_accept_order($vars) {
    logActivity("=Debug: ---  Accept order: STARTS");
    logActivity("=Debug: ---  Parms:".$vars);

    $order_id= $vars['orderid'];

    logActivity("=Debug: order is:".$order_id);

    $orders = fetch_by_id('tblorders', $order_id)

    logActivity("=Debug: client products is:".json_encode($orders));

    $user_id = $orders['userid'];

    $products = getClientProducts($user_id)

    logActivity("=Debug: products is:".json_encode($products));

    //$product_details = fetch_by_id('tblproducts', $product_id)

    //logActivity("=Debug: products is:".json_encode($product_details));

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

function getClientProducts($clientid) {

  $where = array();

  if ($clientid) {
      $where["tblhosting.userid"] = $clientid;
  }

$result = select_query("tblhosting", "COUNT(*)", $where, "", "", "", "tblproducts ON tblproducts.id=tblhosting.packageid INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid");
logActivity("=Debug: first query result is:".json_encode($result));
$data = mysql_fetch_array($result);
logActivity("=Debug: first query data is:".json_encode($data));
$totalresults = $data[0];
$limitstart = (int)$limitstart;
$limitnum = (int)$limitnum;

if (!$limitnum) {
	$limitnum = 999999;
}

$result = select_query("tblhosting", "tblhosting.*,tblproducts.name AS productname,tblproductgroups.name AS groupname,(SELECT CONCAT(name,'|',ipaddress,'|',hostname) FROM tblservers WHERE tblservers.id=tblhosting.server) AS serverdetails,(SELECT tblpaymentgateways.value FROM tblpaymentgateways WHERE tblpaymentgateways.gateway=tblhosting.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1) AS paymentmethodname", $where, "tblhosting`.`id", "ASC", "" . $limitstart . "," . $limitnum, "tblproducts ON tblproducts.id=tblhosting.packageid INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid");
logActivity("=Debug: second query result is:".json_encode($result));
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

 return $apiresults

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
