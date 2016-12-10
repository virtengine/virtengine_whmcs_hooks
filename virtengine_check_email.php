include(ROOTDIR.'/includes/hooks/virtengine_api.php');
include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php
function verify_email($vars) {
$email = $vars['email'];
//here we dont have user_id to pass
$res = invoke_api('/v2/accounts/'.$email,$email, $email);
logActivity( json_encode( $res ) );
if($res){
$error = "Email alredy exit in vertice";
return $error;
}
}

//add_hook('ClientDetailsValidation',0,'verify_email');

?>
