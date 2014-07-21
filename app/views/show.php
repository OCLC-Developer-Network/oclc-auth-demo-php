<?php
$parameters = array();
$parameters['principalID'] = $accessToken->getPrincipalID();
$parameters['principalIDNS'] = $accessToken->getPrincipalIDNS();
$parameters['inst'] = $accessToken->getContextInstitution();

$url = 'https://circ.sd' . $config['datacenter'] . '.worldcat.org/pulllist/' . $_GET['branchID'] . '?' . http_build_query($parameters, '', '&');

$client = new Client();
$client->getClient()->setDefaultOption('config/curl/' . CURLOPT_SSLVERSION, 3);
$headers = array();
$headers['Authorization'] = 'Bearer' . $accessToken->getValue();
$request = $client->createRequest('GET', $url, $headers);

try {
    $response = $request->send();
    
} catch (\Guzzle\Http\Exception\BadResponseException $error) {
    echo $error->getResponse()->getStatusCode();
    echo $error->getResponse()->getWwwAuthenticate();
    echo $error->getResponse()->getBody(true);
}


?>

<html>
<head>
<title>WAYF Screen</title>
<style type="text/css">
body {
	font-family: Helvetica, Verdana, sans-serif;
	margin: 2em 15%;
}
</style>
</head>
<body>
<?php 
foreach ($pullistItems as $item){
?>

<?php 
}?>
</body>
</html>
