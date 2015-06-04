<?php 

function subnav($folder) {
	$id = $_GET['id'];
	$action = $_GET['action'];
	$menu = $_GET['menu'];
	$sort = $_GET['sort'];
	$jaar = $_GET['jaar'];
	$maand = $_GET['maand'];

	switch ($folder) {
		case home:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($action <> "") { ?>
					  <li><a href="../home/"><b>terug</b></a></li>
<?		}
		else { ?>
					  <li><a target='_blank' href="../home/print.php">print versie</a></li>
<?		} ?>
					</ul>
				  </div>
<?
		break;
		case leden:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($action <> "" || $id <> "") { ?>
					  <li><a href="../leden/"><b>terug</b></a></li>
<?		} ?>
<?		if ($action == "") { ?>
					  <li><a target='_blank' href="../leden/print.php?sort=<? echo $sort; ?>&id=<? echo $id; ?>">print versie</a></li>
<?		} ?>
					</ul>
				  </div>
<?
		break;
		case activi:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($menu <> "" && $id <> "") { ?>
					  <li><a href="../activi/?menu=archief"><b>terug</b></a></li>
<?		} 
		else if ($menu <> "" || $action <> "" || $id <> "") { ?>
					  <li><a href="../activi/"><b>terug</b></a></li>
<?		} ?>
<?		if ($action == "" || $id <> "") {
			if ($menu == "archief") { ?>
					  <li><a target='_blank' href="../activi/print.php?menu=archief&action=<? echo $action; ?>&id=<? echo $id; ?>">print versie</a></li>
<?			} else { ?>
					  <li><a target='_blank' href="../activi/print.php?action=<? echo $action; ?>&id=<? echo $id; ?>">print versie</a></li>
<?			}
		} ?>
<?		if ($menu == "archief") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>		  <a href="../activi/?menu=archief">archief + media</a></li>
<?		if ($action == "ibox") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>		  <a href="../activi/?action=ibox">ideëenbox</a></li>
					</ul>
				  </div>
<?
		break;
		case event:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($menu <> "" && $id <> "") { ?>
					  <li><a href="../event/?menu=archief"><b>terug</b></a></li>
<?		} 
		else if ($menu <> "" || $action <> "" || $id <> "") { ?>
					  <li><a href="../event/"><b>terug</b></a></li>
<?		} ?>
<?		if ($action == "" || $id <> "") {
			if ($menu == "archief") { ?>
					  <li><a target='_blank' href="../event/print.php?menu=archief&action=<? echo $action; ?>&id=<? echo $id; ?>">print versie</a></li>
<?			} else { ?>
					  <li><a target='_blank' href="../event/print.php?action=<? echo $action; ?>&id=<? echo $id; ?>">print versie</a></li>
<?			}
		} ?>
<?		if ($menu == "archief") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>		  <a href="../event/?menu=archief">archief + media</a></li>
<?		if ($action == "nieuw") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>		  
					  <a href="../event/?action=nieuw">toevoegen</a></li>
					</ul>
				  </div>
<?
		break;
		case memo:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($menu <> "" && $id <> "") { ?>
					  <li><a href="../fuiven/?menu=archief"><b>terug</b></a></li>
<?		} 
		else if ($menu <> "" || $action <> "" || $id <> "") { ?>
					  <li><a href="../fuiven/"><b>terug</b></a></li>
<?		} ?>
<?		if ($action == "") { ?>
					  <li><a target='_blank' href="../memo/print.php?jaar=<? echo $jaar; ?>&maand=<? echo $maand; ?>">print versie</a></li>
					  <li><a href="../event/?action=nieuw">event toevoegen</a></li>
<?		} ?>
					</ul>
				  </div>
<?
		break;
		case vendelen:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($menu <> "" && $id <> "") { ?>
					  <li><a href="../vendelen/?menu=wimpelen"><b>terug</b></a></li>
<?		} 
		else if ($menu <> "" || $action <> "" || $id <> "") { ?>
					  <li><a href="../vendelen/"><b>terug</b></a></li>
<?		} ?>
<?		if ($action == "" || $id <> "") {
			if ($menu == "wimpelen") { ?>
					  <li><a target='_blank' href="../vendelen/print.php?menu=wimpelen&action=<? echo $action; ?>&id=<? echo $id; ?>">print versie</a></li>
<?			} else { ?>
					  <li><a target='_blank' href="../vendelen/print.php?action=<? echo $action; ?>&id=<? echo $id; ?>">print versie</a></li>
<?			}
		} ?>
<?		if ($menu == "vendelen") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>		  <a href="../vendelen/?menu=vendelen">vendelen</a></li>
<?		if ($menu == "wimpelen") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>		  <a href="../vendelen/?menu=wimpelen">wimpelen</a></li>
					</ul>
				  </div>
<?
		break;
		case weetjes:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($action <> "" || $id <> "") { ?>
					  <li><a href="../weetjes/"><b>terug</b></a></li>
<?		} ?>
<?		if ($action == "" || $id <> "") { ?>
					  <li><a target='_blank' href="../weetjes/print.php?action=<? echo $action; ?>&id=<? echo $id; ?>">print versie</a></li>
<?		} ?>
<?		if ($action == "nieuw") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>
					  <a href="../weetjes/?action=nieuw">toevoegen</a></li>
					</ul>
				  </div>
<?
		break;
		case gasten:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($action <> "" || $id <> "") { ?>
					  <li><a href="../gasten/"><b>terug</b></a></li>
<?		} ?>
<?		if ($action == "nieuw") { ?>
					  <li class='selected'>
<?		} else { ?>
					  <li>
<?		} ?>
					  <a href="../gasten/?action=nieuw">bericht toevoegen</a></li>
					  <li><a href="../event/?action=nieuw">event toevoegen</a></li>
					</ul>
				  </div>
<?
		break;
		case admin:
?>
				  <div id="sub_navigation">
					<span></span>
					<ul>
<?		if ($action <> "" || $id <> "") { ?>
					  <li><a href="../admin/"><b>terug</b></a></li>
<?		} ?>
					</ul>
				  </div>
<?
		break;
	}
}

?>