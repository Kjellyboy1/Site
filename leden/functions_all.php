<?php

function map($print) {
	include ("config.php");
	$action = $_GET['action'];
	$menu = $_GET['menu'];
	$id = $_GET['id'];
	$sort = $_GET['sort'];
	$delete = $_GET['delete'];
	$foto = $_GET['foto'];
			
	switch ($print) {
		case"yes":
			$print = "print.php";
		break;
		default:
			$print = "index.php";
	}

	if ($delete == "nok") {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='nok'>
						<b>". $delete_nok_1 ."</b><br><br>". $delete_nok_2 ."
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
	
	if (isset($_POST['nieuw'])) {
		$voornaam = $_POST['voornaam'];
		$naam = $_POST['naam'];
		$fotos = "";
		$dag = $_POST['dag'];
		$maand = $_POST['maand'];
		$jaar = $_POST['jaar'];
		$postcode = $_POST['postcode'];
		$gemeente = $_POST['gemeente'];
		$telefoon = $_POST['telefoon'];
  		$email = $_POST['email'];
		$position = $_POST['position'];
		$visible = $_POST['visible'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
        mysql_query("INSERT INTO Leden(voornaam,naam,fotos,dag,maand,jaar,postcode,gemeente,telefoon,email,position,visible) VALUES('$voornaam','$naam','$fotos','$dag','$maand','$jaar','$postcode','$gemeente','$telefoon','$email','$position','$visible')"); 
        mysql_close($db);
		echo"<script>self.location='index.php';</script>";
	}
	if (isset($_POST['profiel'])) {
		$id = $_REQUEST['id'];
		$voornaam = $_POST['voornaam'];
		$naam = $_POST['naam'];
		$fotos = "";
		$dag = $_POST['dag'];
		$maand = $_POST['maand'];
		$jaar = $_POST['jaar'];
		$postcode = $_POST['postcode'];
		$gemeente = $_POST['gemeente'];
		$telefoon = $_POST['telefoon'];
  		$email = $_POST['email'];
		$position = $_POST['position'];
		$visible = $_POST['visible'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
 		mysql_query("UPDATE Leden SET voornaam='$voornaam' WHERE id='$id'");
		mysql_query("UPDATE Leden SET naam='$naam' WHERE id='$id'");
		mysql_query("UPDATE Leden SET dag='$dag' WHERE id='$id'");
		mysql_query("UPDATE Leden SET maand='$maand' WHERE id='$id'");
		mysql_query("UPDATE Leden SET jaar='$jaar' WHERE id='$id'");
		mysql_query("UPDATE Leden SET postcode='$postcode' WHERE id='$id'");
		mysql_query("UPDATE Leden SET gemeente='$gemeente' WHERE id='$id'");
		mysql_query("UPDATE Leden SET telefoon='$telefoon' WHERE id='$id'");
		mysql_query("UPDATE Leden SET email='$email' WHERE id='$id'");
      	mysql_query("UPDATE Leden SET position='$position' WHERE id='$id'");
		mysql_query("UPDATE Leden SET visible='$visible' WHERE id='$id'");
        mysql_close($db);
		echo"<script>self.location='index.php';</script>";
	}
	if (isset($_POST['rechten_wijzigen'])) {
		$id = $_REQUEST['id'];
		$rechten = $_POST['rechten'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
        mysql_query("UPDATE Leden SET rights='$rechten' WHERE id='$id'");
        mysql_close($db);
		echo"<script>self.location='index.php';</script>";
	}
	if (isset($_POST['profielfotowijzigen'])) {
		$id = $_REQUEST['id'];

		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotofolder = $instellingen[profielfotofolder];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $id";
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
				echo"<script>self.location='?action=profielfoto_wijzigen&id=$id&foto=nok';</script>";
			}
		}
		else {
			$fotos = "NONE";
		}

		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Leden SET fotos='$fotos' WHERE id='$id'");
        mysql_close($db);
		echo"<script>self.location='index.php';</script>";
	}
	
	if ( $action == "delete" ) {
			$db = mysql_connect($dbhost,$dbuname,$dbpass); 
			mysql_select_db($dbname) or die($dberror);
			$queryH = "SELECT * FROM Leden ORDER BY id ASC"; 
			$resultH = mysql_query($queryH);
			$aantal = 0;
			while ($raant = mysql_fetch_array($resultH)) {
				$aantal++;
			}
			$i2 = 1;
			$i3 = 0;
			while ($i2 <= $aantal) {
				$id = $_POST["v". $i2 .""];
				if ($id <> "") {
					$db = mysql_connect($dbhost,$dbuname,$dbpass); 
					mysql_select_db($dbname) or die($dberror);
					mysql_query("DELETE FROM Leden WHERE id='$id'");
					$i3++;
				}
			$i2++;
			}
			if ($i3 == 0) {
				echo"<script>self.location='index.php?delete=nok';</script>";
			}
			echo"<script>self.location='index.php';</script>";
	}
	else if ( $action == "delete_profiel" ) {
		$id = $_GET['id'];
		
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
        mysql_select_db($dbname) or die($dberror);
		mysql_query("UPDATE Leden SET username='' WHERE id='$id'");
      	mysql_query("UPDATE Leden SET user_password='' WHERE id='$id'");
		mysql_query("UPDATE Leden SET rights='' WHERE id='$id'");
        mysql_close($db);
		echo"<script>self.location='index.php';</script>";
	}
	else if ($action == 'nieuw') {
	?>
	<form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	  <table width='100%' align='left' border='0' cellspacing='0' cellpadding='5'>
		<tr>
		  <td colspan='2' class='title' valign='middle'>
			<b>Nieuw (bestuurs-)lid toevoegen</b>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
			    <td>
					<i><? echo $naam ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input name="voornaam" type="text" class='input' size="15"/>&nbsp;<input name="naam" type="text" class='input' size="40"/><br /><br />
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
						  <option> </option>
						  <option value="01">1</option>
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
						</SELECT>
						<SELECT NAME="maand" class='input'>
						  <option> </option>
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
				  </SELECT>
						<SELECT NAME="jaar" class='input'>
						  <option> </option>
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
						</SELECT><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i><? echo $gemeente ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td>
			
				</td>
			  </tr>
			  <tr>
				<td>
					<input name="postcode" type="text" class='input' size="4" maxlength='4'/>&nbsp;<input class='input' type="text" name="gemeente" value="<?php echo $r[gemeente]; ?>" size="30"/>
<br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i><? echo $telefoon ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td><input name="telefoon" type="text" class='input' size="30"/><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i><? echo $email ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td><br />
				  <input name="email2" type="text" class='input' size="58"/>
				  <br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<fieldset class='fieldset'><legend class='legend'><i>Type</i></legend><br>
					<table border='0' cellspacing='0' cellpadding='0'>
						<tr>
							<td>
								<input type="radio" value="1" name="position"> <? echo $categorie1 ?></a><br />
							</td>
						  </tr>
						  <tr>
							<td>
								
								<input type="radio" value="5" name="position"> <? echo $categorie5 ?></a><br /><br />
							</td>
						</tr>
					</table>
					</fieldset><br>
				</td>
			  </tr>
			  <tr>
				<td>
					<fieldset class='fieldset'><legend class='legend'><i>Publicatie-opties</i></legend><br>
						<input type="checkbox" value="YES" name="visible"> <? echo $enable_visible; ?><br /><br />
					</fieldset><br><br>
  				    <input tabindex='3' name="nieuw" type="submit" class='verzenden' value="<? echo $send; ?>">
  				    </form>
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	<?
	}
	else if ($action == 'profiel_wijzigen') {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotoNONE = $instellingen[fotoNa];
		$instellingen_fotoINST = $instellingen[fotoInst];
	
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $id";
		$result = mysql_query($query);
		$r = mysql_fetch_array($result);
		if ($r[fotos] == "NONE") {
			$fotos = "$instellingen_fotoNONE";
		}
		else if ($r[fotos] == "na" || $r[fotos] == "") {
			$fotos = "$instellingen_fotoNONE";
		}
		else {
			$fotos = "$r[fotos]";
		}
	?>
	<form name="fr" method="post" action="index.php?action=delete_profiel&id=<? echo $id ?>">
	  <table width='100%' align='left' border='0' cellspacing='0' cellpadding='5'>
		<tr>
		  <td class='title' width='25%'>
			<img width='150' height='150' src='<? echo"$instellingen_mediafolder$fotos";?>' class='images'>
		  </td>
		  <td class='title' width='75%'>
		    <? if ($r[username] <> "") { ?>
			<?php echo"<b>$r[username]</b>&nbsp;&nbsp;&nbsp;"; ?> <input name="delete_profiel" type="submit" class='verzenden_title' value="<? echo $delete_profiel; ?>">
			<? } ?>
		  </td>
		</tr>
	</form>
	<form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
			    <td>
					<i><? echo $naam ?>:</i>
				</td>
			  </tr>
			  <tr>
				<td>
					<input class='input' type="text" name="voornaam" value="<?php echo $r[voornaam]; ?>Voornaam" size="15"/>&nbsp;<input class='input' type="text" name="naam" value="<?php echo $r[naam]; ?>Naam" size="40"/><br /><br />
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="" selected></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="" selected></OPTION>
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
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1975") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1976") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1977") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1978") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1979") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1980") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1981") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1982") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1983") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1984") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1985") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1986") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1987") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1988") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1989") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1990") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1991") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1992") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1993") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1994") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1995") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1996") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1997") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1998") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "1999") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "2000") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "2001") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001" selected>2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "2002") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002" selected>2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "2003") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003" selected>2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "2004") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004" selected>2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
			<? } else if ($r[jaar] == "2005") { ?>
					<OPTION VALUE=""></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005" selected>2005</OPTION>
			<? } else  { ?>
					<OPTION VALUE="" selected></OPTION>
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
					<OPTION VALUE="2001">2001</OPTION>
					<OPTION VALUE="2002">2002</OPTION>
					<OPTION VALUE="2003">2003</OPTION>
					<OPTION VALUE="2004">2004</OPTION>
					<OPTION VALUE="2005">2005</OPTION>
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
					<input class='input' type="text" name="email" value="<?php echo $r[email]; ?>" size="58"/><br /><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<fieldset class='fieldset'><legend class='legend'><i>Type</i></legend><br>
					<table border='0' cellspacing='0' cellpadding='0'>
						  <tr>
							<td>
								<? if ($r[position] == 1) { ?>
									<input type="radio" value="1" name="position" checked > <? echo $categorie1 ?></a><br />
								<? } else { ?>
									<input type="radio" value="1" name="position" > <? echo $categorie1 ?></a><br />
								<? } ?>
							</td>
						  </tr>
						  <tr>
							<td>
								<? if ($r[position] == 4) { ?>
									<input type="radio" value="4" name="position" checked > <? echo $categorie4 ?></a><br />
								<? } else { ?>
									<input type="radio" value="4" name="position" > <? echo $categorie4 ?></a><br />
								<? } ?>
							</td>
						  </tr>
						  <tr>
							<td>
								<? if ($r[position] == 5) { ?>
									<input type="radio" value="5" name="position" checked > <? echo $categorie5 ?></a><br /><br />
								<? } else { ?>
									<input type="radio" value="5" name="position" > <? echo $categorie5 ?></a><br /><br />
								<? } ?>
							</td>
						  </tr>
					</table>
					</fieldset><br>
				</td>
			  </tr>
			  <tr>
				<td>						
					<fieldset class='fieldset'><legend class='legend'><i>Publicatie-opties</i></legend><br>
						<? if ($r[visible] == "YES") { ?>
							<input type="checkbox" value="YES" name="visible" checked> <? echo $enable_visible; ?><br /><br />
						<? } else { ?>
							<input type="checkbox" value="YES" name="visible"> <? echo $enable_visible; ?><br /><br />
						<? } ?>
					</fieldset><br><br>
					<input type="hidden" name="id" value="<? echo $id ?>">
  				    <input tabindex='3' name="profiel" type="submit" class='verzenden' value="<? echo $send; ?>">
  				    </form>
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	<?
	}
	else if ($action == 'rechten_wijzigen') {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotoNONE = $instellingen[fotoNa];
		$instellingen_fotoINST = $instellingen[fotoInst];
	
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $id";
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
				<? echo "<img width='150' height='150' src='$instellingen_mediafolder$fotos' class='images'>"; ?>
			  </td>
			  <td class='title' width='75%'>
				<? echo "<b>$r[naam] $r[voornaam]</b>"; ?>
			  </td>
			</tr>
			<tr>
			  <td colspan='2' valign='top'>
				<table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
				  <tr>
					<td>
						<? if ($r[rights] == "NONE") { ?>
							<input type="radio" name="rechten" value="NONE" checked /> Geen rechten
						<? } else { ?>
							<input type="radio" name="rechten" value="NONE"/> Geen rechten
						<? } ?>
					</td>
				  </tr>
				  <tr>
					<td>
						<? if ($r[rights] == "ALL") { ?>
							<input type="radio" name="rechten" value="ALL" checked /> Alle rechten<br /><br /><br /><br /><br />
						<? } else { ?>
							<input type="radio" name="rechten" value="ALL"/> Alle rechten<br /><br /><br /><br /><br />
						<? } ?>
					</td>
				  </tr>
				  <tr>
					<td>
						<input type="hidden" name="id" value="<? echo $id ?>">
						<input tabindex='3' name="rechten_wijzigen" type="submit" class='verzenden' value="<? echo $send; ?>">
					</td>
				  </tr>
				</table>
			  </td>
			</tr>
		  </table>
		<?
	}
	else if ($action == 'profielfoto_wijzigen') {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
		$instellingen_fotoNONE = $instellingen[fotoNa];
	
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Leden WHERE id = $id";
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
			<? echo"<b>$r[naam] $r[voornaam]</b>"; ?>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
				<td>
					<INPUT TYPE="checkbox" NAME="geen_profielfoto" VALUE="NONE"> <? echo $geen_profielfoto ?><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<i>Upload foto:</i> <input type="file" name="file" id="file" class='verzenden'/>	<span class='important'>De foto moet <b><300kb</b> en een <b>'jpg, gif, png, bmp, jpeg, JPG'</b> bestand zijn!</span><br /><br /><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<input type="hidden" name="id" value="<? echo $id ?>">
					<input tabindex='3' name="profielfotowijzigen" type="submit" class='verzenden' value="<? echo $send; ?>">
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	
	<?	
	}
	else {
		$i=1;
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		if ($id <> "") {
			$naamb = "10px_right_disabled";
			$emailb = "10px_right_disabled";
			$gemeenteb = "10px_right_disabled";
			$bdayb = "10px_right_disabled";
		}
		else {
			$naamb = "10px_right";
			$emailb = "10px_right";
			$gemeenteb = "10px_right";
			$bdayb = "10px_right";
		}
		switch ($sort) {
		case "bday":
		  if ($id <> "") {
			$bdayb = "10px_down_disabled";
		  }
		  else {
		    $bdayb = "10px_down";
		  }
		  $queryb = "SELECT * FROM Leden WHERE position = 5 ORDER BY maand, dag ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 ORDER BY maand, dag ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 ORDER BY maand, dag ASC";
		break;
		case "email":
		  if ($id <> "") {
			$emailb = "10px_down_disabled";
		  }
		  else {
		    $emailb = "10px_down";
		  }
		  $queryb = "SELECT * FROM Leden WHERE position = 5 ORDER BY email ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 ORDER BY email ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 ORDER BY email ASC";
		break;
		case "gemeente":
		  if ($id <> "") {
			$gemeenteb = "10px_down_disabled";
		  }
		  else {
		    $gemeenteb = "10px_down";
		  }
		  $queryb = "SELECT * FROM Leden WHERE position = 5 ORDER BY gemeente ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 ORDER BY gemeente ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 ORDER BY gemeente ASC";
		break;
		default:
		  if ($id <> "") {
			$naamb = "10px_down_disabled";
		  }
		  else {
		    $naamb = "10px_down";
		  }
		  $queryb = "SELECT * FROM Leden WHERE position = 5 ORDER BY naam ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 ORDER BY naam ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 ORDER BY naam ASC";
		}
		if ($id <> "") {
			$classledenboven = 'ledenbovendim';
			
			$classledenonder = 'ledenonderdim';
		}
		else {
			$classledenboven = 'ledenboven';
			$classledenonder = 'ledenonder';
		}
echo "
		<form method='post' action='index.php?action=delete'>
			<table width='100%' align='left' border='0' cellspacing='0' cellpadding='0'>
			  <tr>
				<td>
				  <table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
					<tr>
					  <td class='title'>
						Dagelijks bestuur
					  </td>
					</tr>
				  </table>
				</td>
			  </tr>
			  <tr>
				<td>
				  <table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
					  <tr> 
						<td width='3%' class='$classledenboven'>&nbsp;</td>
						<td width='25%' class='$classledenboven'>
							<a title='$sorteer' href='$print'><img border='0' src='../_images/$naamb.gif'></a>&nbsp;&nbsp;<b>Naam</b>
						</td>
						<td width='26%' class='$classledenboven'>
							<a title='$sorteer' href='$print?sort=email'><img border='0' src='../_images/$emailb.gif'></a>&nbsp;&nbsp;<b>Email</b>
						</td>						
						<td width='14%' class='$classledenboven'>
							<a title='$sorteer' href='$print?sort=gemeente'><img border='0' src='../_images/$gemeenteb.gif'></a>&nbsp;&nbsp;<b>Gemeente</b>
						</td>						
						<td colspan='2' width='32%' class='$classledenboven'>
							<a title='$sorteer' href='$print?sort=bday'><img border='0' src='../_images/$bdayb.gif'></a>&nbsp;&nbsp;<b>Geboortedatum</b>
						</td>
					  </tr>
	";
			$resultb = mysql_query($queryb);
			while ($r = mysql_fetch_array($resultb)) {
			  if ($id == $r[id]) {
				meerinfo($r[id], $i);
			  }
			  else if ($sort == "bday" && ($r[dag] == "" || $r[maand] == "")) { echo""; }
			  else if ($sort == "email" && $r[email] == "") { echo""; }
			  else if ($sort == "gemeente" && $r[gemeente] == "") { echo""; }
			  else {
				$email = $r[email];
				if ($email == "") {
					$email = '&nbsp;';
				}
				$jaar = $r[jaar];
				if ($jaar == "") {
					$jaar = '&nbsp;';
				}
				$imin = $i + 1;
				$email = substr(stripslashes($email), 0, $textchars_admin);
	echo "
					<tr>
					  <td width='3%' class='$classledenonder'><center>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"
					  $i</center></td>
					  <td width='25%' class='$classledenonder'>

	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
					if ($sort <> "") {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?sort=$sort&id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
					else {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
	echo"
					  </td>
					  <td width='26%' class='$classledenonder'>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"
					    <a title='$contacteer' href='mailto:$r[email]'>$email</a>
					  </td>
					  <td width='14%' class='$classledenonder'>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"
						  $r[gemeente]
					  </td>
					  <td width='12%' class='$classledenonder'>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"	  
						  $r[dag] 
	";
								switch ($r[maand]) {
								  case 1:
									echo "jan";
								  break;
								  case 2:
									echo "feb";
								  break;
								  case 3:
									echo "mrt";
								  break;
								  case 4:
									echo "apr";
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
									echo "aug";
								  break;
								  case 9:
									echo "sep";
								  break;
								  case 10:
									echo "okt";
								  break;
								  case 11:
									echo "nov";
								  break;
								  case 12:
									echo "dec";
								  break;
								}
echo "
							$jaar
					  </td>
					  <td align='right' width='20%' class='$classledenonder'>
						<table>
							<tr>
								<td align='center' width='130' height='22' class='edit'>
							";
										if ($r[fotos] == "NONE") {
							echo"
											<a title=\"". $edit_profielfoto ."\"><img border='0' align='top' class='editdel' src='../_images/button/upload_foto_not.gif'></a>
							";
										}
										else {
							echo"
											<a title=\"". $edit_profielfoto ."\" href=\"index.php?action=profielfoto_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/upload_foto.gif'></a>
							";
										}
							echo"
										&nbsp;
							";
								switch ($r[rights]) {
								  case "ALL":
									echo "<a title=\"". $rights_all ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_all.gif'></a>";
								  break;
								  case "SEMI":
									echo "<a title=\"". $rights_semi ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_semi.gif'></a>";
								  break;
								  case "NONE":
									echo "<a title=\"". $rights_none ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_none.gif'></a>";
								  break;
								  default:
									echo "<a title=\"". $rights_notregistered ."\"><img border='0' align='top' class='editdel' src='../_images/button/rights_notregistered.gif'></a>";								  
								  break;
								}
							echo"
										&nbsp;<a title=\"". $edit_article ."\" href=\"index.php?action=profiel_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/edit.gif'></a>&nbsp;
										<input title=\"". $del_article ."\" type='image' src='../_images/button/delete.gif' align='top' class='editdel' name='submit_text'>
										<input type='checkbox' name='v$i' value='$r[id]'>
								</td>
							</tr>
						</table>
					  </td>
					</tr>
";
			  }
			$i++;
			}
echo"
				  </table>
				</td>
			  </tr>
			  <tr>
				<td>
					<br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
				  <table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
					<tr>
					 
				  <table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
					<tr>
					  <td class='title'>
						Leden
					  </td>
					</tr>
				  </table>
				</td>
			  </tr>
			  <tr>
				<td>
				  <table width='100%' border='0' align='center' cellspacing='0' cellpadding='4'>
					  <tr> 
						<td width='3%' class='$classledenboven'>&nbsp;</td>
						<td width='25%' class='$classledenboven'>
							<b>Naam
						</td>
						<td width='26%' class='$classledenboven'>
							<b>Email
						</td>						
						<td width='14%' class='$classledenboven'>
							<b>Gemeente
						</td>						
						<td colspan='2' width='32%' class='$classledenboven'>
							<b>Geboortedatum
						</td>
					  </tr>
	";
			$resultl = mysql_query($queryl);
			while ($r = mysql_fetch_array($resultl)) {
			  if ($id == $r[id]) {
				meerinfo($r[id], $i);
			  }
			  else if ($sort == "bday" && ($r[dag] == "" || $r[maand] == "")) { echo""; }
			  else if ($sort == "email" && $r[email] == "") { echo""; }
			  else if ($sort == "gemeente" && $r[gemeente] == "") { echo""; }
			  else {
				$email = $r[email];
				if ($email == "") {
					$email = '&nbsp;';
				}
				$jaar = $r[jaar];
				if ($jaar == "") {
					$jaar = '&nbsp;';
				}
				$imin = $i + 1;
				$email = substr(stripslashes($email), 0, $textchars_admin);
	echo "
					<tr>
					  <td width='3%' class='$classledenonder'><center>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"
					  $i</center></td>
					  <td width='25%' class='$classledenonder'>

	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
					if ($sort <> "") {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?sort=$sort&id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
					else {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
	echo"
					  </td>
					  <td width='26%' class='$classledenonder'>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"
					    <a title='$contacteer' href='mailto:$r[email]'>$email</a>
					  </td>
					  <td width='14%' class='$classledenonder'>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"
						  $r[gemeente]
					  </td>
					  <td width='12%' class='$classledenonder'>
	";
					if ($r[visible] <> "YES" && $id == "") {
							echo"<span class='memo_visible'>";
					}
	echo"	  
						  $r[dag] 
	";
								switch ($r[maand]) {
								  case 1:
									echo "jan";
								  break;
								  case 2:
									echo "feb";
								  break;
								  case 3:
									echo "mrt";
								  break;
								  case 4:
									echo "apr";
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
									echo "aug";
								  break;
								  case 9:
									echo "sep";
								  break;
								  case 10:
									echo "okt";
								  break;
								  case 11:
									echo "nov";
								  break;
								  case 12:
									echo "dec";
								  break;
								}
echo "
							$jaar
					  </td>
					  <td width='20%' align='right' class='$classledenonder'>
						<table>
							<tr>
								<td align='center' width='130' height='22' class='edit'>
							";
										if ($r[fotos] == "NONE") {
							echo"
											<a title=\"". $edit_profielfoto ."\"><img border='0' align='top' class='editdel' src='../_images/button/upload_foto_not.gif'></a>
							";
										}
										else {
							echo"
											<a title=\"". $edit_profielfoto ."\" href=\"index.php?action=profielfoto_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/upload_foto.gif'></a>
							";
										}
							echo"
										&nbsp;
							";
								switch ($r[rights]) {
								  case "ALL":
									echo "<a title=\"". $rights_all ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_all.gif'></a>";
								  break;
								  case "SEMI":
									echo "<a title=\"". $rights_semi ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_semi.gif'></a>";
								  break;
								  case "NONE":
									echo "<a title=\"". $rights_none ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_none.gif'></a>";
								  break;
								  default:
									echo "<a title=\"". $rights_notregistered ."\"><img border='0' align='top' class='editdel' src='../_images/button/rights_notregistered.gif'></a>";								  
								  break;
								}
							echo"
										&nbsp;<a title=\"". $edit_article ."\" href=\"index.php?action=profiel_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/edit.gif'></a>&nbsp;
										<input title=\"". $del_article ."\" type='image' src='../_images/button/delete.gif' align='top' class='editdel' name='submit_text'>
										<input type='checkbox' name='v$i' value='$r[id]'>
								</td>
							</tr>
						</table>
					  </td>
					</tr>
";
			  }
			$i++;
			}
echo"
				  </table>
				</td>
			  </tr>
			</table>
";
	}
}

function meerinfo ($id, $i) {
	include ("config.php");
	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$instellingen_mediafolder = '../'.$instellingen[mediafolder].'/';
	$instellingen_foto = $instellingen[fotoNa];
	
	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query = "SELECT * FROM Leden WHERE id = $id";
	$result = mysql_query($query);
	$r = mysql_fetch_array($result);
	if ($r[fotos] == "NONE" || $r[fotos] == "na" || $r[fotos] == "") {
		$fotos = "$instellingen_foto";
	}
	else {
		$fotos = "$r[fotos]";
	}
	$jaar = $r[jaar];
	if ($jaar == "") {
		$jaar = '&nbsp;';
	}
echo "
					  <tr> 
					  	<td width='3%' class='ledenborderboven'>&nbsp;</td>
						<td width='25%' class='ledenborderboven'>
							<b><a name='$r[id]'>Foto</a></b>
						</td>						
						<td colspan='2' width='40%' class='ledenborderboven'>
							<b>Info</b>
						</td>						
						<td colspan='2' width='32%' class='ledenborderboven'>
							<b>Geboortedatum</b>
						</td>
					  </tr>
					  <tr>
					    <td width='3%' class='ledenborderonder'><center>$i</center></td>
					    <td width='25%' class='ledenborderonder'>
							<img width='150' height='150' src='$instellingen_mediafolder$fotos' class='images'>
						</td>
					    <td colspan='2' width='40%' class='ledenborderonder' valign='top'>
							<u><i>Naam:</i></u><br />
							$r[naam] $r[voornaam]<br /><br />
							<u><i>Adres:</i></u><br />
							$r[gemeente] ($r[postcode])<br /><br />
					";
						if ($r[telefoon] <> "") {
							echo"<u><i>Telefoon/GSM:</i></u><br />
							$r[telefoon]<br /><br />";
						}
					echo"
							<u><i>Email:</i></u><br />
							<a title='$contacteer' href='mailto:$r[email]'>$r[email]</a><br />
						</td>
						<td width='12%' class='ledenborderonder'>
				";
						if ($r[maand] == "") {
							echo "$jaar";
						}
						else if ($r[dag] == "") {
							echo "$jaar";
						}
						else {
				echo "
						$r[dag] 
				";
							switch ($r[maand]) {
							  case 1:
								echo "jan";
							  break;
							  case 2:
								echo "feb";
							  break;
							  case 3:
								echo "mrt";
							  break;
							  case 4:
								echo "apr";
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
								echo "aug";
							  break;
							  case 9:
								echo "sep";
							  break;
							  case 10:
								echo "okt";
							  break;
							  case 11:
								echo "nov";
							  break;
							  case 12:
								echo "dec";
							  break;
							}
				echo "
						$jaar
				";
						}
				echo "
					  </td>
					  <td width='20%' align='right' class='ledenborderonder'>
						<table>
							<tr>
								<td align='center' width='130' height='22' class='edit'>
							";
										if ($r[fotos] == "NONE") {
							echo"
											<a title=\"". $edit_profielfoto ."\"><img border='0' align='top' class='editdel' src='../_images/button/upload_foto_not.gif'></a>
							";
										}
										else {
							echo"
											<a title=\"". $edit_profielfoto ."\" href=\"index.php?action=profielfoto_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/upload_foto.gif'></a>
							";
										}
							echo"
										&nbsp;
							";
								switch ($r[rights]) {
								  case "ALL":
									echo "<a title=\"". $rights_all ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_all.gif'></a>";
								  break;
								  case "SEMI":
									echo "<a title=\"". $rights_semi ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_semi.gif'></a>";
								  break;
								  case "NONE":
									echo "<a title=\"". $rights_none ."\" href=\"index.php?action=rechten_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/rights_none.gif'></a>";
								  break;
								  default:
									echo "<a title=\"". $rights_notregistered ."\"><img border='0' align='top' class='editdel' src='../_images/button/rights_notregistered.gif'></a>";								  
								  break;
								}
							echo"
										&nbsp;<a title=\"". $edit_article ."\" href=\"index.php?action=profiel_wijzigen&id=$r[id]\"><img border='0' align='top' class='editdel' src='../_images/button/edit.gif'></a>&nbsp;
										<input title=\"". $del_article ."\" type='image' src='../_images/button/delete.gif' align='top' class='editdel' name='submit_text'>
										<input type='checkbox' name='v$i' value='$r[id]'>
								</td>
							</tr>
						</table>
					  </td>
				    </tr>
				";
}
?>