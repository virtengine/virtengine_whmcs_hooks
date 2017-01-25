include(ROOTDIR.'/includes/hook/virtengine_api.php');
include(ROOTDIR.'/includes/hook/virtengine_db.php');
<?php
function verify_email($vars) {
  $custom_fields = base64_decode($vars['customfields']);
 if(strlen($custom_fields) == 0)
 {
  $email = $vars['email'];
  $res = invoke_api_get('/v2/accounts/'.$email,$email,$email);
  logActivity( json_encode( $res ) );
  logActivity("http_code -".$res['http_code']);
  if ($res['http_code'] == 200 || $res['http_code'] == 201) {
    $error = "Email alredy exit in vertice";
    return $error;
  }
}
}
add_hook('ClientDetailsValidation',0,'verify_email');
?>
