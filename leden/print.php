	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
	<? include("config.php"); ?>

	<head>
		<META HTTP-EQUIV="Refresh" CONTENT="URL=index.php">
		<meta name="ROBOTS" content="INDEX">
		<meta name="ROBOTS" content="FOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>.: KLJ Gistel :.: Nie Geweune :.: Print :.</title>
		<link rel="stylesheet" href="../_styles/print.css" type="text/css" />
		<script type="text/javascript" src="../_styles/button.js"></script>
	</head>

	<body>

	<table border='0' width='100%' align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<td class='printbanner'>
			    <b>&#126; <? $menu = strtoupper($menu); echo"$menu"; ?> &#126;</b>
			</td>
		</tr>
		<tr>
			<td class='maintable'>
			    <br />
			</td>
		</tr>
		<tr>
			<td height='800' class='maintable'>
			    <? include ('functions.php');
				map('yes'); ?>
			</td>
		</tr>
	</table>

</body>
</html>