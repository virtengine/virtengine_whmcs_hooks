<?php
function verify_email($vars) {
global $errormessage;
$errormessage .= "The e-mail address you have entered already exists in our system";
logActivity($errormessage);
return false;
}
add_hook('ClientDetailsValidation',1,'verify_email');

?>
