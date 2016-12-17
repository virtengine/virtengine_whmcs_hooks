include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php

 function createProduct($vars) {

     $orderId= $vars['OrderID'];
     $user_id= $vars['userid']
     $e = new Product();
     $e->id = $vars['email'];
     $e->account_id = $vars['account_id'];
     $e->name = $vars['email'];
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

 add_hook("AfterShoppingCartCheckout",1,"createProduct");

 class Product {
       public $id;
       public $account_id;
       public $name;
       public $cost;
       public $allowed;
       public $ram;
       public $cpu;
       public $disk;
       public $disk_type;
       public $allocated_to;
       public $inputs;
       public $created_at;
       public $updated_at;
 }

 ?>
