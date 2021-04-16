<?php

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('710871611206-q5pbcc0949eie4q9t0b2jhote760o91h.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('pdvhq_17HRqaxmdmn6cOD7st');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/php/login_demo/');

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page
session_start();

// Call Facebook API

$facebook = new \Facebook\Facebook([
    'app_id'      => '308660164006911',
    'app_secret'     => 'a6518a76452b7170e2524dbf3152671b',
    'default_graph_version'  => 'v2.10'
  ]);

?>