<?php
function fetchFieldByName( $name, $userid ){
        if( empty($name) || empty($userid) )
                return false;
        $query = "SELECT cfv.value FROM tblcustomfieldsvalues cfv INNER JOIN tblcustomfields cf ON cfv.fieldid = cf.id
                  WHERE cfv.relid = '".$userid."' AND cf.fieldname = '".$name."'";
        $res = full_query($query);
        if( mysql_num_rows($res) > 0 ) {
          $row = mysql_fetch_assoc($res);
          return $row['value'];
        }
        return false;
}

function fetch_data_by_id($tbl, $id ) {
        if(empty($id))
           return false;
        $query = "SELECT * FROM ".$tbl." WHERE  id = '".$id."'";
        $res = full_query($query);
        if( mysql_num_rows($res) > 0 ) {
          $row = mysql_fetch_assoc($res);
          return $row;
        }
        return false;
}

function fetch_data_invoiceItems($tbl, $id ) {
        if(empty($id))
           return false;
        $query = "SELECT * FROM ".$tbl." WHERE  invoiceid = '".$id."'";
        $res = full_query($query);
        if( mysql_num_rows($res) > 0 ) {
          $row = mysql_fetch_assoc($res);
          return $row;
        }
        return false;
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
  if ($invoiceid == $invoice_id || $invoiceid == 0) {
  $apiresults['orders']['order'][] = array(
    "orderid" => $id,
    "invoiceid" => $invoiceid,
    );
    }
  }
 return $apiresults;
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
          );
        }
  }
 return $apiresults;
}

?>
