include(ROOTDIR.'/includes/hook/virtengine_api.php');
include(ROOTDIR.'/includes/hook/virtengine_db.php');

<?php

function create_addon( $vars ) {
  $e = new Addon();
  $e->name->first_name  = $vars['firstname'];

  $res = invoke_api('/addons/content', $e);

  logActivity( json_encode( $res ) );
}

add_hook('ClientAdd',1,'create_addon_account');

class Addon {
      public $account_id;
      public $provider_name;
      public $id;
      public $options;
      public $create_at;
      public $provider_id;
}

?>
