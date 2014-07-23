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
<form name="wayf" action="index.php" method="get">
<select name="institution">
<?php 
foreach ($config['institutions'] as $institutionId => $institution){

?>
    <option value="<?php echo $institutionId?>"><?php echo $institution['name']?></option>
<?php 
}
?>
</select>
<input type="submit" value="Continue"/>
</form>
</body>
</html>