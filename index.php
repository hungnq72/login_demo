<?php
require_once("database.php");
require_once("account.php");
//index.php

//Include Configuration File
include('config.php');

$login_button = '';

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if (isset($_GET["code"])) {
    //It will Attempt to exchange a code for an valid authentication token.
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
    if (!isset($token['error'])) {
        //Set the access token used for requests
        $google_client->setAccessToken($token['access_token']);

        //Store "access_token" value in $_SESSION variable for future use.
        $_SESSION['access_token'] = $token['access_token'];

        //Create Object of Google Service OAuth 2 class
        $google_service = new Google_Service_Oauth2($google_client);

        //Get user profile data from google
        $data = $google_service->userinfo->get();

        //Below you can find Get profile data and store into $_SESSION variable
        if (!empty($data['given_name'])) {
            $_SESSION['user_first_name'] = $data['given_name'];
        }

        if (!empty($data['family_name'])) {
            $_SESSION['user_last_name'] = $data['family_name'];
        }

        if (!empty($data['email'])) {
            $_SESSION['user_email_address'] = $data['email'];
        }

        if (!empty($data['gender'])) {
            $_SESSION['user_gender'] = $data['gender'];
        }

        if (!empty($data['picture'])) {
            $_SESSION['user_image'] = $data['picture'];
        }
    }
}

//This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
if (!isset($_SESSION['access_token'])) {
    //Create a URL to obtain user authorization
    $login_button = '<a href="' . $google_client->createAuthUrl() . '"><img src="https://www.codexworld.com/demos/login-with-google-api-using-php/images/google-sign-in-btn.png" /></a>';
}

?>


<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Login using Google Account</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body>

    <div style="display: block;" id="hung" class="container">
        <br />
        <h2 align="center">PHP Login using Google Account</h2>
        <br />
        <div class="panel panel-default">
            <?php
            if ($login_button == '') {
                echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
                echo '<img src="' . $_SESSION["user_image"] . '" class="img-responsive img-circle img-thumbnail" />';
                echo '<h3><b>Name :</b> ' . $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'] . '</h3>';
                echo '<h3><b>Email :</b> ' . $_SESSION['user_email_address'] . '</h3>';
                echo '<h3><a href="logout.php">Logout</h3></div>';
                echo "<script>document.getElementById('btn-fb').setAttribute('style', 'display:none');</script>";

                $name = $_SESSION['user_first_name'] . " " . $_SESSION['user_last_name'];
                $email = $_SESSION['user_email_address'];
                $avatar = $_SESSION["user_image"];
                $sitename = 'Google';
                if($p->search($email, $sitename)){
                    $p->insertAccount($name, $email, $avatar, $sitename);
                    
                }
            } else {
                echo '<div align="center">' . $login_button . '</div>';
            }
            ?>
        </div>
    </div>

    

    <script>
        window.fbAsyncInit = function() {
            // FB JavaScript SDK configuration and setup
            FB.init({
                appId: '1902816543201564', // FB App ID
                cookie: true, // enable cookies to allow the server to access the session
                xfbml: true, // parse social plugins on this page
                version: 'v2.8' // use graph api version 2.8
            });

            // Check whether the user already logged in
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    //display user data
                    getFbUserData();
                }
            });
        };

        // Load the JavaScript SDK asynchronously
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // Facebook login with JavaScript SDK
        function fbLogin() {
            FB.login(function(response) {
                if (response.authResponse) {
                    // Get and display the user profile data
                    getFbUserData();
                } else {
                    document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
                }
            }, {
                scope: 'email'
            });
        }

        // Fetch the user profile data from facebook
        function getFbUserData() {
            FB.api('/me', {
                    locale: 'en_US',
                    fields: 'id,first_name,last_name,email,link,gender,locale,picture'
                },
                function(response) {
                    document.getElementById('fbLink').setAttribute("onclick", "fbLogout()");
                    document.getElementById('fbLink').innerHTML = 'Logout from Facebook';
                    document.getElementById('status').innerHTML = '<p>Thanks for logging in, ' + response.first_name + '!</p>';
                    document.getElementById('userData').innerHTML = '<h2>Facebook Profile Details</h2><p><img src="' + response.picture.data.url + '"/></p><p><b>FB ID:</b> ' + response.id + '</p><p><b>Name:</b> ' + response.first_name + ' ' + response.last_name + '</p><p><b>Email:</b> ' + response.email;


                });
        }

        // Logout from facebook
        function fbLogout() {
            FB.logout(function() {
                document.getElementById('fbLink').setAttribute("onclick", "fbLogin()");
                document.getElementById('fbLink').innerHTML = '<img style="height: 53px" src="https://www.codexworld.com/demos/login-with-facebook-using-javascript-sdk/images/fb-login-btn.png"/>';
                document.getElementById('userData').innerHTML = '';
                document.getElementById('status').innerHTML = '<p>You have successfully logout from Facebook.</p>';

                document.getElementById('hung').setAttribute('style', 'display:block');
            });
        }

        // Fetch the user profile data from facebook



        function getFbUserData() {
            FB.api('/me', {
                    locale: 'en_US',
                    fields: 'id,first_name,last_name,email,link,gender,locale,picture'
                },
                function(response) {
                    document.getElementById('fbLink').setAttribute("onclick", "fbLogout()");
                    document.getElementById('fbLink').innerHTML = 'Logout from Facebook';
                    document.getElementById('status').innerHTML = '<p>Thanks for logging in, ' + response.first_name + '!</p>';
                    document.getElementById('userData').innerHTML = '<h2>Facebook Profile Details</h2><p><img src="' + response.picture.data.url + '"/></p><p><b>FB ID:</b> ' + response.id + '</p><p><b>Name:</b> ' + response.first_name + ' ' + response.last_name + '</p><p><b>Email:</b> ' + response.email;

                    document.getElementById('hung').setAttribute('style', 'display:none');
                    // Save user data
                    saveUserData(response);
                });
        }


        function saveUserData(userData) {
            $.post('userData.php', {
                oauth_provider: 'facebook',
                userData: JSON.stringify(userData)
            }, function() {
                return true;
            });
        }
    </script>

    <div class="container" id="btn-fb" style="display: block;">
        <!-- Display login status -->
        <div id="status"></div>

        <!-- Facebook login or logout button -->
        <div class="panel panel-default">
            <div align="center">
                <a href="javascript:void(0);" onclick="fbLogin();" id="fbLink"><img style="height: 53px" src="https://www.codexworld.com/demos/login-with-facebook-using-javascript-sdk/images/fb-login-btn.png" /></a>
            </div>

        </div>

        <!-- Display user's profile info -->
        <div class="ac-data" id="userData"></div>

    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</body>

</html>