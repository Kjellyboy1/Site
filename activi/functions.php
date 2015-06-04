<?php
	function map($print) {
	include ("config.php");
	$action = $_GET['action'];
	$menu = $_GET['menu'];
	$id = $_GET['id'];
	$sort = $_GET['sort'];
	$rights = $_SESSION['rights'];
	}
	 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Naamloos document</title>
</head>

<body>

<!-—hoofdtabel-—>
<table width="100%" height="100%">
<tr>
<td>
<!-—tabel 1-—>
<table><iframe src="https://www.google.com/calendar/embed?src=kljgistel%40gmail.com&ctz=Europe/Brussels" style="border: 0" width="580" height="650" frameborder="0" scrolling="no"></iframe></table>
</td>
<td>
<!—-tabel 2-—>
<table><iframe src="https://www.google.com/calendar/embed?src=avr9nfq9vudg677kfech3jmi4o%40group.calendar.google.com&ctz=Europe/Brussels" style="border: 0" width="580" height="650" frameborder="0" scrolling="no"></iframe>
</table>
</td>
</tr>
</table>
</body>

</html>