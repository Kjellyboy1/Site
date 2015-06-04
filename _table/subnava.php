	<html>
    <head>
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
		<link href = "../bootstrap/css/bootstrap.min.css" rel = "stylesheet">
    	<link href = "../bootstrap/css/bootstrap.css" rel = "stylesheet">
   		<script	src="http://code.jquery.com/jquery-2.1.4.min.js"> </script>
  	  	<script	src="../bootstrap/js/bootstrap.js"> </script>

	
</head>
<body>
<?php session_start();
function subnav($folder) {
	include ("configb.php");
	$rights = $_SESSION['rights'];
	$position = $_SESSION['position'];
	$id = $_GET['id'];
	$sort = $_GET['sort'];
	$jaar = $_GET['jaar'];
	$maand = $_GET['maand'];
	
	switch ($folder) {
		case home:
?>
					<ul class="nav nav-tabs">
					<span></span>
					
<?		if ($_GET['action'] <> "") { ?>
					  <li role="presentation"><a href="../home/"><b>Terug</b></a></li>
<?		}
		else { ?>
					 
<?		} ?>
<?		if ($rights == "ALL") { ?>
	<?		if ($_GET['action'] == "upload") { ?></ul>
						 		<ul class="nav nav-tabs">
	<?		} else { ?>
					   <li role="presentation">
	<?		} ?>		  <a href="../home/?action=upload">Upload</a></li>
<?		} ?>
<?		if ($rights == "ALL") {
			if ($_GET['action'] == "nieuw") { ?>
					 		<ul class="nav nav-tabs">
<?			} else { ?>
					   <li role="presentation">
<?			} ?>	  <a href="../home/?action=nieuw">Toevoegen</a></li>
<?		} ?>
					</ul>
				  </div>
<?
		break;
		case leden:
?>
				  		<ul class="nav nav-tabs">
					<span></span>
					
<?		if ($_GET['action'] <> "" || $_GET['id'] <> "") { ?>
					  <li role="presentation"><a href="../leden/"><b>Terug</b></a></li>
<?		} ?>
<?		if ($_GET['action'] == "") { ?>
					 
<?		} ?>
<?		if ($rights == "ALL") {
			if ($_GET['action'] == "nieuw") { ?> </ul>
					 		<ul class="nav nav-tabs">
<?			} else { ?>
					   <li role="presentation">
<?			} ?>	  <a href="../leden/?action=nieuw">Toevoegen</a></li>
<?		} ?>
					</ul>
				  </div>
                 
<?
		break;
		case admin:
?>
				 	<ul class="nav nav-tabs">
					<span></span>
				
<?		if (($_GET['subaction'] <> "" && $_GET['action'] == "instellingen") || $_GET['id'] <> "") { ?>
					  <li role="presentation"><a href="../admin/?action=instellingen"><b>Terug</b></a></li>
<?		} else if ($_GET['action'] <> "" || $_GET['id'] <> "") { ?>
					   <li role="presentation"><a href="../admin/"><b>Terug</b></a></li>
<?		} else { ?>
					   <li role="presentation" class="active"><a href="../admin/?action=logout" target="_top"><b>Uitloggen</b></a></li>
<?		}
		if ($position == "4" || $position == "5") { ?>
					  <li role="presentation"><a target="_blank" href="http://www.kljgistel.be/forum/">Forum</a></li>
<?		}
		if ($position == "5" && $rights == "ALL") { ?>
					   <li role="presentation"><a target="_blank" href="../admin/backup.php">Backup</a></li>
<?		} ?>
<?		if ($rights == "ALL") { 
			if ($_GET['action'] == "instellingen") { ?></ul>
					  	<ul class="nav nav-tabs">
<?			} else { ?>
					  <li role="presentation">
<?			} ?>
					  <a href="../admin/index.php?action=instellingen">Instellingen</a></li>
<?		} ?>
<?		if ($rights == "ALL") { ?>
					   <li role="presentation"><a target="_blank" href="https://www.one.com/admin/">One admin</a></li>
<?		} ?>
<?		if ($rights == "ALL") { ?>					  
					  <li role="presentation"><a target="_blank" href="https://dbadmin.one.com/">Database</a></li>
<?		}
?>
					</ul>
</div>
<?
		break;
	}
}

?>
</body>
</html>