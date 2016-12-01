include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');

<?php

function create_addon( $vars ) {
        $e = new Addon();
        $e->account_id = $vars['email'];
        $e->id = null;
        $e->provider_name = "WHMCS";
        $e->provider_id = $vars['userid'];
        $e->options = null;
        $e->created_at = null;
        $user_id = $vars['userid'];
        $res = invoke_api('/addons/content', $e, $user_id);
        logActivity( json_encode( $res ) );
}

add_hook('ClientAdd',1,'create_addon');

class Addon {
      public $account_id;
      public $provider_name;
      public $id;
      public $options;
      public $create_at;
      public $provider_id;
}

?>
