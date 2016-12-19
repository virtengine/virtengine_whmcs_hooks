include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php
function after_shopping_cart_complete($vars) {
  logActivity("=Debug: ---  Shipping cart complete: STARTS");
  $order_id= $vars['orderid'];
  $user_id= $vars['userid'];
  $order = fetch_by_id($order_id);
  logActivity("=Debug: order is:".$order);
    $e = new Quotas();
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
}
add_hook("ShoppingCartCheckoutCompletePage",1,"after_shopping_cart_complete");
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
