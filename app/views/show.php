<?php
use Guzzle\Http\Client;

if (isset($_GET['branchId'])){
    $branchID = $_GET['branchId'];
}else{
    $branchID = $config['institutions'][$_SESSION['institution']]['defaultBranch'];
}

$parameters = array();
$parameters['principalID'] = $accessToken->getUser()->getPrincipalID();
$parameters['principalIDNS'] = $accessToken->getUser()->getPrincipalIDNS();
$parameters['inst'] = $_SESSION['institution'];

if (isset($config['circService'])){
    $url = $config['circService'];
} else {
    $url = 'https://circ.sd' . $config['institutions'][$_SESSION['institution']]['datacenter'] . '.worldcat.org';
}
$url .= '/pulllist/' . $branchID . '?' . http_build_query($parameters, '', '&');



$client = new Client($guzzleOptions);
//$headers = array('Authorization' => 'Bearer ' . $accessToken->getValue());
$options = array('user' => $accessToken->getUser());
$headers = array('Authorization' => $wskey->getHMACSignature('GET', $url, $options));
$guzzleOptions['headers'] = $headers;

try {
    $response = \Guzzle::get($url, $guzzleOptions);
    
    $pulllistAtom = simplexml_load_string($response->getBody(true));
    $pulllistAtom->registerXPathNamespace("atom", "http://www.w3.org/2005/Atom");
    $pulllistAtom->registerXPathNamespace("pulllist", "http://worldcat.org/xmlschemas/CirculationPullList-1.0");
    $pulllistAtom->registerXPathNamespace("bib", "http://worldcat.org/xmlschemas/Bib-1.0");
    $pulllistItems = $pulllistAtom->xpath('/atom:feed/atom:entry/atom:content/pulllist:itemDescription');
    
} catch (\Guzzle\Http\Exception\BadResponseException $error) {
    echo $error->getResponse()->getStatusCode();
    echo $error->getRequest();
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
if (count($pulllistItems > 0)){ 
    foreach ($pulllistItems as $item){
        $title = $item->bibliographicItem->children('http://worldcat.org/xmlschemas/Bib-1.0')->title;
        $callNumber = $item->callNumber->description;
        $barcode = $item->pieceDesignation;

    ?>
        <div>
        <p>Title: <?php echo $title?></p>
        <p>Call Number: <?php echo $callNumber?></p>
        <p>Barcode: <?php echo $barcode?></p>
    </div>
    <?php 
    }
} else {
?>
<p>No items in this pulllist.</p>
<?php 
}?>
</body>
</html>
