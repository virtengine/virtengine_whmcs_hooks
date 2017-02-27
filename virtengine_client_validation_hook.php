include(ROOTDIR.'/includes/hook/virtengine_api.php');
include(ROOTDIR.'/includes/hook/virtengine_db.php');
<?php
function verify_email($vars) {
  $custom_fields = base64_decode($vars['customfields']);
 if(strlen($custom_fields) == 0)
 {
  $email = $vars['email'];
  $body = '';
  $res = invoke_api_get('/v2/accounts/'.$email,$body, $email);
  logActivity( json_encode( $res ) );
  logActivity("http_code -".$res['http_code']);
  if ($res['http_code'] !== 404) {
    $error = "Email alredy exists in Virtengine";
    return $error;
  }
else
{
return false;
}
}
}
add_hook('ClientDetailsValidation',0,'verify_email');
?>
