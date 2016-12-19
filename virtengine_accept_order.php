
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