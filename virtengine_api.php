<?php

//====== TO_DO: START: PLEASE CUSTOMIZE THIS AS PER YOUR SITE.
define(MASTER_KEY, '3b8eb672aa7c8db82e5d34a0744740b20ed59e1f6814cfb63364040b0994ee3f');
define(GATEWAY, '13.70.94.139:9000/v2');
//====== TO_DO: END: PLEASE CUSTOMIZE THIS AS PER YOUR SITE.

function build_hmac($api_url, $data) {
  //Converting the body into md5 hash
  $body_digest = openssl_digest( $data,'md5', true );
  //Encoding the body_digest with base64 encde
  $encoded_body = base64_encode( $body_digest );
  //Current date for example
  $current_date = date('Y-m-d H:i');
  //Forming the signature
  $signature = $current_date . "\n" .$api_url. "\n" . $encoded_body;
  //Get Client Custom Fields
  $vertice_apikey = {MASTERKEY};

  $vertice_email = fetchFieldByName('email',$vars['userid']);
  //Creating HMAC hash with sha1
  $hash = hash_hmac( 'sha256', rtrim($signature), $vertice_apikey );
  //Final Hmac
  $final_hmac = $vertice_email . ':' . $hash;

  //we are sticking it in a hash
  $built=array();
  $built['final_hmac']=$final_hmac;
  $built['current_date']=$final_date;

  return $built;
}

function build_header($headerArgs) {
  $final_hmac = $headerArgs['final_hmac '];
  $current_date = $headerArgs['current_date'];

  $organization_id = fetchFieldByName('org_id',$vars['userid']);

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

function invoke_api($api_url, $body_json) {
  $data = json_encode($body_json);

  $headerArgs = build_hmac($api_url,$data)

  $headers = build_header($headerArgs)

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, {$GATEWAY}.$api_url);

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

  return  array('Request: ' => $e, 'Api Response : ' => $response,'Http Code : ' => $get_info,'Curl Error : ' => $curl_error);
}

?>
