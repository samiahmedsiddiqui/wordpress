<?php

/**
 * Add http auth on WordPress except the wp-admin and wp-login page and also it doesn't give the http auth to the administrators
 */
if( !is_admin() ){ // To add http auth for all the site user accept admin. So, Remove this and line no. 22
  if( strpos($_SERVER['REQUEST_URI'], '/wp-admin') === false && strpos($_SERVER['REQUEST_URI'], '/wp-login') === false ) // To Add http auth for over all the site accept wp-admin/wp-login page then remove this line and the very next line(Line no. 8) of it.
    return;
  $realm = 'Restricted Site';
  $username = "sami";
  $password = "123456";

  $user = $_SERVER['PHP_AUTH_USER'];
  $pass = $_SERVER['PHP_AUTH_PW'];

  if ( !($user == $username && $pass == $password) ){
    $message = "This Site is Restricted. Please contact the administrator for access.";
    header('WWW-Authenticate: Basic realm="'.$realm.'"');
    header('HTTP/1.0 401 Unauthorized');
    die ( http_auth_cancel_page($message) );
  }
}

/**
 * Page which shows when user click on cancel button in http auth
 */
function http_auth_cancel_page($message = ''){
   $sitename = get_bloginfo ( 'name' );
   return '<html>
               <head>
                  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                  <title>'.$sitename.' | Restricted Site</title>
               </head>
               <body class="http-restricted">
                  <p>'.$message.'</p>
               </body>
            </html>';
}

To have proper implementation and have front-end feature Use this plugin: https://wordpress.org/plugins/http-auth/
