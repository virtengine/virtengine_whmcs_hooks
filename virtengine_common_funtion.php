include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php
function add_data($vars){
  $invoiceid= $vars['invoiceid'];
  $data = fetch_data_invoiceItems("tblinvoiceitems",$invoiceid);
  $invoice_data = fetch_data_by_id("tblinvoices",$invoiceid);
  $currency_type = fetch_data_by_id("tblcurrencies","1");
  $date = date('d/m/Y', strtotime($invoice_data['date']));

  if ($data['type'] == HOSTING){
    $orders = fetch_order_by_user_id('tblorders',$data['userid'], $invoiceid);
    $order_id = $orders['orders']['order'][0]['orderid'];
    $products = getClientProducts($data['userid'], $order_id);
    $product_details = fetch_data_by_id('tblproducts', $products['products']['product'][0]['pid']);
    $product_name =$product_details['name'];
    $product_description = parse_allowed($product_details['description']);
    $quota_type = "VM";
    $status = $invoice_data['status'];
  }
  else {
    $order_id = "";
    $product_name = "";
    $product_description = [];
    $quota_type = "";
    $status = "";
  }
  $e = new CommonData();
  $e->name = $product_name;
  $e->allowed = $product_description;
  $e->inputs = [];
  $e->quota_type = $quota_type;
  $e->status = $status;
  $e->orderid = $order_id;
  $e->key = $data["type"];
  $e->gateway = $data['paymentmethod'];
  $e->currency_type = $currency_type['code'];
  $e->trandate = $date;
  $e->amount = $data['amount'];
  logActivity(json_encode( $e ));
  $res = invoke_api('/v2/billings/content', $e ,$data['userid']);
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

  class CommonData {
        public $key;
        public $name;
        public $allowed;
        public $inputs;
        public $quota_type;
        public $status;
        public $orderid;
        public $gateway;
        public $amount;
        public $trandate;
        public $currency_type;
  }
?>
