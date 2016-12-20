include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');

<?php

function verify_email($vars) {
    $email = $vars['email'];

    $res = invoke_api('/v2/accounts/'.$email,$email, $email);

    logActivity( json_encode( $res ) );

    if($res){
      $error = "Email already exits in VirtEngine";
      return $error;
    }
}


//add_hook('ClientDetailsValidation',0,'verify_email');

?>
