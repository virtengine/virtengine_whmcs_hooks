include(ROOTDIR.'/includes/hook/virtengine_api.php');
include(ROOTDIR.'/includes/hook/virtengine_db.php');

<?php

function create_account( $vars ) {
    $e = new Account();
    $e->api_key = bin2hex(openssl_random_pseudo_bytes(16));
    $e->email = $vars['email'];
    $e->name->first_name  = $vars['firstname'];
    $e->name->last_name  = $vars['lastname'];  
    $e->password->password_hash = base64_encode($vars['password']);
    $e->password->password_reset_key = '';
    $e->password->password_reset_sent_at = '';
    $e->approval->approved = null;
    $e->approval->approved_by_id = null;
    $e->approval->approved_at = null;
    $e->dates->last_posted_at = null;
    $e->dates->last_emailed_at = null;
  	$e->dates->previous_visit_at = null;
    $e->dates->first_seen_at = null;
    $e->dates->created_at = null;
    $e->phone->phone = $vars['phonenumber'];
    $e->phone->phone_verified = null;
    $e->registration_ip_address = '';
    $e->states->authority = null;
    $e->states->active = "true";
    $e->states->blocked = null;
    $e->states->staged = null;
    $e->suspend->suspended = null;
    $e->suspend->suspended_at = null;
    $e->suspend->suspended_till = null;

    $res = invoke_api('/accounts/content', $e);

    logActivity( json_encode( $res ) );
}

add_hook('ClientAdd',1,'create_account');


class Account {
      public $name;
      public $email;
      public $password;
      public $phone;
      public $approval;
      public $dates;
      public $registration_ip_address;
      public $suspend;
      public $id;
      public $api_key;
      public $states;
}

?>
