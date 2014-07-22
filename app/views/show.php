<?php

if (isset($_GET['branchId'])){
    $branchID = $_GET['branchId'];
}else{
    $branchID = $config['institutions'][$_SESSION['institution']]['defaultBranch'];
}

$parameters = array();
$parameters['principalID'] = $accessToken->getPrincipalID();
$parameters['principalIDNS'] = $accessToken->getPrincipalIDNS();
$parameters['inst'] = $_SESSION['institution'];

$url = 'https://circ.sd' . $config['institutions'][$_SESSION['institution']]['datacenter'] . '.worldcat.org/pulllist/' . $branchID . '?' . http_build_query($parameters, '', '&');

$client = new Client();
$client->getClient()->setDefaultOption('config/curl/' . CURLOPT_SSLVERSION, 3);
$headers = array();
$headers['Authorization'] = 'Bearer' . $accessToken->getValue();
$request = $client->createRequest('GET', $url, $headers);

try {
    $response = $request->send();
    $pulllistAtom = simplexml_load_string($response->getResponseBody());
    $pulllistAtom->registerXPathNamespace("atom", "http://www.w3.org/2005/Atom");
    $pulllistAtom->registerXPathNamespace("pulllist", "http://worldcat.org/xmlschemas/Circulation-1.0");
    $pulllistAtom->registerXPathNamespace("bib", "http://worldcat.org/xmlschemas/Bib-1.0");
    $pulllistItems = $results->xpath('/atom:feed/atom:entry/atom:content/pulllist:itemDescription');
    
} catch (\Guzzle\Http\Exception\BadResponseException $error) {
    echo $error->getResponse()->getStatusCode();
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
$bibliographicItem = $item->children('http://worldcat.org/xmlschemas/Bib-1.0');
$title = $bibliographicItem->title;
$callNumber = $item->callNumber->description;
$barcode = $item->pieceDesignation;

?>
<div>
<p>Title: <?php $title?></p>
<p>Call Number<?php $callNumber?></p>
<p>Barcode <?php $barcode?></p>
</div>
<?php 
}?>
</body>
</html>
