<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../../main/siteslider.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"> </script>
<script src="../../main/menucross.js" type="text/javascript">
</script><link rel="stylesheet" type="text/css" href="../../main/css.css" />

<link rel="stylesheet" type="text/css" href="../../menu/cssmenu.css" />

<title>Klj gistel</title>


<style type="text/css">
naamonder {
	color: #FFF;
}
#table2 tr td center {
	color: #FFF;
}
#table1 tr td {
	color: #FFF;
	background-color: #500000;
}
</style>
</head>
<?php
if(!$_GET["p"]) {
session_start();
header("location: index.php?p=../pagina/home/index");
}
?>

<!--<style>
border met spatie 
body {Border: solid #500000 20px;}

</style>
-->

<body>

<div id="left"></div>
<div id="right"></div>
<div id="top"></div>



<!-- header --> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" height="360" id="table1">
<tr>
<td align="top" width="100%" height="360">
<iframe src="..
/../header/header.html" frameborder="0"  height="360" width="100%" >
</iframe> 
</tr>
</table>

<!-- menu -->

<table border="0" width="100%" cellspacing="0" cellpadding="0" height="50" id="menu"> <tr> 
<th width="100%" height="50" align="center">
  
<?php include("../menu/menu.php");?>

</tr> 
</table>
<center>
<table>
 
<table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%" id="table1">
<tr>
<td align="middle" width="100%" height="100%">
<iframe src="index.php" frameborder="0" scrolling="auto" height="100%" width="100%" >
</iframe> 
</tr>
</table>
</body>
</html>

  </body>

</html> 