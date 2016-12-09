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
        $org_id = fetchFieldByName('org_id', $vars['userid']);
        if (empty($org_id))
        {
          $res = invoke_api('/v2/addons/content', $e,  $user_id);
          logActivity( json_encode( $res ) );
        }
        else
        {
          return false;
        }
    }

//add_hook('ClientAdd',1,'create_addon');

class Addon {
      public $account_id;
      public $provider_name;
      public $id;
      public $options;
      public $create_at;
      public $provider_id;
}

?>
