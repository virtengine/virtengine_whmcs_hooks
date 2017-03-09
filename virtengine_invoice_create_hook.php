include(ROOTDIR.'/includes/hooks/virtengine_common_function.php');
<?php
function invoice_create($vars) {
  $data = fetch_data_invoiceItems("tblinvoiceitems",$invoiceid);
  if ($data['type'] == HOSTING){
  add_data($vars);
}
else {
  return false;
}
}
add_hook("InvoiceCreated",1,"invoice_create");
?>
