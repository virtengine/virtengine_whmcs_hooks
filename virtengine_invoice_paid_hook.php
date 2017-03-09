include(ROOTDIR.'/includes/hooks/virtengine_common_function.php');
<?php
function invoice_paid($vars) {
  add_data($vars);
}
add_hook("InvoicePaid",1,"invoice_paid");

?>
