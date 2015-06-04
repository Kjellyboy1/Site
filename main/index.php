<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- hier staan alle verwijzingen die naar een document sturen  AFBLIJVEN!!-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="siteslider.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"> </script>
<script src="menucross.js" type="text/javascript">
</script>

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
header("location: index.php?p=../home/index");
}

?>

<!--<style>
border met spatie 
Niet nodig!
body {Border: solid #500000 20px;}

</style>
-->

<body>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '868491879858554',
      xfbml      : true,
      version    : 'v2.3'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<link rel="stylesheet" type="text/css" href="css.css" />
<div id="left"></div>
<div id="right"></div>
<div id="top"></div>



<!-- (header) Hier staat de foto slider. Afblijven!--> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" height="360" id="table1">
<tr>
<td align="top" width="100%" height="360">
<iframe src="../header/header.html" frameborder="0" scrolling="auto" height="360" width="100%" >
</iframe> 
</tr>
</table>

<!-- (menu) daar staat heel het menu in indien je dat wil aanpassen vragen aan contact persoon -> readme! -->

<table border="0" width="100%" cellspacing="0" cellpadding="0" height="50" id="menu"> <tr> 
<th width="100%" height="50" align="center">
<?php include("../menu/menu.php");?>



<!-- Index.phpp?=... afblijven hier kommen al paginas al je ze opent op de site! -->
<div class="clear"> </div>
</th>
</tr> 
</table>
<center>
<table>
 <tr>
  <td><table width="1097"><tr>
  
  <td width="1055" height="100%"><table width="107%" height="100%" align="center"><tr><td align="center"><p align="left">

		<center id="table2"></center>
</td></tr></table></td>
</tr>
</table>
<div class="footer">&copy; Kavoosi 2014. All rights reserved. </div>
<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Open+Sans);
footer {
position: fixed; 
width: 100%; 
background: #500000; 
bottom: 0px; 
padding: 20px; 
left: 0px; 
font-family: 'Open Sans', sans-serif; 
color: #fff;
}



</style>
  </body>

</html>