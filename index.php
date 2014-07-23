<?php
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

use OCLC\Auth\WSKey;
use OCLC\Auth\AuthCode;
use OCLC\Auth\AccessToken;
use OCLC\User;

global $config;
$config = Yaml::parse('app/config/config.yaml');

$guzzleOptions = array(
    'config' => array(
        'curl' => array(
            CURLOPT_SSLVERSION => 3
        )
    ),
    'allow_redirects' => array(
        'strict' => true
    ),
    'timeout' => 60
);

if (!class_exists('Guzzle')) {
    \Guzzle\Http\StaticClient::mount();
}

if (isset($config['authorizationServer'])){
    AuthCode::$authorizationServer = $config['authorizationServer'];
    AccessToken::$authorizationServer = $config['authorizationServer'];
    WSKey::$testServer = TRUE;
    $guzzleOptions['verify'] = false;
}



/* Determine the redirect_uri of your application*/
if (isset($_SERVER['HTTPS'])):
$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
else:
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
endif;

session_start();
 
/* Construct a new WSkey object using the key, secret and an options array that contains the services you want to access and your redirect_uri */
$options = array('services' => array('WMS_CIRCULATION', 'refresh_token'), 'redirectUri' => $redirect_uri);
$wskey = new WSKey($config['wskey'], $config['secret'], $options);
 
/* if you have an Access Token or Authorization Code already */
if (isset($_SESSION['accessToken']) || isset($_GET['code'])) {
    /* if you have an Access Token or Authorization Code already */
    if (empty($_SESSION['accessToken'])) {
        /* if you do have an Authorization Code but not an Access Token, use the Authorization code to get an Access Token */
        $accessToken = $wskey->getAccessTokenWithAuthCode($_GET['code'], $_SESSION['institution'], $_SESSION['institution']);
    
        $_SESSION['accessToken'] = $accessToken;
    } else {
        $accessToken = $_SESSION['accessToken'];
    }
    if ($accessToken->getErrorCode()){
        unset($_SESSION['accessToken']);
        echo $accessToken->getResponse();
        
    }elseif ($accessToken->getRefreshToken->isExpired()){
        unset($_SESSION['accessToken']);
        include 'app/views/wayf.php';
    }else {
        include 'app/views/show.php';
    }
}elseif (isset($_GET['institution'])){ 
    /* if you don't have an Access token or Authorization Code, but know the institution redirect the user to the login URL */
    $_SESSION['institution'] = (int)$_GET['institution'];
    header("Location: " . $wskey->getLoginURL($_SESSION['institution'], $_SESSION['institution']), 'true', '303');
} else {
    /* if you don't have an Access token or Authorization Code and don't know what institution it is show the WAYF screen **/
    include 'app/views/wayf.php';
}
?>