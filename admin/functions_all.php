<?php
session_start();
function map() {
	include ("config.php");
	$naam = $_SESSION['mname_name'];
	$rights = $_SESSION['rights'];
	$ledenid = $_SESSION['ledenid'];
	$profiel = $_GET['profiel'];
	$foto = $_GET['foto'];
	$wachtwoord = $_GET['wachtwoord'];
	$gebruikersnaam = $_GET['gebruikersnaam'];
	
	if (isset($_POST['profiel'])) {
		$username = $_POST['username'];
		$dag = $_POST['dag'];
		$maand = $_POST['maand'];
		$jaar = $_POST['jaar'];
		$adres = $_POST['adres'];
		$nr = $_POST['nr'];
		$postcode = $_POST['postcode'];
		$gemeente = $_POST['gemeente'];
		$telefoon = $_POST['telefoon'];
  		$email = $_POST['email'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id <> '$ledenid'";
		$result = mysql_query($query);
		$b=0;
		while ($r = mysql_fetch_array($result)) {
			if ($username == $r[username]) {
				$b++;
			}
		}
	
		if (strlen($username) > 2) {
			if ($b == 0) {	
				$db = mysql_connect($dbhost,$dbuname,$dbpass); 
				mysql_select_db($dbname) or die($dberror);
				mysql_query("UPDATE Leden SET username='$username' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET dag='$dag' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET maand='$maand' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET jaar='$jaar' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET adres='$adres' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET nr='$nr' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET postcode='$postcode' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET gemeente='$gemeente' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET telefoon='$telefoon' WHERE id='$ledenid'");
				mysql_query("UPDATE Leden SET email='$email' WHERE id='$ledenid'");
				mysql_close($db);
				echo"<script>self.location='index.php';</script>";
			}
			else {
				echo"<script>self.location='$print?action=registreren&gebruikersnaam=nok';</script>";
			}
		}
		else {
			echo"<script>self.location='$print?action=registreren&gebruikersnaam=nok2';</script>";
		}		
	}
	else if (isset($_POST['profiel'])) {
		echo"<script>self.location='?action=profiel_wijzigen&profiel=nok';</script>";
    }
	if (isset($_POST['profielfoto'])) {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotofolder = $instellingen[profielfotofolder];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $ledenid";
		$result = mysql_query($query);
		$r = mysql_fetch_array($result);
		
		$geen_profielfoto = $_POST['geen_profielfoto'];
		
		if ($geen_profielfoto == "") {
			if ((($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 307200)) {
				move_uploaded_file($_FILES["file"]["tmp_name"],$instellingen_mediafolder . $instellingen_fotofolder . $r[naam] . " " . $r[voornaam] . ".jpg");
				$target_width = 150;
				$target_height = 150;
				
				$destination_path = $instellingen_mediafolder . $instellingen_fotofolder;

					if (ob_get_level() == 0) ob_start();
					if ($handle = opendir($destination_path)) {
					  while (false !== ($file = readdir($handle))) {
						if ($file == $r[naam] . " " . $r[voornaam] . ".jpg") {
						  $target_path = $destination_path . basename($file);

						  $extension = pathinfo($target_path);
						  $allowed_ext = "jpg, gif, png, bmp, jpeg, JPG";
						  $extension = $extension[extension];
						  $allowed_paths = explode(", ", $allowed_ext);
						  $ok = 0;
						  for($i = 0; $i < count($allowed_paths); $i++) {
							if ($allowed_paths[$i] == "$extension") {
							  $ok = "1";
							}
						  }

						  if ($ok == "1") {

							if($extension == "jpg" || $extension == "jpeg" || $extension == "JPG"){
							  $tmp_image=imagecreatefromjpeg($target_path);
							}

							if($extension == "png") {
							  $tmp_image=imagecreatefrompng($target_path);
							}

							if($extension == "gif") {
							  $tmp_image=imagecreatefromgif($target_path);
							}

							$width = imagesx($tmp_image);
							$height = imagesy($tmp_image);

							//calculate the image ratio
							$imgratio = ($width / $height);

							if ($imgratio>1) {
							  $new_width = ($target_width * $imgratio);
							  $new_height = $target_width;
							} else {
							  $new_height = ($target_height / $imgratio);
							  $new_width = $target_height;
							}

							$new_image = imagecreatetruecolor(150,150);
							ImageCopyResampled($new_image, $tmp_image,(150-$new_width)/2,(150-$new_height)/2,0,0, $new_width, $new_height, $width, $height);
							//Grab new image
							imagejpeg($new_image, $target_path);
							$image_buffer = ob_get_contents();
							ImageDestroy($new_image);
							ImageDestroy($tmp_image);
							ob_flush();
							flush();
						  }
						}
					  }
					  closedir($handle);
					  ob_end_flush();
					}
	
				$fotos = $instellingen_fotofolder . $r[naam] . " " . $r[voornaam] . ".jpg";			
			}
			else {
				echo"<script>self.location='?action=profielfoto_wijzigen&foto=nok';</script>";
			}
		}
		else {
			$fotos = "NONE";
		}

		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Leden SET fotos='$fotos' WHERE id='$ledenid'");

        mysql_close($db);
		echo"<script>self.location='index.php';</script>";
	}
	if ($profiel == "nok") {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $profiel_nok_1 ."</b><br><br>". $profiel_nok_2 ."
					</td>
				</tr>
			</table>
		";
	}
	if ($foto == "nok") {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $profielfoto_nok_1 ."</b><br><br>". $profielfoto_nok_2 ."
					</td>
				</tr>
			</table>
		";
	}
	if (isset($_POST['wachtwoord']) AND md5($_POST['wachtwoordoud']) == $_POST['wachtwoord_huidig'] AND $_POST['wachtwoord1'] == $_POST['wachtwoord2'] AND strlen($_POST['wachtwoord1']) > 5) {
		$wachtwoord = md5($_POST['wachtwoord1']);
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Leden SET user_password='$wachtwoord' WHERE id='$ledenid'");
        mysql_close($db);
		echo"<script>self.location='index.php';</script>";
	}
	else if (isset($_POST['wachtwoord']) AND md5($_POST['wachtwoordoud']) <> $_POST['wachtwoord_huidig']) {
		echo"<script>self.location='?action=wachtwoord_wijzigen&wachtwoord=nok1';</script>";
    }
	else if (isset($_POST['wachtwoord']) AND $_POST['wachtwoord1'] <> $_POST['wachtwoord2']) {
		echo"<script>self.location='?action=wachtwoord_wijzigen&wachtwoord=nok2';</script>";
    }
	else if (isset($_POST['wachtwoord'])) {
		echo"<script>self.location='?action=wachtwoord_wijzigen&wachtwoord=nok3';</script>";
    }
	if ($wachtwoord == "nok1") {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $wachtwoord_nok_1 ."</b><br><br>". $wachtwoord_nok_2 ."
					</td>
				</tr>
			</table>
		";
	}
	if ($wachtwoord == "nok2") {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $wachtwoord_nok_1 ."</b><br><br>". $wachtwoord_nok_3 ."
					</td>
				</tr>
			</table>
		";
	}
	if ($wachtwoord == "nok3") {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $wachtwoord_nok_1 ."</b><br><br>". $wachtwoord_nok_4 ."
					</td>
				</tr>
			</table>
		";
	}
	if ( $gebruikersnaam == "nok" ) {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $gebruikersnaam_nok1 ."</b><br><br>". $gebruikersnaam_nok2 ."
					</td>
				</tr>
			</table>
		";
	}
	else if ( $gebruikersnaam == "nok2" ) {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $gebruikersnaam_nok1 ."</b><br><br>". $gebruikersnaam_nok3 ."
					</td>
				</tr>
			</table>
		";
	}
	if (isset($_POST['mediafolder'])) {
		$folder = $_POST['folder'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Instellingen SET mediafolder='$folder'");
        mysql_close($db);
		echo"<script>self.location='index.php?action=instellingen';</script>";
	}
	if (isset($_POST['verbodenfoto'])) {
		$folder = $_POST['folder'];
		$bestand = $_POST['bestand'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Instellingen SET fotoNa='$folder$bestand'");
        mysql_close($db);
		echo"<script>self.location='index.php?action=instellingen';</script>";
	}
	if (isset($_POST['profielfoto_folder'])) {
		$folder = $_POST['folder'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Instellingen SET profielfotofolder='$folder'");
        mysql_close($db);
		echo"<script>self.location='index.php?action=instellingen';</script>";
	}
	if (isset($_POST['upload_home_folder'])) {
		$folder = $_POST['folder'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Instellingen SET homefolder='$folder'");
        mysql_close($db);
		echo"<script>self.location='index.php?action=instellingen';</script>";
	}
	if (isset($_POST['startpagina_verzenden'])) {
		$startpagina = $_POST['startpagina'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Instellingen SET startpagina='$startpagina'");
        mysql_close($db);
		echo"<script>self.location='index.php?action=instellingen';</script>";
	}
	if (isset($_POST['werkjaar_verzenden'])) {
		$werkjaar = $_POST['werkjaar'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Instellingen SET werkjaar='$werkjaar'");
        mysql_close($db);
		echo"<script>self.location='index.php?action=instellingen';</script>";
	}
	
	if ( $_GET['action'] == "profiel_wijzigen" ) {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotoNONE = $instellingen[fotoNa];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $ledenid";
		$result = mysql_query($query);
		$r = mysql_fetch_array($result);
		if ($r[fotos] == "NONE" || $r[fotos] == "na" || $r[fotos] == "") {
			$fotos = "$instellingen_fotoNONE";
		}
		else {
			$fotos = "$r[fotos]";
		}
	?>
	<form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	  <table width='100%' align='left' border='0' cellspacing='0' cellpadding='5'>
		<tr>
		  <td class='title' width='25%'>
			<img width='150' height='150' src='<? echo"$instellingen_mediafolder$fotos";?>' class='images'>
		  </td>
		  <td class='title' width='75%'>
			<input class='input' type="text" name="username" value="<?php echo $r[username]; ?>" size="40"/>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
			    <td>
					<u><i>Naam:</i></u>
				</td>
			  </tr>
			  <tr>
				<td>
					<?php echo"$r[naam] $r[voornaam]<br /><br />"; ?>
				</td>
			  </tr>
			  <tr>
				<td>
					<i><? echo $geboortedatum ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td>
					<SELECT NAME="dag" class='input'>
				<? if ($r[dag] == "01") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01" selected>1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
    		<? }
			else if ($r[dag] == "02") { ?>
					<OPTION VALUE="">dag</OPTION>
    				<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02" selected>2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? }
			else if ($r[dag] == "03") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03" selected>3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "04") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04" selected>4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "05") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05" selected>5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "06") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06" selected>6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "07") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07" selected>7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "08") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08" selected>8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "09") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09" selected>9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "10") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10" selected>10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "11") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11" selected>11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "12") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12" selected>12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "13") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13" selected>13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "14") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14" selected>14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "15") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15" selected>15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "16") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16" selected>16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "17") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17" selected>17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "18") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18" selected>18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "19") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19" selected>19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "20") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20" selected>20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "21") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21" selected>21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "22") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22" selected>22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "23") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23" selected>23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "24") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24" selected>24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "25") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25" selected>25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "26") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26" selected>26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "27") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27" selected>27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "28") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28" selected>28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "29") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29" selected>29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "30") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="01">1</OPTION>
					<OPTION VALUE="02">2</OPTION>
					<OPTION VALUE="03">3</OPTION>
					<OPTION VALUE="04">4</OPTION>
					<OPTION VALUE="05">5</OPTION>
					<OPTION VALUE="06">6</OPTION>
					<OPTION VALUE="07">7</OPTION>
					<OPTION VALUE="08">8</OPTION>
					<OPTION VALUE="09">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30" selected>30</OPTION>
					<OPTION VALUE="31">31</OPTION>
			<? } else if ($r[dag] == "31") { ?>
					<OPTION VALUE="">dag</OPTION>
					<OPTION VALUE="1">1</OPTION>
					<OPTION VALUE="2">2</OPTION>
					<OPTION VALUE="3">3</OPTION>
					<OPTION VALUE="4">4</OPTION>
					<OPTION VALUE="5">5</OPTION>
					<OPTION VALUE="6">6</OPTION>
					<OPTION VALUE="7">7</OPTION>
					<OPTION VALUE="8">8</OPTION>
					<OPTION VALUE="9">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31" selected>31</OPTION>
			<? } else { ?>
					<OPTION VALUE="" selected>dag</OPTION>
					<OPTION VALUE="1">1</OPTION>
					<OPTION VALUE="2">2</OPTION>
					<OPTION VALUE="3">3</OPTION>
					<OPTION VALUE="4">4</OPTION>
					<OPTION VALUE="5">5</OPTION>
					<OPTION VALUE="6">6</OPTION>
					<OPTION VALUE="7">7</OPTION>
					<OPTION VALUE="8">8</OPTION>
					<OPTION VALUE="9">9</OPTION>
					<OPTION VALUE="10">10</OPTION>
					<OPTION VALUE="11">11</OPTION>
					<OPTION VALUE="12">12</OPTION>
					<OPTION VALUE="13">13</OPTION>
					<OPTION VALUE="14">14</OPTION>
					<OPTION VALUE="15">15</OPTION>
					<OPTION VALUE="16">16</OPTION>
					<OPTION VALUE="17">17</OPTION>
					<OPTION VALUE="18">18</OPTION>
					<OPTION VALUE="19">19</OPTION>
					<OPTION VALUE="20">20</OPTION>
					<OPTION VALUE="21">21</OPTION>
					<OPTION VALUE="22">22</OPTION>
					<OPTION VALUE="23">23</OPTION>
					<OPTION VALUE="24">24</OPTION>
					<OPTION VALUE="25">25</OPTION>
					<OPTION VALUE="26">26</OPTION>
					<OPTION VALUE="27">27</OPTION>
					<OPTION VALUE="28">28</OPTION>
					<OPTION VALUE="29">29</OPTION>
					<OPTION VALUE="30">30</OPTION>
					<OPTION VALUE="31">31</OPTION>
    		<? } ?>
				</SELECT>
				<SELECT NAME="maand" class='input'>
			<? if ($r[maand] == "01") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01" selected>januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
    		<? } else if ($r[maand] == "02") { ?>
					<OPTION VALUE="">maand</OPTION>
    				<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02" selected>februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "03") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03" selected>maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "04") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04" selected>april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "05") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05" selected>mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "06") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06" selected>juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "07") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07" selected>juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "08") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08" selected>augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "09") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09" selected>september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "10") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10" selected>oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "11") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11" selected>november</OPTION>
					<OPTION VALUE="12">december</OPTION>
			<? } else if ($r[maand] == "12") { ?>
					<OPTION VALUE="">maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12" selected>december</OPTION>
			<? } else { ?>
					<OPTION VALUE="" selected>maand</OPTION>
					<OPTION VALUE="01">januari</OPTION>
					<OPTION VALUE="02">februari</OPTION>
					<OPTION VALUE="03">maart</OPTION>
					<OPTION VALUE="04">april</OPTION>
					<OPTION VALUE="05">mei</OPTION>
					<OPTION VALUE="06">juni</OPTION>
					<OPTION VALUE="07">juli</OPTION>
					<OPTION VALUE="08">augustus</OPTION>
					<OPTION VALUE="09">september</OPTION>
					<OPTION VALUE="10">oktober</OPTION>
					<OPTION VALUE="11">november</OPTION>
					<OPTION VALUE="12">december</OPTION>
    		<? } ?>
				</SELECT>
				<SELECT NAME="jaar" class='input'>
			<? if ($r[jaar] == "1974") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974" selected>1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1975") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975" selected>1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1976") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976" selected>1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1977") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977" selected>1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1978") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978" selected>1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1979") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979" selected>1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1980") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980" selected>1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1981") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981" selected>1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1982") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982" selected>1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1983") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983" selected>1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1984") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984" selected>1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1985") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985" selected>1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1986") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986" selected>1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1987") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987" selected>1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1988") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988" selected>1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1989") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989" selected>1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1990") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990" selected>1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1991") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991" selected>1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1992") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992" selected>1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1993") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993" selected>1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1994") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994" selected>1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1995") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995" selected>1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1996") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996" selected>1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1997") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997" selected>1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1998") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998" selected>1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "1999") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999" selected>1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
			<? } else if ($r[jaar] == "2000") { ?>
					<OPTION VALUE="">jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000" selected>2000</OPTION>
			<? } else  { ?>
					<OPTION VALUE="" selected>jaar</OPTION>
					<OPTION VALUE="1974">1974</OPTION>
					<OPTION VALUE="1975">1975</OPTION>
					<OPTION VALUE="1976">1976</OPTION>
					<OPTION VALUE="1977">1977</OPTION>
					<OPTION VALUE="1978">1978</OPTION>
					<OPTION VALUE="1979">1979</OPTION>
					<OPTION VALUE="1980">1980</OPTION>
					<OPTION VALUE="1981">1981</OPTION>
					<OPTION VALUE="1982">1982</OPTION>
					<OPTION VALUE="1983">1983</OPTION>
					<OPTION VALUE="1984">1984</OPTION>
					<OPTION VALUE="1985">1985</OPTION>
					<OPTION VALUE="1986">1986</OPTION>
					<OPTION VALUE="1987">1987</OPTION>
					<OPTION VALUE="1988">1988</OPTION>
					<OPTION VALUE="1989">1989</OPTION>
					<OPTION VALUE="1990">1990</OPTION>
					<OPTION VALUE="1991">1991</OPTION>
					<OPTION VALUE="1992">1992</OPTION>
					<OPTION VALUE="1993">1993</OPTION>
					<OPTION VALUE="1994">1994</OPTION>
					<OPTION VALUE="1995">1995</OPTION>
					<OPTION VALUE="1996">1996</OPTION>
					<OPTION VALUE="1997">1997</OPTION>
					<OPTION VALUE="1998">1998</OPTION>
					<OPTION VALUE="1999">1999</OPTION>
					<OPTION VALUE="2000">2000</OPTION>
    			<? } ?>
					</SELECT><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i><? echo $adres ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input class='input' type="text" name="adres" value="<?php echo $r[adres]; ?>" size="30"/>&nbsp;<input class='input' maxlength='4' type="text" name="nr" value="<?php echo $r[nr]; ?>" size="4"/>
				</td>
			  </tr>
			  <tr>
				<td>
					<input maxlength='4' class='input' type="text" name="postcode" value="<?php echo $r[postcode]; ?>" size="4"/>&nbsp;<input class='input' type="text" name="gemeente" value="<?php echo $r[gemeente]; ?>" size="30"/><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i><? echo $telefoon ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input class='input' type="text" name="telefoon" value="<?php echo $r[telefoon]; ?>" size="30"/><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i><? echo $email ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input class='input' type="text" name="email" value="<?php echo $r[email]; ?>" size="58"/><br /><br /><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<input tabindex='3' name="profiel" type="submit" class='verzenden' value="<? echo $send; ?>">
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	<?
	}
	else if ( $_GET['action'] == "wachtwoord_wijzigen" ) {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotoNONE = $instellingen[fotoNa];
	
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $ledenid";
		$result = mysql_query($query);
		$r = mysql_fetch_array($result);
		if ($r[fotos] == "NONE" || $r[fotos] == "na" || $r[fotos] == "") {
			$fotos = "$instellingen_fotoNONE";
		}
		else {
			$fotos = "$r[fotos]";
		}
	?>
	<form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	  <table width='100%' align='left' border='0' cellspacing='0' cellpadding='5'>
		<tr>
		  <td class='title' width='25%'>
			<img width='150' height='150' src='<? echo"$instellingen_mediafolder$fotos";?>' class='images'>
		  </td>
		  <td class='title' width='75%'>
			<? echo"<b>$naam</b>"; ?>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
			    <td>
					<i><? echo $wachtwoord_oud ?></i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input class='input' type="password" name="wachtwoordoud" size="40"/><br /><br />
				</td>
			  </tr>
			  <tr>
			    <td>
					<i><? echo $wachtwoord_1 ?></i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input class='input' type="password" name="wachtwoord1" size="40"/><br />
				</td>
			  </tr>
			  <tr>
			    <td>
					<i><? echo $wachtwoord_2 ?></i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input class='input' type="password" name="wachtwoord2" size="40"/><br /><br /><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<input name="wachtwoord_huidig" type="hidden" id="id" value="<? echo $r[user_password] ?>">
					<input tabindex='3' name="wachtwoord" type="submit" class='verzenden' value="<? echo $send; ?>">
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	<?
	}
	else if ( $_GET['action'] == "profielfoto_wijzigen" ) {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotoNONE = $instellingen[fotoNa];
	
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $ledenid";
		$result = mysql_query($query);
		$r = mysql_fetch_array($result);
		if ($r[fotos] == "NONE" || $r[fotos] == "na" || $r[fotos] == "") {
			$fotos = "$instellingen_fotoNONE";
		}
		else {
			$fotos = "$r[fotos]";
		}
	?>
	<form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
	  <table width='100%' align='left' border='0' cellspacing='0' cellpadding='5'>
		<tr>
		  <td class='title' width='25%'>
			<img width='150' height='150' src='<? echo"$instellingen_mediafolder$fotos";?>' class='images'>
		  </td>
		  <td class='title' width='75%'>
			<? echo"<b>$naam</b>"; ?>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
				<td>
					<? if ($r[fotos] == "NONE") { ?>
						<INPUT TYPE="checkbox" NAME="geen_profielfoto" VALUE="NONE" checked> <? echo $geen_profielfoto ?><br /><br />
					<? } else { ?>
						<INPUT TYPE="checkbox" NAME="geen_profielfoto" VALUE="NONE"> <? echo $geen_profielfoto ?><br /><br />
					<? } ?>
				</td>
			  </tr>
			  <tr>
				<td>
					<i>Upload foto:</i> <input type="file" name="file" id="file" class='verzenden'/>	<span class='important'><b>De foto mag niet groter zijn dan 300kb!</b></span><br /><br /><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<input tabindex='3' name="profielfoto" type="submit" class='verzenden' value="<? echo $send; ?>">
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	
	<?php
	}
	else if ( $_GET['action'] == "instellingen" && $_GET['subaction'] == "mediafolder" && $rights == "ALL" ) {
		mediafolder ();
	}
	else if ( $_GET['action'] == "instellingen" && $_GET['subaction'] == "verbodenfoto" && $rights == "ALL" ) {
		verbodenfoto ();
	}
	else if ( $_GET['action'] == "instellingen" && $_GET['subaction'] == "profielfotofolder" && $rights == "ALL" ) {
		profielfotofolder ();
	}
	else if ( $_GET['action'] == "instellingen" && $_GET['subaction'] == "homefolder" && $rights == "ALL" ) {
		homefolder ();
	}
	else if ( $_GET['action'] == "instellingen" && $_GET['subaction'] == "werkjaar" && $rights == "ALL" ) {
		werkjaar ();
	}
	else if ( $_GET['action'] == "instellingen" && $_GET['subaction'] == "rechten_semi" && $rights == "ALL" ) {
		rechten_semi ();
	}
	else if ( $_GET['action'] == "instellingen" && $rights == "ALL" ) {
		instellingen ();
	}
	else if ( $_GET['action'] == "logout" ) {
		session_destroy();	
		echo "<script>self.location='index.php';</script>";
	}
	else {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotoNONE = $instellingen[fotoNa];
	
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $ledenid";
		$result = mysql_query($query);
		$r = mysql_fetch_array($result);
		if ($r[fotos] == "NONE" || $r[fotos] == "na" || $r[fotos] == "") {
			$fotos = "$instellingen_fotoNONE";
		}
		else {
			$fotos = "$r[fotos]";
		}
echo"
	  <table width='100%' align='left' border='0' cellspacing='0' cellpadding='5'>
		<tr>
		  <td class='title' width='25%'>
			<img width='150' height='150' src='$instellingen_mediafolder$fotos' class='images'>
		  </td>
		  <td class='title' width='75%'>
			<b>$naam</b>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
			    <td>
					<u><i>Naam:</i></u>
				</td>
			  </tr>
			  <tr>
				<td>
					$r[naam] $r[voornaam]<br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<u><i>Geboortedatum:</i></u>
				</td>
			  </tr>
			  <tr>
				<td>
					$r[dag]
";
							switch ($r[maand]) {
								  case 1:
									echo "januari";
								  break;
								  case 2:
									echo "februari";
								  break;
								  case 3:
									echo "maart";
								  break;
								  case 4:
									echo "april";
								  break;
								  case 5:
									echo "mei";
								  break;
								  case 6:
									echo "juni";
								  break;
								  case 7:
									echo "juli";
								  break;
								  case 8:
									echo "augustus";
								  break;
								  case 9:
									echo "september";
								  break;
								  case 10:
									echo "oktober";
								  break;
								  case 11:
									echo "november";
								  break;
								  case 12:
									echo "december";
								  break;
								}
					echo"
					$r[jaar]<br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<u><i>Adres:</i></u>
				</td>
			  </tr>
			  <tr>
				<td>
					";
		if ($r[adres] <> "") {
echo"
					$r[adres] $r[nr]
				</td>
			  </tr>
			  <tr>
				<td>
";
		}
echo"
					$r[postcode] $r[gemeente]<br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
";
		if ($r[telefoon] <> "") {
echo"
					<u><i>Telefoon/GSM:</i></u>
				</td>
			  </tr>
			  <tr>
				<td>
					$r[telefoon]<br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
";
		}
echo"
					<u><i>Email:</i></u>
				</td>
			  </tr>
			  <tr>
				<td>
					<a title='$contacteer' href='mailto:$r[email]'>$r[email]</a><br /><br /><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i><img src='../_images/10px_right.gif'>&nbsp;<a href='?action=profiel_wijzigen'>Profiel wijzigen</a>
				</td>
			  </tr>
			  <tr>
				<td>
					<i><img src='../_images/10px_right.gif'>&nbsp;<a href='?action=wachtwoord_wijzigen'>Wachtwoord wijzigen</a>
				</td>
			  </tr>
			  <tr>
				<td>
					<i><img src='../_images/10px_right.gif'>&nbsp;<a href='?action=profielfoto_wijzigen'>Profielfoto wijzigen</a>
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
";
	}
}

function backup () {
	include ("config.php");
	$menu = $_GET['menu'];
	
	echo"
						<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
							<tr>
								<td align='left' BORDER='0' height='50' valign='top'>
		";
		if ($_GET['menu'] == "all") {
			echo"
									<a href='backup.php'><b>Sluit alle fora</b></a>
			";
		} else {
			echo"
									<a href='backup.php?menu=all'><b>Open alle fora</b></a>
			";
		}
		echo"
								</td>
							</tr>
		";
    		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
    		mysql_select_db($dbname_login) or die($dberror);
    		$queryforum = "SELECT * FROM forum_forums WHERE parent_id = '0' ORDER BY forum_id ASC"; 
    		$resultforum = mysql_query($queryforum);
			$a = 1;
	   		while ($forum = mysql_fetch_array($resultforum)) {
		echo "
							<tr>
								<td align='left' bgcolor='#000000' BORDER='0' STYLE='border: solid 1px #000000;' height='40'>
									$a <a href='?action=backup&menu=$forum[forum_name]'><b>$forum[forum_name]</b> <i>$forum[forum_desc]</i></a></font>
								</td>
							</tr>
		";
				$db = mysql_connect($dbhost,$dbuname,$dbpass); 
				mysql_select_db($dbname_login) or die($dberror);
				$querysubforum1 = "SELECT * FROM forum_forums WHERE parent_id = $forum[forum_id] ORDER BY forum_id ASC"; 
				$resultsubforum1 = mysql_query($querysubforum1);
				$b=1;
				while ($subforum1 = mysql_fetch_array($resultsubforum1)) {
				echo "
									<tr>
										<td align='left' bgcolor='#003300' BORDER='0' STYLE='border: solid 1px #000000;' height='40'>
											&nbsp;$a.$b <a href='?action=backup&menu=$subforum1[forum_name]'><b>$subforum1[forum_name]</b> <i>$subforum1[forum_desc]</i></a></font>
										</td>
									</tr>
				";
					$db = mysql_connect($dbhost,$dbuname,$dbpass); 
					mysql_select_db($dbname_login) or die($dberror);
					$querytopic = "SELECT * FROM forum_topics WHERE forum_id = $subforum1[forum_id] ORDER BY topic_id ASC"; 
					$resulttopic = mysql_query($querytopic);
					while ($topic = mysql_fetch_array($resulttopic)) {
			echo "
								<tr>
									<td align='left' bgcolor='#CBEEA8' BORDER='0' STYLE='border: solid 1px #000000;' height='30'>
										&nbsp;$topic[topic_title]
									</td>
								</tr>
			";
						if ($menu == $subforum1[forum_name] || $menu == "all") {
							$db = mysql_connect($dbhost,$dbuname,$dbpass); 
							mysql_select_db($dbname_login) or die($dberror);
							$querypost = "SELECT * FROM forum_posts WHERE topic_id = $topic[topic_id] ORDER BY post_id ASC"; 
							$resultpost = mysql_query($querypost);
							while ($post = mysql_fetch_array($resultpost)) {
								$txt = nl2br($post[post_text]);
				echo "
									<tr>
										<td align='left' BORDER='0' STYLE='border: solid 1px #000000;'>
											<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='3'>
												<tr>
													<td align='left' width='20%'>
				";
								$db = mysql_connect($dbhost,$dbuname,$dbpass); 
								mysql_select_db($dbname_login) or die($dberror);
								$queryuser = "SELECT * FROM forum_users WHERE user_id = $post[poster_id]"; 
								$resultuser = mysql_query($queryuser);
								$user = mysql_fetch_array($resultuser);
				echo"
														$user[username]
													</td>
													<td width='80%' align='left'>
														$txt
													</td>
												</tr>
											</table>
										</td>
									</tr>
				";
								
							}
						}
					}
					$db = mysql_connect($dbhost,$dbuname,$dbpass); 
					mysql_select_db($dbname_login) or die($dberror);
					$querysubforum2 = "SELECT * FROM forum_forums WHERE parent_id = $subforum1[forum_id] ORDER BY forum_id ASC"; 
					$resultsubforum2 = mysql_query($querysubforum2);
					$c=1;
					while ($subforum2 = mysql_fetch_array($resultsubforum2)) {
					echo "
										<tr>
											<td align='left' bgcolor='#006600' BORDER='0' STYLE='border: solid 1px #000000;' height='40'>
												&nbsp;&nbsp;$a.$b.$c <a href='?action=backup&menu=$subforum2[forum_name]'><b>$subforum2[forum_name]</b> <i>$subforum2[forum_desc]</i></a></font>
											</td>
										</tr>
					";
						$db = mysql_connect($dbhost,$dbuname,$dbpass); 
						mysql_select_db($dbname_login) or die($dberror);
						$querytopic = "SELECT * FROM forum_topics WHERE forum_id = $subforum2[forum_id] ORDER BY topic_id ASC"; 
						$resulttopic = mysql_query($querytopic);
						while ($topic = mysql_fetch_array($resulttopic)) {
				echo "
									<tr>
										<td align='left' bgcolor='#CBEEA8' BORDER='0' STYLE='border: solid 1px #000000;' height='30'>
											&nbsp;&nbsp;$topic[topic_title]
										</td>
									</tr>
				";
							if ($menu == $subforum2[forum_name] || $menu == "all") {
								$db = mysql_connect($dbhost,$dbuname,$dbpass); 
								mysql_select_db($dbname_login) or die($dberror);
								$querypost = "SELECT * FROM forum_posts WHERE topic_id = $topic[topic_id] ORDER BY post_id ASC"; 
								$resultpost = mysql_query($querypost);
								while ($post = mysql_fetch_array($resultpost)) {
									$txt = nl2br($post[post_text]);
					echo "
										<tr>
											<td align='left' BORDER='0' STYLE='border: solid 1px #000000;'>
												<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='3'>
													<tr>
														<td align='left' width='20%'>
					";
									$db = mysql_connect($dbhost,$dbuname,$dbpass); 
									mysql_select_db($dbname_login) or die($dberror);
									$queryuser = "SELECT * FROM forum_users WHERE user_id = $post[poster_id]"; 
									$resultuser = mysql_query($queryuser);
									$user = mysql_fetch_array($resultuser);
					echo"
															$user[username]
														</td>
														<td width='80%' align='left'>
															$txt
														</td>
													</tr>
												</table>
											</td>
										</tr>
					";
									
								}
							}
						}
						$db = mysql_connect($dbhost,$dbuname,$dbpass); 
						mysql_select_db($dbname_login) or die($dberror);
						$querysubforum3 = "SELECT * FROM forum_forums WHERE parent_id = $subforum2[forum_id] ORDER BY forum_id ASC"; 
						$resultsubforum3 = mysql_query($querysubforum3);
						$d=1;
						while ($subforum3 = mysql_fetch_array($resultsubforum3)) {
						echo "
											<tr>
												<td align='left' bgcolor='#009900' BORDER='0' STYLE='border: solid 1px #000000;' height='40'>
													&nbsp;&nbsp;&nbsp;$a.$b.$c.$d <a href='?action=backup&menu=$subforum3[forum_name]'><b>$subforum3[forum_name]</b> <i>$subforum3[forum_desc]</i></a></font>
												</td>
											</tr>
						";
							$db = mysql_connect($dbhost,$dbuname,$dbpass); 
							mysql_select_db($dbname_login) or die($dberror);
							$querytopic = "SELECT * FROM forum_topics WHERE forum_id = $subforum3[forum_id] ORDER BY topic_id ASC"; 
							$resulttopic = mysql_query($querytopic);
							while ($topic = mysql_fetch_array($resulttopic)) {
					echo "
										<tr>
											<td align='left' bgcolor='#CBEEA8' BORDER='0' STYLE='border: solid 1px #000000;' height='30'>
												&nbsp;&nbsp;&nbsp;$topic[topic_title]
											</td>
										</tr>
					";
								if ($menu == $subforum3[forum_name] || $menu == "all") {
									$db = mysql_connect($dbhost,$dbuname,$dbpass); 
									mysql_select_db($dbname_login) or die($dberror);
									$querypost = "SELECT * FROM forum_posts WHERE topic_id = $topic[topic_id] ORDER BY post_id ASC"; 
									$resultpost = mysql_query($querypost);
									while ($post = mysql_fetch_array($resultpost)) {
										$txt = nl2br($post[post_text]);
						echo "
											<tr>
												<td align='left' BORDER='0' STYLE='border: solid 1px #000000;'>
													<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='3'>
														<tr>
															<td align='left' width='20%'>
						";
										$db = mysql_connect($dbhost,$dbuname,$dbpass); 
										mysql_select_db($dbname_login) or die($dberror);
										$queryuser = "SELECT * FROM forum_users WHERE user_id = $post[poster_id]"; 
										$resultuser = mysql_query($queryuser);
										$user = mysql_fetch_array($resultuser);
						echo"
																$user[username]
															</td>
															<td width='80%' align='left'>
																$txt
															</td>
														</tr>
													</table>
												</td>
											</tr>
						";
										
									}
								}
							}
							$db = mysql_connect($dbhost,$dbuname,$dbpass); 
							mysql_select_db($dbname_login) or die($dberror);
							$querysubforum4 = "SELECT * FROM forum_forums WHERE parent_id = $subforum3[forum_id] ORDER BY forum_id ASC"; 
							$resultsubforum4 = mysql_query($querysubforum4);
							$e=1;
							while ($subforum4 = mysql_fetch_array($resultsubforum4)) {
							echo "
												<tr>
													<td align='left' bgcolor='#00CC00' BORDER='0' STYLE='border: solid 1px #000000;' height='40'>
														&nbsp;&nbsp;&nbsp;&nbsp;$a.$b.$c.$d.$e <a href='?action=backup&menu=$subforum4[forum_name]'><b>$subforum4[forum_name]</b> <i>$subforum4[forum_desc]</i></a></font>
													</td>
												</tr>
							";
								$db = mysql_connect($dbhost,$dbuname,$dbpass); 
								mysql_select_db($dbname_login) or die($dberror);
								$querytopic = "SELECT * FROM forum_topics WHERE forum_id = $subforum4[forum_id] ORDER BY topic_id ASC"; 
								$resulttopic = mysql_query($querytopic);
								while ($topic = mysql_fetch_array($resulttopic)) {
						echo "
											<tr>
												<td align='left' bgcolor='#CBEEA8' BORDER='0' STYLE='border: solid 1px #000000;' height='30'>
													&nbsp;&nbsp;&nbsp;&nbsp;$topic[topic_title]
												</td>
											</tr>
						";
									if ($menu == $subforum4[forum_name] || $menu == "all") {
										$db = mysql_connect($dbhost,$dbuname,$dbpass); 
										mysql_select_db($dbname_login) or die($dberror);
										$querypost = "SELECT * FROM forum_posts WHERE topic_id = $topic[topic_id] ORDER BY post_id ASC"; 
										$resultpost = mysql_query($querypost);
										while ($post = mysql_fetch_array($resultpost)) {
											$txt = nl2br($post[post_text]);
							echo "
												<tr>
													<td align='left' BORDER='0' STYLE='border: solid 1px #000000;'>
														<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='3'>
															<tr>
																<td align='left' width='20%'>
							";
											$db = mysql_connect($dbhost,$dbuname,$dbpass); 
											mysql_select_db($dbname_login) or die($dberror);
											$queryuser = "SELECT * FROM forum_users WHERE user_id = $post[poster_id]"; 
											$resultuser = mysql_query($queryuser);
											$user = mysql_fetch_array($resultuser);
							echo"
																	$user[username]
																</td>
																<td width='80%' align='left'>
																	$txt
																</td>
															</tr>
														</table>
													</td>
												</tr>
							";
											
										}
									}
								}
								$db = mysql_connect($dbhost,$dbuname,$dbpass); 
								mysql_select_db($dbname_login) or die($dberror);
								$querysubforum5 = "SELECT * FROM forum_forums WHERE parent_id = $subforum4[forum_id] ORDER BY forum_id ASC"; 
								$resultsubforum5 = mysql_query($querysubforum5);
								$f=1;
								while ($subforum5 = mysql_fetch_array($resultsubforum5)) {
								echo "
													<tr>
														<td align='left' bgcolor='#00FF00' BORDER='0' STYLE='border: solid 1px #000000;' height='40'>
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$a.$b.$c.$d.$e.$f <a href='?action=backup&menu=$subforum5[forum_name]'><b>$subforum5[forum_name]</b> <i>$subforum5[forum_desc]</i></a></font>
														</td>
													</tr>
								";
									$db = mysql_connect($dbhost,$dbuname,$dbpass); 
									mysql_select_db($dbname_login) or die($dberror);
									$querytopic = "SELECT * FROM forum_topics WHERE forum_id = $subforum5[forum_id] ORDER BY topic_id ASC"; 
									$resulttopic = mysql_query($querytopic);
									while ($topic = mysql_fetch_array($resulttopic)) {
							echo "
												<tr>
													<td align='left' bgcolor='#CBEEA8' BORDER='0' STYLE='border: solid 1px #000000;' height='30'>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$topic[topic_title]
													</td>
												</tr>
							";
										if ($menu == $subforum5[forum_name] || $menu == "all") {
											$db = mysql_connect($dbhost,$dbuname,$dbpass); 
											mysql_select_db($dbname_login) or die($dberror);
											$querypost = "SELECT * FROM forum_posts WHERE topic_id = $topic[topic_id] ORDER BY post_id ASC"; 
											$resultpost = mysql_query($querypost);
											while ($post = mysql_fetch_array($resultpost)) {
												$txt = nl2br($post[post_text]);
								echo "
													<tr>
														<td align='left' BORDER='0' STYLE='border: solid 1px #000000;'>
															<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='3'>
																<tr>
																	<td align='left' width='20%'>
								";
												$db = mysql_connect($dbhost,$dbuname,$dbpass); 
												mysql_select_db($dbname_login) or die($dberror);
												$queryuser = "SELECT * FROM forum_users WHERE user_id = $post[poster_id]"; 
												$resultuser = mysql_query($queryuser);
												$user = mysql_fetch_array($resultuser);
								echo"
																		$user[username]
																	</td>
																	<td width='80%' align='left'>
																		$txt
																	</td>
																</tr>
															</table>
														</td>
													</tr>
								";
												
											}
										}
									}
									$f++;
								}
								$e++;
							}
							$d++;
						}
						$c++;
					}
					$b++;
				}
				$a++;
			}
echo"
				</table>
";
}

function instellingen () {
	include ("config.php");
	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$queryR = "SELECT * FROM Instellingen"; 
	$resultR = mysql_query($queryR);
	$rR = mysql_fetch_array($resultR);
	
	echo"
		  <table width='100%' BORDER='0' align='center' cellspacing='0' cellpadding='0'>
			<tr>
			  <td class='title'>
				<b>Algemene instellingen site</b>
			  </td>
			</tr>
			<tr>
				<td>
					<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
						<tr> 
							<td class='ledenboven'>
								<b>Mediafolder</b>
							</td>
						</tr>
						<tr>
							<td>
								<a href='index.php?action=instellingen&subaction=mediafolder' target='_top'><img border='0' src='../_images/folder_15px12px.gif'> $rR[mediafolder]</a>
							</td>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
						<tr> 
							<td>
								<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
									<tr> 
										<td class='ledenboven'>
											<b>Afbeelding indien er geen foto van een lid aanwezig is op de server</b>
										</td>
									</tr>
									<tr>
										<td>
											<a href='index.php?action=instellingen&subaction=verbodenfoto' target='_top'><img width='150' height='150' src='../$rR[mediafolder]$rR[fotoNa]'>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr> 
							<td>
								<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
									<tr> 
										<td class='ledenboven'>
											<b>Folder voor de geuploade profielfoto's</b>
										</td>
									</tr>
									<tr>
										<td>
											<a href='index.php?action=instellingen&subaction=profielfotofolder' target='_top'><img border='0' src='../_images/folder_15px12px.gif'> $rR[profielfotofolder]</a>
										</td>
									</tr>
									<tr>
										<td>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr> 
							<td>
								<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
									<tr> 
										<td class='ledenboven'>
											<b>Folder voor de geuploade HOME-foto's</b>
										</td>
									</tr>
									<tr>
										<td>
											<a href='index.php?action=instellingen&subaction=homefolder' target='_top'><img border='0' src='../_images/folder_15px12px.gif'> $rR[homefolder]</a>
										</td>
									</tr>
									<tr>
										<td>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
						<tr> 
							<td class='ledenboven'>
								
								<b>Huidig werkjaar</b>
							</td>
						</tr>
						<tr>
							<td>
								<a href='index.php?action=instellingen&subaction=werkjaar' target='_top'>$rR[werkjaar]
							</td>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		  </table>
	";
}


// MEDIAFOLDER
function mediafolder() {
	include ("config.php");
	$id = $_GET['id'];
	$folder = $_GET['folder'];
	$best = $_GET['best'];

	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$instellingen_mediafolder = '../';


					?><form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><?
			echo"
		  <table width='100%' BORDER='0' align='center' cellspacing='0' cellpadding='0'>
			<tr>
				<td>
					<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
						<tr> 
							<td class='title'>
								<b>Mediafolder</b>
							</td>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
						<tr>
						  <td>
			";

// Naam van de root
$naam_root = "Mediafolder";

// Map waar icoontjes staan opgeslagen
$map_icoontjes = "../_images/";

// Array met verschillende bestands extenties en daar achter het afbeeldinkje
$file_icon = array('jpg' => 'jpg_18px.gif', 
                    'JPG' => 'jpg_18px.gif', 
                    'jpeg' => 'jpg_18px.gif', 
                    'JPEG' => 'jpg_18px.gif',
                    'gif' => 'gif_18px.gif', 
                    'GIF' => 'gif_18px.gif',
                    'png' => 'png_18px.gif', 
                    'PNG' => 'png_18px.gif',
                    'bmp' => 'bmp_18px.gif', 
                    'BMP' => 'bmp_18px.gif',
                    'PDF' => 'pdf_18px.gif', 
                    'pdf' => 'pdf_18px.gif',
                    'doc' => 'doc_18px.gif', 
                    'docx' => 'doc_18px.gif', 
                    'DOC' => 'doc_18px.gif', 
                    'DOCX' => 'doc_18px.gif',
                    'XLT' => 'xls_18px.gif', 
                    'xlt' => 'xls_18px.gif', 
                    'xml' => 'xls_18px.gif', 
                    'XML' => 'xls_18px.gif', 
                    'xls' => 'xls_18px.gif', 
                    'XLS' => 'xls_18px.gif', 
                    'xlw' => 'xls_18px.gif', 
                    'XLW' => 'xls_18px.gif',
                    'txt' => 'txt_18px.gif', 
                    'TXT' => 'txt_18px.gif',
                    'htm' => 'html_18px.gif', 
                    'html' => 'html_18px.gif', 
                    'HTM' => 'html_18px.gif', 
                    'HTML' => 'html_18px.gif', 
                    'xml' => 'html_18px.gif', 
                    'XML' => 'html_18px.gif', 
                    'css' => 'css_18px.gif', 
                    'CSS' => 'css_18px.gif',
					'js' => 'css_18px.gif', 
					'JS' => 'css_18px.gif', 
                    'php' => 'php_18px.gif', 
                    'PHP' => 'php_18px.gif', 
                    'php3' => 'php_18px.gif', 
                    'PHP3' => 'php_18px.gif', 
                    'php4' => 'php_18px.gif', 
                    'PHP4' => 'php_18px.gif', 
                    'php5' => 'php_18px.gif', 
                    'PHP5' => 'php_18px.gif',
					'psd'  => 'psd_18px.gif',
					'PSD'  => 'psd_18px.gif',
					'ai'  => 'ai_18px.gif',
					'AI'  => 'ai_18px.gif',
					'eps'  => 'ai_18px.gif',
					'EPS'  => 'ai_18px.gif',
					'zip'  => 'zip_18px.gif',
					'ZIP'  => 'zip_18px.gif');

function file_size($size, $stap){
	$naam_eenheid = array('1' => 'B', '2' => 'kB', '3' => 'mB', '4' => 'gB');
    if($size >= 1024) {
		$size = $size / 1024;
		$stap = $stap + 1;
		file_size($size, $stap);
    }
	else {
        echo(round($size).$naam_eenheid[$stap]);
    }
}
$parts = explode("/", $_GET["folder"]);
$aantal = count($parts);
$i = 0;
while($i < ($aantal-2)) {
    $vorige .= $parts[$i]."/";
    $i++;
}
    ?>
    
        <div style="background-color: #F2F2EE; border: 1px solid #DDDDDD; width:705px; " id="verkenner">
            <a href="javascript:history.go(-1)"><img src="<?=$map_icoontjes?>folder_back.gif" border="0"></a>&nbsp;&nbsp;
            <a href="javascript:history.go(+1)"><img src="<?=$map_icoontjes?>folder_forward.gif" border="0"></a>&nbsp;&nbsp;
            <a href="index.php?action=instellingen&subaction=mediafolder&folder=<?=$vorige?>"><img src="<?=$map_icoontjes?>folder_omhoog.gif" border="0"></a>
        </div>
        <div style=" padding: 3px 0px 3px 0px; background-color: #F2F2EE; border-left: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD; width:705px; ">
            &nbsp;Adres: <input type="text" disabled value="<?=$naam_root?>:\<? $locatie = str_replace("/","\\",$folder); echo($locatie); ?>" size="96">
        </div>
        <div id="Map_structuur">
            <div id="Titel_structuur"><b>Mappen</b></div>
			<div id="Folder_structuur">
				<table cellpadding="0" cellspacing="0" border="0" width="280">
					<tr>        
						<td>
							<a href="index.php?action=instellingen&subaction=mediafolder"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left; ">&nbsp;<?=$naam_root?></a>
						</td> 
					</tr>
<?
function verkennen($cat, $path, $folder, $laag, $parts, $last, $map_icoontjes) {
	$id = $_GET['id'];
	$parts = explode("/", $_GET["folder"]);
	$aantal = count($parts);
	$dir = $path.$folder;
	           
    if ($dh = opendir($dir)) { 
		while (($file = readdir($dh)) !== false) {
			if($file != "." && $file != ".."){
				if(filetype($dir . $file) == "dir"){?>      
                    <tr>
                        <td><?
							$not_empty = "";
                            if ($dh2 = opendir($dir.$file)) { 
								while (($file2 = readdir($dh2)) !== false) {
									if($file2 != "." && $file2 != "..") {
										if(filetype($dir.$file."/".$file2) == "dir") {
											$not_empty = $file2;
                                        }
                                    } 
                                }
                                closedir($dh2);
							}
                            if(!$not_empty) {
								$i = -1;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px<? 
                                    if($file == $last) {
										if(($laag-1) == $i) {
											echo("");
                                        }
                                    }
									?>.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
							}
                            if($not_empty) {
                                $i = 0;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
                                ?><a href="index.php?action=instellingen&subaction=mediafolder&folder=<?=$folder.$file?>/"><img src="../_images/folder_sub_15px12px<?						
                                if($file == $last){
                                    echo("");
                                }
								?>.gif" style="vertical-align: middle; float:left; " border="0"></a><?
                            }
                            ?><a href="index.php?action=instellingen&subaction=mediafolder&folder=<?=$folder.$file?>/"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left;"><? 
								
							?>&nbsp;<?=$file?><?
							if($parts[($aantal-2)] == $file) {
								?></div><?
							}
							?></a>
						</td>                                
                    </tr>
                                
                            <? if($parts[$laag] == $file) {
								$laag_temp = $laag + 1;
                                verkennen($cat, $path, $folder.$file."/", $laag_temp, $parts, $not_empty, $map_icoontjes);
                            }
                                
				}
			} 
		}
        closedir($dh);
	}
}     
	$laag = 0;
	$parts = explode("/", "/".$_GET["folder"]);    
	if ($dh2 = opendir($instellingen_mediafolder)) { 
		while (($file2 = readdir($dh2)) !== false) {
			if($file2 != "." && $file2 != "..") {
				if(filetype($instellingen_mediafolder.$file2) == "dir") {
					$not_empty = $file2;
				}
			} 
		}
		closedir($dh2);
	}
                
    verkennen($cat, $instellingen_mediafolder, '', $laag, $parts, $not_empty, $map_icoontjes);?>
            
				</table>
			</div>
		</div>
        <div id="Map_self">
            <table cellpadding="0" cellspacing="0" border="0" width="490">
                <tr>                
                    <td class="Titel_self" width="250">Naam</td>
                    <td class="Titel_self" width="140">Type</td>
                    <td class="Titel_self" width="60">Grootte</td>
					<td class="Titel_self" width="140">Laatst gewijzigd</td>
                </tr>
            <?
            $mappen = array();
            
			$dir = $instellingen_mediafolder.$_GET["folder"];
			
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != "." && $file != ".."){
                        if(filetype($dir . $file) == "dir"){
                            $mappen[] = $file;
                        }
                    } 
                }
                closedir($dh);
            }
            sort($mappen);
            $i = 0;
            while($i < (count($mappen))){
				?>
				<tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=mediafolder&folder=<?=$_GET["folder"].$mappen[$i]?>/"><img src="../_images/folder_18px.gif" border="0" height="18" width="18" style="vertical-align: middle;"> <?=$mappen[$i]?></a></td>
                    <td style="font-size:11px; ">Bestandsmap</div></td>
                    <td style="font-size:11px; ">&nbsp;</td>
                    <td style="font-size:11px; "><? echo date("d-m-Y H:i", filectime($dir.$mappen[$i])); ?></td>
                </tr>
            <?
                $i++;
            }
            $bestanden = array();
            
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != '.'){ if($file != '..'){
                        if(filetype($dir . $file) == "file"){
                            $bestanden[] = $file;
                        }
                    } 
                }
            }
        }
            
            if(($_GET["sort"] == "name") OR (!$_GET["sort"])){
                sort($bestanden);
            }
            if($_GET["sort"] == "name_x"){
                rsort($bestanden);
            }
            $i = 0;
            while($i < (count($bestanden))){
                $parts = explode(".", $bestanden[$i]);
                $aantal_punten = count($parts) - 1;
                $extensie = $parts[$aantal_punten];
    
                ?>
                            
                <tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=mediafolder&folder=<? echo $_GET['folder']; ?>&best=<? echo $bestanden[$i]; ?>"><img height="18" width="18" src="<?
						if(array_key_exists($extensie, $file_icon)){
								echo($map_icoontjes.$file_icon[$extensie]);
						}
						else {
								echo($map_icoontjes.'else_18px.gif');
						}
                    ?>" border="0" style="vertical-align: middle;"> <?=substr($bestanden[$i], 0, 32)?> </a></td>
                    <td style="font-size:11px; "><?=$extensie?>-bestand</td>
                    <td style="font-size:11px; "><?
                    $size = filesize($dir.$bestanden[$i]);
                    $totaal_size += $size;
                    file_size($size,"1");
                    $totaal_bestanden += 1;
                    ?></td>
                
                </tr>
                                
            <?
                $icon_file = "";
                $name_type = "";
                $i++;
                }
            ?>
            
            </table>
            
            </div>
            
            <div style="background-color: #F2F2EE; border-right: 1px solid #DDDDDD; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:550px; float:left; ">&nbsp;&nbsp;<? if(!$totaal_bestanden) { echo("0"); } if($totaal_bestanden) { echo($totaal_bestanden); } ?> Objecten </div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:50px; float:left; "><?=file_size($totaal_size, "1")?></div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:104px; float:left; ">&nbsp;</div>
            
        </div>
    </div><?
			echo"
						</td>
					</tr>
					<tr>
						<td>
			";
	?>
	<br>
	<input type="hidden" name="folder" value="<? echo $folder ?>">
	<input tabindex='3' name="mediafolder" type="submit" class='verzenden' value="<? echo $sendmediafolder; ?>">
  	</form>
	<?
			echo"
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
			";
} 


// FOTO VERBODEN
function verbodenfoto() {
	include ("config.php");
	$id = $_GET['id'];
	$folder = $_GET['folder'];
	$best = $_GET['best'];

	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$instellingen_mediafolder = '../'.$instellingen[mediafolder];
	$instellingen_fotoOnb = $instellingen[fotoOnb];


					?><form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><?
echo"
		  <table width='100%' BORDER='0' align='center' cellspacing='0' cellpadding='0'>
			<tr>
				<td>
					<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
						<tr> 
							<td class='title'>
								<b>Afbeelding indien er geen foto van een lid aanwezig is op de server</b>
							</td>
						</tr>
						<tr>
							<td class='ledenonder' valign='top'>
								<b><i>Voorbeeld: </b></i>
";
			if ($best <> "") {
echo"
								<img width='150' height='150' class='images' src='../$instellingen_mediafolder$folder$best'>
";
			} else {
echo"
								<img height='150' class='images' src='../$instellingen_fotoOnb'>
";
			}
echo"
							</td>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					  <table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
						<tr>
						  <td>
			";

// Naam van de root
$naam_root = "Mediafolder";

// Map waar icoontjes staan opgeslagen
$map_icoontjes = "../_images/";

// Array met verschillende bestands extenties en daar achter het afbeeldinkje
$file_icon = array('jpg' => 'jpg_18px.gif', 
                    'JPG' => 'jpg_18px.gif', 
                    'jpeg' => 'jpg_18px.gif', 
                    'JPEG' => 'jpg_18px.gif',
                    'gif' => 'gif_18px.gif', 
                    'GIF' => 'gif_18px.gif',
                    'png' => 'png_18px.gif', 
                    'PNG' => 'png_18px.gif',
                    'bmp' => 'bmp_18px.gif', 
                    'BMP' => 'bmp_18px.gif',
                    'PDF' => 'pdf_18px.gif', 
                    'pdf' => 'pdf_18px.gif',
                    'doc' => 'doc_18px.gif', 
                    'docx' => 'doc_18px.gif', 
                    'DOC' => 'doc_18px.gif', 
                    'DOCX' => 'doc_18px.gif',
                    'XLT' => 'xls_18px.gif', 
                    'xlt' => 'xls_18px.gif', 
                    'xml' => 'xls_18px.gif', 
                    'XML' => 'xls_18px.gif', 
                    'xls' => 'xls_18px.gif', 
                    'XLS' => 'xls_18px.gif', 
                    'xlw' => 'xls_18px.gif', 
                    'XLW' => 'xls_18px.gif',
                    'txt' => 'txt_18px.gif', 
                    'TXT' => 'txt_18px.gif',
                    'htm' => 'html_18px.gif', 
                    'html' => 'html_18px.gif', 
                    'HTM' => 'html_18px.gif', 
                    'HTML' => 'html_18px.gif', 
                    'xml' => 'html_18px.gif', 
                    'XML' => 'html_18px.gif', 
                    'css' => 'css_18px.gif', 
                    'CSS' => 'css_18px.gif',
					'js' => 'css_18px.gif', 
					'JS' => 'css_18px.gif', 
                    'php' => 'php_18px.gif', 
                    'PHP' => 'php_18px.gif', 
                    'php3' => 'php_18px.gif', 
                    'PHP3' => 'php_18px.gif', 
                    'php4' => 'php_18px.gif', 
                    'PHP4' => 'php_18px.gif', 
                    'php5' => 'php_18px.gif', 
                    'PHP5' => 'php_18px.gif',
					'psd'  => 'psd_18px.gif',
					'PSD'  => 'psd_18px.gif',
					'ai'  => 'ai_18px.gif',
					'AI'  => 'ai_18px.gif',
					'eps'  => 'ai_18px.gif',
					'EPS'  => 'ai_18px.gif',
					'zip'  => 'zip_18px.gif',
					'ZIP'  => 'zip_18px.gif');

function file_size($size, $stap){
	$naam_eenheid = array('1' => 'B', '2' => 'kB', '3' => 'mB', '4' => 'gB');
    if($size >= 1024) {
		$size = $size / 1024;
		$stap = $stap + 1;
		file_size($size, $stap);
    }
	else {
        echo(round($size).$naam_eenheid[$stap]);
    }
}
$parts = explode("/", $_GET["folder"]);
$aantal = count($parts);
$i = 0;
while($i < ($aantal-2)) {
    $vorige .= $parts[$i]."/";
    $i++;
}
    ?>
    
        <div style="background-color: #F2F2EE; border: 1px solid #DDDDDD; width:705px; " id="verkenner">
            <a href="javascript:history.go(-1)"><img src="<?=$map_icoontjes?>folder_back.gif" border="0"></a>&nbsp;&nbsp;
            <a href="javascript:history.go(+1)"><img src="<?=$map_icoontjes?>folder_forward.gif" border="0"></a>&nbsp;&nbsp;
            <a href="index.php?action=instellingen&subaction=verbodenfoto&folder=<?=$vorige?>"><img src="<?=$map_icoontjes?>folder_omhoog.gif" border="0"></a>
        </div>
        <div style=" padding: 3px 0px 3px 0px; background-color: #F2F2EE; border-left: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD; width:705px; ">
            &nbsp;Adres: <input type="text" disabled value="<?=$naam_root?>:\<? $locatie = str_replace("/","\\",$folder); echo($locatie); ?>" size="96">
        </div>
        <div id="Map_structuur">
            <div id="Titel_structuur"><b>Mappen</b></div>
			<div id="Folder_structuur">
				<table cellpadding="0" cellspacing="0" border="0" width="280">
					<tr>        
						<td>
							<a href="index.php?action=instellingen&subaction=verbodenfoto"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left; ">&nbsp;<?=$naam_root?></a>
						</td> 
					</tr>
<?
function verkennen($cat, $path, $folder, $laag, $parts, $last, $map_icoontjes) {
	$id = $_GET['id'];
	$parts = explode("/", $_GET["folder"]);
	$aantal = count($parts);
	$dir = $path.$folder;
	           
    if ($dh = opendir($dir)) { 
		while (($file = readdir($dh)) !== false) {
			if($file != "." && $file != ".."){
				if(filetype($dir . $file) == "dir"){?>      
                    <tr>
                        <td><?
							$not_empty = "";
                            if ($dh2 = opendir($dir.$file)) { 
								while (($file2 = readdir($dh2)) !== false) {
									if($file2 != "." && $file2 != "..") {
										if(filetype($dir.$file."/".$file2) == "dir") {
											$not_empty = $file2;
                                        }
                                    } 
                                }
                                closedir($dh2);
							}
                            if(!$not_empty) {
								$i = -1;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px<? 
                                    if($file == $last) {
										if(($laag-1) == $i) {
											echo("");
                                        }
                                    }
									?>.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
							}
                            if($not_empty) {
                                $i = 0;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
                                ?><a href="index.php?action=instellingen&subaction=verbodenfoto&folder=<?=$folder.$file?>/"><img src="../_images/folder_sub_15px12px<?						
                                if($file == $last){
                                    echo("");
                                }
								?>.gif" style="vertical-align: middle; float:left; " border="0"></a><?
                            }
                            ?><a href="index.php?action=instellingen&subaction=verbodenfoto&folder=<?=$folder.$file?>/"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left;"><? 
								
							?>&nbsp;<?=$file?><?
							if($parts[($aantal-2)] == $file) {
								?></div><?
							}
							?></a>
						</td>                                
                    </tr>
                                
                            <? if($parts[$laag] == $file) {
								$laag_temp = $laag + 1;
                                verkennen($cat, $path, $folder.$file."/", $laag_temp, $parts, $not_empty, $map_icoontjes);
                            }
                                
				}
			} 
		}
        closedir($dh);
	}
}     
	$laag = 0;
	$parts = explode("/", "/".$_GET["folder"]);    
	if ($dh2 = opendir($instellingen_mediafolder)) { 
		while (($file2 = readdir($dh2)) !== false) {
			if($file2 != "." && $file2 != "..") {
				if(filetype($instellingen_mediafolder.$file2) == "dir") {
					$not_empty = $file2;
				}
			} 
		}
		closedir($dh2);
	}
                
    verkennen($cat, $instellingen_mediafolder, '', $laag, $parts, $not_empty, $map_icoontjes);?>
            
				</table>
			</div>
		</div>
        <div id="Map_self">
            <table cellpadding="0" cellspacing="0" border="0" width="490">
                <tr>                
                    <td class="Titel_self" width="250">Naam</td>
                    <td class="Titel_self" width="140">Type</td>
                    <td class="Titel_self" width="60">Grootte</td>
					<td class="Titel_self" width="140">Laatst gewijzigd</td>
                </tr>
            <?
            $mappen = array();
            
			$dir = $instellingen_mediafolder.$_GET["folder"];
			
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != "." && $file != ".."){
                        if(filetype($dir . $file) == "dir"){
                            $mappen[] = $file;
                        }
                    } 
                }
                closedir($dh);
            }
            sort($mappen);
            $i = 0;
            while($i < (count($mappen))){
				?>
				<tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=verbodenfoto&folder=<?=$_GET["folder"].$mappen[$i]?>/"><img src="../_images/folder_18px.gif" border="0" height="18" width="18" style="vertical-align: middle;"> <?=$mappen[$i]?></a></td>
                    <td style="font-size:11px; ">Bestandsmap</div></td>
                    <td style="font-size:11px; ">&nbsp;</td>
                    <td style="font-size:11px; "><? echo date("d-m-Y H:i", filectime($dir.$mappen[$i])); ?></td>
                </tr>
            <?
                $i++;
            }
            $bestanden = array();
            
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != '.'){ if($file != '..'){
                        if(filetype($dir . $file) == "file"){
                            $bestanden[] = $file;
                        }
                    } 
                }
            }
        }
            
            if(($_GET["sort"] == "name") OR (!$_GET["sort"])){
                sort($bestanden);
            }
            if($_GET["sort"] == "name_x"){
                rsort($bestanden);
            }
            $i = 0;
            while($i < (count($bestanden))){
                $parts = explode(".", $bestanden[$i]);
                $aantal_punten = count($parts) - 1;
                $extensie = $parts[$aantal_punten];
    
                ?>
                            
                <tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=verbodenfoto&folder=<? echo $_GET['folder']; ?>&best=<? echo $bestanden[$i]; ?>"><img height="18" width="18" src="<?
						if(array_key_exists($extensie, $file_icon)){
								echo($map_icoontjes.$file_icon[$extensie]);
						}
						else {
								echo($map_icoontjes.'else_18px.gif');
						}
                    ?>" border="0" style="vertical-align: middle;"> <?=substr($bestanden[$i], 0, 32)?> </a></td>
                    <td style="font-size:11px; "><?=$extensie?>-bestand</td>
                    <td style="font-size:11px; "><?
                    $size = filesize($dir.$bestanden[$i]);
                    $totaal_size += $size;
                    file_size($size,"1");
                    $totaal_bestanden += 1;
                    ?></td>
                
                </tr>
                                
            <?
                $icon_file = "";
                $name_type = "";
                $i++;
                }
            ?>
            
            </table>
            
            </div>
            
            <div style="background-color: #F2F2EE; border-right: 1px solid #DDDDDD; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:550px; float:left; ">&nbsp;&nbsp;<? if(!$totaal_bestanden) { echo("0"); } if($totaal_bestanden) { echo($totaal_bestanden); } ?> Objecten </div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:50px; float:left; "><?=file_size($totaal_size, "1")?></div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:104px; float:left; ">&nbsp;</div>
            
        </div>
    </div><?
			echo"
						</td>
					</tr>
					<tr>
						<td>
			";
	?>
	<br>
	<input type="hidden" name="folder" value="<? echo $folder ?>">
	<input type="hidden" name="bestand" value="<? echo $best ?>">
	<input tabindex='3' name="verbodenfoto" type="submit" class='verzenden' value="<? echo $sendfoto; ?>">
  	</form>
	<?
			echo"
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
			";
} 

// FOLDER PROFIELFOTO
function profielfotofolder() {
	include ("config.php");
	$id = $_GET['id'];
	$folder = $_GET['folder'];
	$best = $_GET['best'];

	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$instellingen_mediafolder = '../'.$instellingen[mediafolder];


					?><form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><?
			echo"
		  <table width='100%' BORDER='0' align='center' cellspacing='0' cellpadding='0'>
			<tr>
				<td>
					<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
						<tr> 
							<td class='title'>
								<b>Folder voor de geuploade profielfoto's</b>
							</td>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
						<tr>
						  <td>
			";

// Naam van de root
$naam_root = "Mediafolder";

// Map waar icoontjes staan opgeslagen
$map_icoontjes = "../_images/";

// Array met verschillende bestands extenties en daar achter het afbeeldinkje
$file_icon = array('jpg' => 'jpg_18px.gif', 
                    'JPG' => 'jpg_18px.gif', 
                    'jpeg' => 'jpg_18px.gif', 
                    'JPEG' => 'jpg_18px.gif',
                    'gif' => 'gif_18px.gif', 
                    'GIF' => 'gif_18px.gif',
                    'png' => 'png_18px.gif', 
                    'PNG' => 'png_18px.gif',
                    'bmp' => 'bmp_18px.gif', 
                    'BMP' => 'bmp_18px.gif',
                    'PDF' => 'pdf_18px.gif', 
                    'pdf' => 'pdf_18px.gif',
                    'doc' => 'doc_18px.gif', 
                    'docx' => 'doc_18px.gif', 
                    'DOC' => 'doc_18px.gif', 
                    'DOCX' => 'doc_18px.gif',
                    'XLT' => 'xls_18px.gif', 
                    'xlt' => 'xls_18px.gif', 
                    'xml' => 'xls_18px.gif', 
                    'XML' => 'xls_18px.gif', 
                    'xls' => 'xls_18px.gif', 
                    'XLS' => 'xls_18px.gif', 
                    'xlw' => 'xls_18px.gif', 
                    'XLW' => 'xls_18px.gif',
                    'txt' => 'txt_18px.gif', 
                    'TXT' => 'txt_18px.gif',
                    'htm' => 'html_18px.gif', 
                    'html' => 'html_18px.gif', 
                    'HTM' => 'html_18px.gif', 
                    'HTML' => 'html_18px.gif', 
                    'xml' => 'html_18px.gif', 
                    'XML' => 'html_18px.gif', 
                    'css' => 'css_18px.gif', 
                    'CSS' => 'css_18px.gif',
					'js' => 'css_18px.gif', 
					'JS' => 'css_18px.gif', 
                    'php' => 'php_18px.gif', 
                    'PHP' => 'php_18px.gif', 
                    'php3' => 'php_18px.gif', 
                    'PHP3' => 'php_18px.gif', 
                    'php4' => 'php_18px.gif', 
                    'PHP4' => 'php_18px.gif', 
                    'php5' => 'php_18px.gif', 
                    'PHP5' => 'php_18px.gif',
					'psd'  => 'psd_18px.gif',
					'PSD'  => 'psd_18px.gif',
					'ai'  => 'ai_18px.gif',
					'AI'  => 'ai_18px.gif',
					'eps'  => 'ai_18px.gif',
					'EPS'  => 'ai_18px.gif',
					'zip'  => 'zip_18px.gif',
					'ZIP'  => 'zip_18px.gif');

function file_size($size, $stap){
	$naam_eenheid = array('1' => 'B', '2' => 'kB', '3' => 'mB', '4' => 'gB');
    if($size >= 1024) {
		$size = $size / 1024;
		$stap = $stap + 1;
		file_size($size, $stap);
    }
	else {
        echo(round($size).$naam_eenheid[$stap]);
    }
}
$parts = explode("/", $_GET["folder"]);
$aantal = count($parts);
$i = 0;
while($i < ($aantal-2)) {
    $vorige .= $parts[$i]."/";
    $i++;
}
    ?>
    
        <div style="background-color: #F2F2EE; border: 1px solid #DDDDDD; width:705px; " id="verkenner">
            <a href="javascript:history.go(-1)"><img src="<?=$map_icoontjes?>folder_back.gif" border="0"></a>&nbsp;&nbsp;
            <a href="javascript:history.go(+1)"><img src="<?=$map_icoontjes?>folder_forward.gif" border="0"></a>&nbsp;&nbsp;
            <a href="index.php?action=instellingen&subaction=profielfotofolder&folder=<?=$vorige?>"><img src="<?=$map_icoontjes?>folder_omhoog.gif" border="0"></a>
        </div>
        <div style=" padding: 3px 0px 3px 0px; background-color: #F2F2EE; border-left: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD; width:705px; ">
            &nbsp;Adres: <input type="text" disabled value="<?=$naam_root?>:\<? $locatie = str_replace("/","\\",$folder); echo($locatie); ?>" size="96">
        </div>
        <div id="Map_structuur">
            <div id="Titel_structuur"><b>Mappen</b></div>
			<div id="Folder_structuur">
				<table cellpadding="0" cellspacing="0" border="0" width="280">
					<tr>        
						<td>
							<a href="index.php?action=instellingen&subaction=profielfotofolder"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left; ">&nbsp;<?=$naam_root?></a>
						</td> 
					</tr>
<?
function verkennen($cat, $path, $folder, $laag, $parts, $last, $map_icoontjes) {
	$id = $_GET['id'];
	$parts = explode("/", $_GET["folder"]);
	$aantal = count($parts);
	$dir = $path.$folder;
	           
    if ($dh = opendir($dir)) { 
		while (($file = readdir($dh)) !== false) {
			if($file != "." && $file != ".."){
				if(filetype($dir . $file) == "dir"){?>      
                    <tr>
                        <td><?
							$not_empty = "";
                            if ($dh2 = opendir($dir.$file)) { 
								while (($file2 = readdir($dh2)) !== false) {
									if($file2 != "." && $file2 != "..") {
										if(filetype($dir.$file."/".$file2) == "dir") {
											$not_empty = $file2;
                                        }
                                    } 
                                }
                                closedir($dh2);
							}
                            if(!$not_empty) {
								$i = -1;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px<? 
                                    if($file == $last) {
										if(($laag-1) == $i) {
											echo("");
                                        }
                                    }
									?>.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
							}
                            if($not_empty) {
                                $i = 0;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
                                ?><a href="index.php?action=instellingen&subaction=profielfotofolder&folder=<?=$folder.$file?>/"><img src="../_images/folder_sub_15px12px<?						
                                if($file == $last){
                                    echo("");
                                }
								?>.gif" style="vertical-align: middle; float:left; " border="0"></a><?
                            }
                            ?><a href="index.php?action=instellingen&subaction=profielfotofolder&folder=<?=$folder.$file?>/"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left;"><? 
								
							?>&nbsp;<?=$file?><?
							if($parts[($aantal-2)] == $file) {
								?></div><?
							}
							?></a>
						</td>                                
                    </tr>
                                
                            <? if($parts[$laag] == $file) {
								$laag_temp = $laag + 1;
                                verkennen($cat, $path, $folder.$file."/", $laag_temp, $parts, $not_empty, $map_icoontjes);
                            }
                                
				}
			} 
		}
        closedir($dh);
	}
}     
	$laag = 0;
	$parts = explode("/", "/".$_GET["folder"]);    
	if ($dh2 = opendir($instellingen_mediafolder)) { 
		while (($file2 = readdir($dh2)) !== false) {
			if($file2 != "." && $file2 != "..") {
				if(filetype($instellingen_mediafolder.$file2) == "dir") {
					$not_empty = $file2;
				}
			} 
		}
		closedir($dh2);
	}
                
    verkennen($cat, $instellingen_mediafolder, '', $laag, $parts, $not_empty, $map_icoontjes);?>
            
				</table>
			</div>
		</div>
        <div id="Map_self">
            <table cellpadding="0" cellspacing="0" border="0" width="490">
                <tr>                
                    <td class="Titel_self" width="250">Naam</td>
                    <td class="Titel_self" width="140">Type</td>
                    <td class="Titel_self" width="60">Grootte</td>
					<td class="Titel_self" width="140">Laatst gewijzigd</td>
                </tr>
            <?
            $mappen = array();
            
			$dir = $instellingen_mediafolder.$_GET["folder"];
			
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != "." && $file != ".."){
                        if(filetype($dir . $file) == "dir"){
                            $mappen[] = $file;
                        }
                    } 
                }
                closedir($dh);
            }
            sort($mappen);
            $i = 0;
            while($i < (count($mappen))){
				?>
				<tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=profielfotofolder&folder=<?=$_GET["folder"].$mappen[$i]?>/"><img src="../_images/folder_18px.gif" border="0" height="18" width="18" style="vertical-align: middle;"> <?=$mappen[$i]?></a></td>
                    <td style="font-size:11px; ">Bestandsmap</div></td>
                    <td style="font-size:11px; ">&nbsp;</td>
                    <td style="font-size:11px; "><? echo date("d-m-Y H:i", filectime($dir.$mappen[$i])); ?></td>
                </tr>
            <?
                $i++;
            }
            $bestanden = array();
            
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != '.'){ if($file != '..'){
                        if(filetype($dir . $file) == "file"){
                            $bestanden[] = $file;
                        }
                    } 
                }
            }
        }
            
            if(($_GET["sort"] == "name") OR (!$_GET["sort"])){
                sort($bestanden);
            }
            if($_GET["sort"] == "name_x"){
                rsort($bestanden);
            }
            $i = 0;
            while($i < (count($bestanden))){
                $parts = explode(".", $bestanden[$i]);
                $aantal_punten = count($parts) - 1;
                $extensie = $parts[$aantal_punten];
    
                ?>
                            
                <tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=profielfotofolder&folder=<? echo $_GET['folder']; ?>&best=<? echo $bestanden[$i]; ?>"><img height="18" width="18" src="<?
						if(array_key_exists($extensie, $file_icon)){
								echo($map_icoontjes.$file_icon[$extensie]);
						}
						else {
								echo($map_icoontjes.'else_18px.gif');
						}
                    ?>" border="0" style="vertical-align: middle;"> <?=substr($bestanden[$i], 0, 32)?> </a></td>
                    <td style="font-size:11px; "><?=$extensie?>-bestand</td>
                    <td style="font-size:11px; "><?
                    $size = filesize($dir.$bestanden[$i]);
                    $totaal_size += $size;
                    file_size($size,"1");
                    $totaal_bestanden += 1;
                    ?></td>
                
                </tr>
                                
            <?
                $icon_file = "";
                $name_type = "";
                $i++;
                }
            ?>
            
            </table>
            
            </div>
            
            <div style="background-color: #F2F2EE; border-right: 1px solid #DDDDDD; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:550px; float:left; ">&nbsp;&nbsp;<? if(!$totaal_bestanden) { echo("0"); } if($totaal_bestanden) { echo($totaal_bestanden); } ?> Objecten </div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:50px; float:left; "><?=file_size($totaal_size, "1")?></div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:104px; float:left; ">&nbsp;</div>
            
        </div>
    </div><?
			echo"
						</td>
					</tr>
					<tr>
						<td>
			";
	?>
	<br>
	<input type="hidden" name="folder" value="<? echo $folder ?>">
	<input tabindex='3' name="profielfoto_folder" type="submit" class='verzenden' value="<? echo $sendmediafolder; ?>">
  	</form>
	<?
			echo"
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
			";
} 

// FOLDER HOME FOTO's
function homefolder() {
	include ("config.php");
	$id = $_GET['id'];
	$folder = $_GET['folder'];
	$best = $_GET['best'];

	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$instellingen_mediafolder = '../'.$instellingen[mediafolder];


					?><form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><?
			echo"
		  <table width='100%' BORDER='0' align='center' cellspacing='0' cellpadding='0'>
			<tr>
				<td>
					<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
						<tr> 
							<td class='title'>
								<b>Folder voor de geuploade profielfoto's</b>
							</td>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
						<tr>
						  <td>
			";

// Naam van de root
$naam_root = "Mediafolder";

// Map waar icoontjes staan opgeslagen
$map_icoontjes = "../_images/";

// Array met verschillende bestands extenties en daar achter het afbeeldinkje
$file_icon = array('jpg' => 'jpg_18px.gif', 
                    'JPG' => 'jpg_18px.gif', 
                    'jpeg' => 'jpg_18px.gif', 
                    'JPEG' => 'jpg_18px.gif',
                    'gif' => 'gif_18px.gif', 
                    'GIF' => 'gif_18px.gif',
                    'png' => 'png_18px.gif', 
                    'PNG' => 'png_18px.gif',
                    'bmp' => 'bmp_18px.gif', 
                    'BMP' => 'bmp_18px.gif',
                    'PDF' => 'pdf_18px.gif', 
                    'pdf' => 'pdf_18px.gif',
                    'doc' => 'doc_18px.gif', 
                    'docx' => 'doc_18px.gif', 
                    'DOC' => 'doc_18px.gif', 
                    'DOCX' => 'doc_18px.gif',
                    'XLT' => 'xls_18px.gif', 
                    'xlt' => 'xls_18px.gif', 
                    'xml' => 'xls_18px.gif', 
                    'XML' => 'xls_18px.gif', 
                    'xls' => 'xls_18px.gif', 
                    'XLS' => 'xls_18px.gif', 
                    'xlw' => 'xls_18px.gif', 
                    'XLW' => 'xls_18px.gif',
                    'txt' => 'txt_18px.gif', 
                    'TXT' => 'txt_18px.gif',
                    'htm' => 'html_18px.gif', 
                    'html' => 'html_18px.gif', 
                    'HTM' => 'html_18px.gif', 
                    'HTML' => 'html_18px.gif', 
                    'xml' => 'html_18px.gif', 
                    'XML' => 'html_18px.gif', 
                    'css' => 'css_18px.gif', 
                    'CSS' => 'css_18px.gif',
					'js' => 'css_18px.gif', 
					'JS' => 'css_18px.gif', 
                    'php' => 'php_18px.gif', 
                    'PHP' => 'php_18px.gif', 
                    'php3' => 'php_18px.gif', 
                    'PHP3' => 'php_18px.gif', 
                    'php4' => 'php_18px.gif', 
                    'PHP4' => 'php_18px.gif', 
                    'php5' => 'php_18px.gif', 
                    'PHP5' => 'php_18px.gif',
					'psd'  => 'psd_18px.gif',
					'PSD'  => 'psd_18px.gif',
					'ai'  => 'ai_18px.gif',
					'AI'  => 'ai_18px.gif',
					'eps'  => 'ai_18px.gif',
					'EPS'  => 'ai_18px.gif',
					'zip'  => 'zip_18px.gif',
					'ZIP'  => 'zip_18px.gif');

function file_size($size, $stap){
	$naam_eenheid = array('1' => 'B', '2' => 'kB', '3' => 'mB', '4' => 'gB');
    if($size >= 1024) {
		$size = $size / 1024;
		$stap = $stap + 1;
		file_size($size, $stap);
    }
	else {
        echo(round($size).$naam_eenheid[$stap]);
    }
}
$parts = explode("/", $_GET["folder"]);
$aantal = count($parts);
$i = 0;
while($i < ($aantal-2)) {
    $vorige .= $parts[$i]."/";
    $i++;
}
    ?>
    
        <div style="background-color: #F2F2EE; border: 1px solid #DDDDDD; width:705px; " id="verkenner">
            <a href="javascript:history.go(-1)"><img src="<?=$map_icoontjes?>folder_back.gif" border="0"></a>&nbsp;&nbsp;
            <a href="javascript:history.go(+1)"><img src="<?=$map_icoontjes?>folder_forward.gif" border="0"></a>&nbsp;&nbsp;
            <a href="index.php?action=instellingen&subaction=homefolder&folder=<?=$vorige?>"><img src="<?=$map_icoontjes?>folder_omhoog.gif" border="0"></a>
        </div>
        <div style=" padding: 3px 0px 3px 0px; background-color: #F2F2EE; border-left: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD; width:705px; ">
            &nbsp;Adres: <input type="text" disabled value="<?=$naam_root?>:\<? $locatie = str_replace("/","\\",$folder); echo($locatie); ?>" size="96">
        </div>
        <div id="Map_structuur">
            <div id="Titel_structuur"><b>Mappen</b></div>
			<div id="Folder_structuur">
				<table cellpadding="0" cellspacing="0" border="0" width="280">
					<tr>        
						<td>
							<a href="index.php?action=instellingen&subaction=homefolder"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left; ">&nbsp;<?=$naam_root?></a>
						</td> 
					</tr>
<?
function verkennen($cat, $path, $folder, $laag, $parts, $last, $map_icoontjes) {
	$id = $_GET['id'];
	$parts = explode("/", $_GET["folder"]);
	$aantal = count($parts);
	$dir = $path.$folder;
	           
    if ($dh = opendir($dir)) { 
		while (($file = readdir($dh)) !== false) {
			if($file != "." && $file != ".."){
				if(filetype($dir . $file) == "dir"){?>      
                    <tr>
                        <td><?
							$not_empty = "";
                            if ($dh2 = opendir($dir.$file)) { 
								while (($file2 = readdir($dh2)) !== false) {
									if($file2 != "." && $file2 != "..") {
										if(filetype($dir.$file."/".$file2) == "dir") {
											$not_empty = $file2;
                                        }
                                    } 
                                }
                                closedir($dh2);
							}
                            if(!$not_empty) {
								$i = -1;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px<? 
                                    if($file == $last) {
										if(($laag-1) == $i) {
											echo("");
                                        }
                                    }
									?>.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
							}
                            if($not_empty) {
                                $i = 0;
                                while($i < $laag) {
									?><img src="../_images/folder_empty_15px12px.gif" style="vertical-align: middle; float:left; "><?
                                    $i++;
                                }
                                ?><a href="index.php?action=instellingen&subaction=homefolder&folder=<?=$folder.$file?>/"><img src="../_images/folder_sub_15px12px<?						
                                if($file == $last){
                                    echo("");
                                }
								?>.gif" style="vertical-align: middle; float:left; " border="0"></a><?
                            }
                            ?><a href="index.php?action=instellingen&subaction=homefolder&folder=<?=$folder.$file?>/"><img src="../_images/folder_15px12px.gif" border="0" style="vertical-align: middle; float:left;"><? 
								
							?>&nbsp;<?=$file?><?
							if($parts[($aantal-2)] == $file) {
								?></div><?
							}
							?></a>
						</td>                                
                    </tr>
                                
                            <? if($parts[$laag] == $file) {
								$laag_temp = $laag + 1;
                                verkennen($cat, $path, $folder.$file."/", $laag_temp, $parts, $not_empty, $map_icoontjes);
                            }
                                
				}
			} 
		}
        closedir($dh);
	}
}     
	$laag = 0;
	$parts = explode("/", "/".$_GET["folder"]);    
	if ($dh2 = opendir($instellingen_mediafolder)) { 
		while (($file2 = readdir($dh2)) !== false) {
			if($file2 != "." && $file2 != "..") {
				if(filetype($instellingen_mediafolder.$file2) == "dir") {
					$not_empty = $file2;
				}
			} 
		}
		closedir($dh2);
	}
                
    verkennen($cat, $instellingen_mediafolder, '', $laag, $parts, $not_empty, $map_icoontjes);?>
            
				</table>
			</div>
		</div>
        <div id="Map_self">
            <table cellpadding="0" cellspacing="0" border="0" width="490">
                <tr>                
                    <td class="Titel_self" width="250">Naam</td>
                    <td class="Titel_self" width="140">Type</td>
                    <td class="Titel_self" width="60">Grootte</td>
					<td class="Titel_self" width="140">Laatst gewijzigd</td>
                </tr>
            <?
            $mappen = array();
            
			$dir = $instellingen_mediafolder.$_GET["folder"];
			
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != "." && $file != ".."){
                        if(filetype($dir . $file) == "dir"){
                            $mappen[] = $file;
                        }
                    } 
                }
                closedir($dh);
            }
            sort($mappen);
            $i = 0;
            while($i < (count($mappen))){
				?>
				<tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=homefolder&folder=<?=$_GET["folder"].$mappen[$i]?>/"><img src="../_images/folder_18px.gif" border="0" height="18" width="18" style="vertical-align: middle;"> <?=$mappen[$i]?></a></td>
                    <td style="font-size:11px; ">Bestandsmap</div></td>
                    <td style="font-size:11px; ">&nbsp;</td>
                    <td style="font-size:11px; "><? echo date("d-m-Y H:i", filectime($dir.$mappen[$i])); ?></td>
                </tr>
            <?
                $i++;
            }
            $bestanden = array();
            
            if ($dh = opendir($dir)) { 
                while (($file = readdir($dh)) !== false) {
                    if($file != '.'){ if($file != '..'){
                        if(filetype($dir . $file) == "file"){
                            $bestanden[] = $file;
                        }
                    } 
                }
            }
        }
            
            if(($_GET["sort"] == "name") OR (!$_GET["sort"])){
                sort($bestanden);
            }
            if($_GET["sort"] == "name_x"){
                rsort($bestanden);
            }
            $i = 0;
            while($i < (count($bestanden))){
                $parts = explode(".", $bestanden[$i]);
                $aantal_punten = count($parts) - 1;
                $extensie = $parts[$aantal_punten];
    
                ?>
                            
                <tr onMouseOver="style.backgroundColor = '#EFEFEF';" onMouseOut="style.backgroundColor = '#FFFFFF';">
                    <td style="font-size:11px; ">
						<a href="index.php?action=instellingen&subaction=homefolder&folder=<? echo $_GET['folder']; ?>&best=<? echo $bestanden[$i]; ?>"><img height="18" width="18" src="<?
						if(array_key_exists($extensie, $file_icon)){
								echo($map_icoontjes.$file_icon[$extensie]);
						}
						else {
								echo($map_icoontjes.'else_18px.gif');
						}
                    ?>" border="0" style="vertical-align: middle;"> <?=substr($bestanden[$i], 0, 32)?> </a></td>
                    <td style="font-size:11px; "><?=$extensie?>-bestand</td>
                    <td style="font-size:11px; "><?
                    $size = filesize($dir.$bestanden[$i]);
                    $totaal_size += $size;
                    file_size($size,"1");
                    $totaal_bestanden += 1;
                    ?></td>
                
                </tr>
                                
            <?
                $icon_file = "";
                $name_type = "";
                $i++;
                }
            ?>
            
            </table>
            
            </div>
            
            <div style="background-color: #F2F2EE; border-right: 1px solid #DDDDDD; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:550px; float:left; ">&nbsp;&nbsp;<? if(!$totaal_bestanden) { echo("0"); } if($totaal_bestanden) { echo($totaal_bestanden); } ?> Objecten </div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:50px; float:left; "><?=file_size($totaal_size, "1")?></div>
            <div style="background-color: #F2F2EE; border-left: 1px solid #FFFFFF; border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; width:104px; float:left; ">&nbsp;</div>
            
        </div>
    </div><?
			echo"
						</td>
					</tr>
					<tr>
						<td>
			";
	?>
	<br>
	<input type="hidden" name="folder" value="<? echo $folder ?>">
	<input tabindex='3' name="upload_home_folder" type="submit" class='verzenden' value="<? echo $sendmediafolder; ?>">
  	</form>
	<?
			echo"
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
			";
} 


// STARTPAGINA
//verwijderd wegens niet nodig!

// WERKJAAR
function werkjaar() {
	include ("config.php");
	
	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$instellingen_werkjaar = $instellingen[werkjaar];

					?><form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><?
echo"
	<table width='100%' BORDER='0' align='center' cellspacing='0' cellpadding='0'>
		<tr>
			<td>
				<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
					<tr> 
						<td class='title'>
							<b>Huidig werkjaar</b>
						</td>
					</tr>
					<tr>
						<td>
";
?>
							<SELECT NAME="werkjaar" MULTIPLE SIZE="12">
								<? if ($instellingen_werkjaar == '2008-2009') { ?>
									<OPTION VALUE="2008-2009" selected>2008-2009</OPTION>
								<? }
								else { ?>
									<OPTION VALUE="2008-2009">2008-2009</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2009-2010') { ?>
									<OPTION VALUE="2009-2010" selected>2009-2010</OPTION>
								<? }
								else { ?>
									<OPTION VALUE="2009-2010">2009-2010</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2010-2011') { ?>
									<OPTION VALUE="2010-2011" selected>2010-2011</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2010-2011">2010-2011</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2011-2012') { ?>
									<OPTION VALUE="2011-2012" selected>2011-2012</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2011-2012">2011-2012</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2012-2013') { ?>
									<OPTION VALUE="2012-2013" selected>2012-2013</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2012-2013">2012-2013</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2013-2014') { ?>
									<OPTION VALUE="2013-2014" selected>2013-2014</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2013-2014">2013-2014</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2014-2015') { ?>
									<OPTION VALUE="2014-2015" selected>2014-2015</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2014-2015">2014-2015</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2015-2016') { ?>
									<OPTION VALUE="2015-2016" selected>2015-2016</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2015-2016">2015-2016</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2016-2017') { ?>
									<OPTION VALUE="2016-2017" selected>2016-2017</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2016-2017">2016-2017</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2017-2018') { ?>
									<OPTION VALUE="2017-2018" selected>2017-2018</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2017-2018">2017-2018</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2018-2019') { ?>
									<OPTION VALUE="2018-2019" selected>2018-2019</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2018-2019">2018-2019</OPTION>
								<? } 
								if ($instellingen_werkjaar == '2019-2020') { ?>
									<OPTION VALUE="2019-2020" selected>2019-2020</OPTION>
								<? } 
								else { ?>
									<OPTION VALUE="2019-2020">2019-2020</OPTION>
								<? } ?>
							</SELECT>
							<br><br><input name="werkjaar_verzenden" type="submit" class='verzenden' value="<? echo $send; ?>"> <?
echo"	
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
					<tr>
						<td>
							
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
			";
}

// RECHTEN SEMI
function rechten_semi() {
	include ("config.php");

	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);

					?><form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><?
echo"
	<table width='100%' BORDER='0' align='center' cellspacing='0' cellpadding='0'>
		<tr>
			<td class='title'>
				<b>Rechten: SEMI</b>
			</td>
		</tr>
		<tr>
			<td>
				<table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
					<tr> 
						<td class='ledenboven'>
							<b>Admin</b>
						</td>
					</tr>
					<tr>
						<td>
							<input type='checkbox' name='rechten' value='forum'>Snelkoppeling forum zichtbaar<br>
							<input type='checkbox' name='rechten' value='backup'>Backup forum zichtbaar<br>
							<input type='checkbox' name='rechten' value='instellingen'>Instellingen wijzigen<br>
							<input type='checkbox' name='rechten' value='one'>Snelkoppeling one admin zichtbaar<br>
							<input type='checkbox' name='rechten' value='database'>Snelkoppeling database zichtbaar<br>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
";
} 
?>