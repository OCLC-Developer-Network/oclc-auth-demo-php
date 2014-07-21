<?php
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use OCLC\Auth\WSKey;
use OCLC\Auth\AuthCode;
use OCLC\Auth\AccessToken;

global $config;
$config = Yaml::parse('app/config/config.yaml');

/* Determine the redirect_uri of your application*/
if (isset($_SERVER['HTTPS'])):
$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
else:
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
endif;

session_start();
 
/* Construct a new WSkey object using the key, secret and an options array that contains the services you want to access and your redirect_uri */
$options = array('services' => $services, 'redirectUri' => $redirect_uri);
$wskey = new WSKey($key, $secret, $options);
 
/* if you have an Access Token or Authorization Code already */
if (isset($_SESSION['accessToken']) && isset($_GET['code'])) {
    /* if you have an Access Token or Authorization Code already */
    if (empty($_SESSION['AccessToken'])) {
        /* if you do have an Authorization Code but not an Access Token, use the Authorization code to get an Access Token */
        $accessToken = $wskey->getAccessTokenWithAuthCode($_GET['code'], 128807, 128807);
    
        $_SESSION['AccessToken'] = $accessToken;
    } else {
        $accessToken = $_SESSION['AccessToken'];
    }
    include 'app/views/show.php';
}elseif (isset($_GET['institution'])){ 
    /* if you don't have an Access token or Authorization Code, but know the institution redirect the user to the login URL */
    $_SESSION['institution'] = $_GET['institution'];
    header("Location: " . $wskey->getLoginURL($_SESSION['institution'], $_SESSION['institution']), 'true', '303');
} else {
    /* if you don't have an Access token or Authorization Code and don't know what institution it is show the WAYF screen **/
    include 'app/views/wayf.php';
}
?>