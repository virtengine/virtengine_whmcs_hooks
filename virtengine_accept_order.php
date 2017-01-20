include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');

<?php
function after_accept_order($vars) {
    $order_id= $vars['orderid'];
    $orders = fetch_by_id('tblorders', $order_id);
    $user_id = $orders['userid'];
    $products = getClientProducts($user_id, $order_id);
    $product_details = fetch_by_id('tblproducts', $products['products']['product'][0]['pid']);
    $vertice_email = fetch_user($user_id);

    $e = new OrderedQuotas();
    $e->account_id = $vertice_email;
    $e->name = $product_details['name'];
    $e->allowed = parse_allowed($product_details['description']);
    $e->allocated_to = " ";
    $e->inputs = [];
    $res = invoke_api('/v2/quotas/content',$e, $user_id);
    logActivity( json_encode( $res ) );
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
        if (strpos($name,, CLOUD_ONDEMAND) == false) {
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
  }
 return $apiresults;
}

add_hook("AcceptOrder",1,"after_accept_order");

class OrderedQuotas {
      public $account_id;
      public $name;
      public $allowed;
      public $allocated_to;
      public $inputs;
}
?>
