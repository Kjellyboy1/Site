<?php
session_start();
include ("config.php");
	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname_login) or die($dberror);
	$querya = " SELECT * FROM Leden";
	$resulta = mysql_query($querya);
	$admin = mysql_fetch_array($resulta);
	$admin_name = Array();
	$admin_password = Array();
	$admin_rights = Array();
	$admin_id = Array();
		$i=0;
		while ($admin = mysql_fetch_array($resulta)) {
			$admin_name[$i] = "$admin[username]";
			$admin_password[$i] = "$admin[user_password]";
			$admin_rights[$i] = "$admin[rights]";
			$admin_position[$i] = "$admin[position]";
			$admin_id[$i] = "$admin[id]";
			$i++;
		}
$session_expires = $_SESSION['mpass_session_expires'];
$login_attempts++;
if(!empty($_POST['mpass_pass']) && !empty($_POST['mname_name'])) {
	$_SESSION['mpass_pass'] = md5($_POST['mpass_pass']);
	$_SESSION['mname_name'] = $_POST['mname_name'];
}
if(empty($_SESSION['mpass_attempts'])) {
	$_SESSION['mpass_attempts'] = 0;
}
$key = array_search($_SESSION['mname_name'],$admin_name);
$paswoord = $admin_password[$key];
$_SESSION['rights'] = $admin_rights[$key];
$_SESSION['ledenid'] = $admin_id[$key];
$_SESSION['position'] = $admin_position[$key];
$position = $_SESSION['position'];
if(($session_duration>0 && !empty($session_expires) && time()>$session_expires) || empty($_SESSION['mpass_pass']) || empty($_SESSION['mname_name']) || (!in_array($_SESSION['mname_name'],$admin_name)) || ($_SESSION['mpass_pass'] <> $paswoord)) {
	if(!empty($alert) && (!in_array($_SESSION['mpass_pass'],$admin_password)) && (!in_array($_SESSION['mpass_name'],$admin_name))) {
		$_SESSION['mpass_attempts']++;
	}
	if($login_attempts>1 && $_SESSION['mpass_attempts']>=$login_attempts) {	
		exit($loginfailiure);
	}
	$_SESSION['mpass_session_expires'] = ""; ?>
       <link rel="icon" type="image/png" href="../_images/favicon-32x32.png" sizes="32x32" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../../main/siteslider.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"> </script>
<script src="../../main/menucross.js" type="text/javascript">
</script><link rel="stylesheet" type="text/css" href="../../main/css.css" />
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../../menu/cssmenu.css" />
          <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
		<link href = "../bootstrap/css/bootstrap.min.css" rel = "stylesheet">
    	<link href = "../bootstrap/css/bootstrap.css" rel = "stylesheet">
   		<script	src="http://code.jquery.com/jquery-2.1.4.min.js"> </script>
  	  	<script	src="../bootstrap/js/bootstrap.js"> </script>
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
<iframe src="../header/header.html" frameborder="0" scrolling="auto" height="360" width="100%" >
</iframe> 
</tr>
</table>

<!-- menu -->

<table border="0" width="100%" cellspacing="0" cellpadding="0" height="50" id="menu"> <tr> 
<th width="100%" height="50" align="center">
  
<?php include("../menu/menu.php");?>
 <?php include('../_table/footer.php'); ?>

</tr> 
</table>
<center>
<table>
 <tr>
  <td><table width="1097"><tr>
  
  <td width="1055" height="100%"><table width="107%" height="100%" align="center"><tr><td align="center"><p align="left">

		


	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
	<?php include("config.php"); ?>

	<head>
		<META HTTP-EQUIV="Refresh" CONTENT="URL=index.php">
		<meta name="ROBOTS" content="INDEX">
		<meta name="ROBOTS" content="FOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>.: KLJ Gistel :.: Nie Geweune :.</title>
		
		<link rel="stylesheet" href="../_styles/main.css" type="text/css" />
		<script type="text/javascript" src="../_styles/button.js"></script>
	</head>

	<body>

	<table border='0' width='100%' align='center' cellpadding='0' cellspacing='0'>
	  <tr>
		<td class='lineU'></td>
		<td class='lineI'></td>
		<td valign='top'>
		  <table border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tr>
			 
			  
			  </td>
			</tr>
			<tr>
			  <td>
				
			  </td>
			</tr>
			<tr>
			  <td>
				
			  </td>
			</tr>
			<tr>
			  
				
			  </td>
			</tr>
			<tr>
			  <td height='100%' class='maintable'>
			    <?php include ('functions.php');
				map('index.php'); ?>
                <TABLE>
<TR>
<TD WIDTH="100%" HEIGHT="40"></TD>
</TR>
		
			  </td>
			</tr>
			<tr>
			  <td class="lineS"></td>
			</tr>
			<tr>
			 
			   
			  </td>
			</tr>
		  </table>
		</td>
		<td class='lineI'></td>
		<td class='lineU'></td>
	  </tr>
	</table>

<?php exit();
}
$_SESSION['mpass_attempts'] = 0;
$_SESSION['mpass_session_expires'] = time()+$session_duration; ?>

</td></tr></table></td>
</tr>
</table>
  </body>

</html> 







<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../../main/siteslider.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"> </script>
<script src="../../main/menucross.js" type="text/javascript">
</script><link rel="stylesheet" type="text/css" href="../../main/css.css" />
<link rel="stylesheet" type="text/css" href="main.css" />
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
<iframe src="../header/header.html" frameborder="0" scrolling="auto" height="360" width="100%" >
</iframe> 
</tr>
</table>
 <tr>
  <td><table width="1097"><tr>
  
 <strong><table border="0" width="100%" cellspacing="0" cellpadding="0" height="50" id="menu"> <tr> 
<th width="100%" height="50" align="center">
  
<?php include("../menu/menu.php");?>
 <?php include('../_table/footer.php'); ?>

</tr> 
</table>
<center>
<table>
 <tr>
  <td><table width="1097"><tr>
  
  <td width="1055" height="100%"><table width="107%" height="100%" align="center"><tr><td align="center"><p align="left"></strong>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<? include("config.php"); ?>

<head>
		<META HTTP-EQUIV="Refresh" CONTENT="URL=index.php">
		<meta name="ROBOTS" content="INDEX">
		<meta name="ROBOTS" content="FOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" href="../_styles/main.css" type="text/css" />
		<script type="text/javascript" src="../_styles/button.js"></script>
          <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
		<link href = "../bootstrap/css/bootstrap.min.css" rel = "stylesheet">
    	<link href = "../bootstrap/css/bootstrap.css" rel = "stylesheet">
   		<script	src="http://code.jquery.com/jquery-2.1.4.min.js"> </script>
  	  	<script	src="../bootstrap/js/bootstrap.js"> </script>
	

</head>

<body>

	<table border='0' width='100%' align='center' cellpadding='0' cellspacing='0'>
	  <tr>
	
		<td valign='top'>
		  <table border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tr>
		
			   
			  </td>
			</tr>
			<tr>
			  <td>
			
			</tr>
			<tr>
			  <td>
				<?php include ('../_table/subnava.php');
				subnav($menu); ?>
			  </td></td>
			</tr>
			<tr>
			  <td height='100%' class='maintable' align="left">
			    <?php include ('functions_all.php');
				map($position); ?>
    
			  <p></td>
			</tr></p>
			<tr>
			  
			</tr>
			<tr>
			
			        <TABLE>
<TR>
<TD WIDTH="100%" HEIGHT="40"></TD>
</TR>
		 
			  </td>
			</tr>
		  </table>
		</td>
		
	  </tr> 
	</table>

</body>
</html></td></tr></table></td>
</tr>
</table>
  </body>

</html> 