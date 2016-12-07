include(ROOTDIR.'/includes/hook/virtengine_api.php');
include(ROOTDIR.'/includes/hook/virtengine_db.php');
<?php
function verify_email($vars) {
$email = $vars['email'];
$user_id =  $vars['userid'];
$res = invoke_api('/v2/accounts/$email',$email, $user_id);
logActivity( json_encode( $res ) );
if($res){
$error = "Email alredy exit in vertice";
return $error;
}
}

add_hook('ClientDetailsValidation',0,'verify_email');

?>
