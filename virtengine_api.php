include(ROOTDIR.'/includes/hooks/virtengine_db.php');
<?php
//====== TO_DO: START: PLEASE CUSTOMIZE THIS AS PER YOUR SITE.
define(MASTER_KEY, '3b8eb672aa7c8db82e5d34a0744740b20ed59e1f6814cfb63364040b0994ee3f');
define(GATEWAY, '146.0.247.2:9000');
//====== TO_DO: END: PLEASE CUSTOMIZE THIS AS PER YOUR SITE.
define (CLOUD_ONDEMAND, "Cloud On demand billing");
function build_hmac($api_url, $data, $user_id) {
//Converting the body into md5 hash
  $body_digest = openssl_digest( $data,'md5', true );
  //Encoding the body_digest with base64 encde
  $encoded_body = str_replace(array('+', '/'), array('-', '_'), base64_encode($body_digest));
  //Current date for example
  $current_date = date('Y-m-d H:i');
  //Forming the signature
  $signature = $current_date . "\n" .$api_url. "\n" . $encoded_body;
  //Get Client Custom Fields
  $vertice_apikey = (MASTER_KEY);
  $vertice_email = fetch_user($user_id);
  logActivity("=Debug: ---  build_hmac ".$api_url);

  //Creating HMAC hash with sha256
  $hash = hash_hmac( 'sha256', rtrim($signature), $vertice_apikey );
  //Final Hmac
  $final_hmac = $vertice_email . ':' . $hash;
  //we are sticking it in a hash
  $built=array();
  $built['final_hmac']=$final_hmac;
  $built['current_date']=$current_date;
  return $built;
}
function build_header($headerArgs, $user_id) {
  $final_hmac = $headerArgs['final_hmac'];
  $current_date = $headerArgs['current_date'];
  $organization_id = fetchFieldByName('org_id',$user_id);
  logActivity("=Debug: ---  build_header");
  logActivity("=Debug: final_hmac:".$final_hmac);
  logActivity("=Debug: currrent_date:".$current_date);
  $headers =  array(
    'Accept: application/json',
    'Content-Type: application/json',
    'Accept-Encoding: gzip',
    'User-Agent: megam-api/v2',
    'X-Megam-DATE: '.$current_date,
    'X-Megam-HMAC: '.$final_hmac,
    'X-Megam-ORG: '.$organization_id,
    'X-Megam-MASTERKEY: true');
    return $headers;
}
function invoke_api($api_url, $body_json, $user_id) {
  logActivity("=Debug: ---  Vertice API: STARTS");
  logActivity("=Debug: ---  API Parms:".json_encode($body_json));
  logActivity("=Debug: ---  API Userid:".$user_id);
  $data = json_encode($body_json);
  $headerArgs = build_hmac($api_url,$data, $user_id);
  $headers = build_header($headerArgs, $user_id);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, (GATEWAY).$api_url);
  curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);
  $get_info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error ( $ch );
  curl_close($ch);
  return  array('Request: ' => $body_json, 'Api Response : ' => $response,'Http Code : ' => $get_info,'Curl Error : ' => $curl_error);
}
?>
