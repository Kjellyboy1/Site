<?php

function ReplaceSmilies($txt)
  { // starten v/d fucntie ReplaceSmilies, het vervangen van text door smilies.
  
  $cDir = '../_images/smilies';
    // map waarin de smilies staan...
  $cCodes = array(':)',':p',':P',';)',':d',':D',':(',':lol:',':no:',':cry:',':wtf:',':confused:',':bow:',':eek:',':evil:','.sleep.',':mad:',':rofl:',':music:',':ironic:',':crazy:',':love:',':cool:',':help:',':unsure:',':thumb:','.party.',':clap:',':tong:','.scream.','.doh.',':s',':S',':oink:',':@',':x',':X');
    // array met de smilie codes.
  $cCodes2 = array(':-)',':-p',':-P',';-)',':-d',':-D',':-(',':lol:',':no:',':cry:',':wtf:',':confused:',':bow:',':eek:',':evil:','.sleep.',':mad:',':rofl:',':music:',':ironic:',':crazy:',':love:',':cool:',':help:',':unsure:',':thumb:','.party.',':clap:',':tong:','.scream.','.doh.',':-s',':-S',':oink:',':-@',':-x',':-X');
    // 2e array met codes, dezelfde, maar in andere vorm.
  $cSmilies = array('smile','tong','tong','wink','biglaugh','biglaugh','frown','lol','nono','cry','wtf','confused','bow','eek','evil','sleep','mad','rofl','music','ironic','crazy','love','cool','help','unsure','thumb','party','clap','eviltongue','scream','doh','damn','damn','oink','shy','shutup','shutup');
    // De bestandsnamen ervan
    
  // let erop dat je evenveel smilies hebt ingevoerd bij de bovenstaande 3 array's!
  // Als dat niet het geval is wordt er nu het een en ander aan geknipt...
  
  if(count($cCodes) != count($cCodes2))
    {
    // De aantal smilies in $cCodes en $cCodes2 zijn niet evenveel.
    if(count($cCodes) > count($cCodes2))
      {
      $cAantal = count($cCodes2);
      }
    else
      {
      $cAantal = count($cCodes);
      }
    }
  else
    {
    $cAantal = count($cCodes);
    }
    
  // nu dan het echte vervangen met een for-loop.
  
  for($i=0;$i<$cAantal;$i++)
    {
    $txt = str_replace($cCodes[$i],'<img src="'.$cDir.'/'.$cSmilies[$i].'.gif" border="0" alt="'.$cSmilies[$i].'">',$txt);
      // vervangen van de smilies uit de 1e array.
    $txt = str_replace($cCodes2[$i],'<img src="'.$cDir.'/'.$cSmilies[$i].'.gif" border="0" alt="'.$cSmilies[$i].'">',$txt);
      // vervangen van de smilies uit de 2e array.
    }
  return $txt;
}

 
function map() {
	include ("config.php");
	$id = $_GET['id'];
	$nieuw = $_GET['nieuw'];
	$delete = $_GET['delete'];
	$action = $_GET['action'];
	$task = $_GET['task'];
	$foto = $_GET['foto'];
	
	if ($foto == "nok") {
		echo "
			<div class='alert alert-danger' role='alert'>
						<b>". $foto_nok_1 ."</b><br><br>". $foto_nok_2 ."
					</div>
		";
	}
	if (isset($_POST['UploadFoto'])) {
		$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$query_instellingen = "SELECT * FROM Instellingen";
		$result_instellingen = mysql_query($query_instellingen);
		$instellingen = mysql_fetch_array($result_instellingen);
		$instellingen_mediafolder = '../'.$instellingen[mediafolder];
		$instellingen_fotofolder = $instellingen[homefolder];
		
			if (((($_FILES["file"]["type"] == "image/JPG") || ($_FILES["file"]["type"] == "image/jpg") || $_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 307200)) {
				move_uploaded_file($_FILES["file"]["tmp_name"],$instellingen_mediafolder . $instellingen_fotofolder . $_FILES["file"]["name"]);
				$target_width = 150;
				
				$destination_path = $instellingen_mediafolder . $instellingen_fotofolder;

				if (ob_get_level() == 0) ob_start();
					if ($handle = opendir($destination_path)) {
					  while (false !== ($file = readdir($handle))) {
						  $target_path = $destination_path . basename( $_FILES["file"]["name"]);

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

							$new_height = ($target_width / $imgratio);
							$new_width = $target_width;
							$new_image = imagecreatetruecolor($new_width, $new_height);
							ImageCopyResampled($new_image, $tmp_image,0,0,0,0, $new_width, $new_height, $width, $height);
							//Grab new image
							imagejpeg($new_image, $target_path);
							$image_buffer = ob_get_contents();
							ImageDestroy($new_image);
							ImageDestroy($tmp_image);
							ob_flush();
							flush();
						}
					  }
					  closedir($handle);
					  ob_end_flush();
					}		
			}
			else {
				echo"<script>self.location='?action=upload&foto=nok';</script>";
			}
			echo"<script>self.location='index.php';</script>";
	}
	if ($task == "up") {
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$queryC = "SELECT * FROM Homepg ORDER BY plaats ASC"; 
		$resultC = mysql_query($queryC);
		$c = 10;
		while ($rC = mysql_fetch_array($resultC)) {
			mysql_query("UPDATE Homepg SET plaats='$c' WHERE id='$rC[id]'");
			$c++;
		}
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$queryU = "SELECT * FROM Homepg WHERE id='$id'"; 
		$resultU = mysql_query($queryU);
		$rU = mysql_fetch_array($resultU);
		$plaats = $rU[plaats];
		$plaatsU = $plaats+1;
		mysql_query("UPDATE Homepg SET plaats='$plaats' WHERE plaats='$plaatsU'");
		mysql_query("UPDATE Homeopg SET plaats='$plaatsU' WHERE id='$id'");
		mysql_close($db);
		echo"<script>self.location='index.php';</script>";
  	}
	else if ($task == "down") {
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$queryC = "SELECT * FROM Homepg ORDER BY plaats ASC"; 
		$resultC = mysql_query($queryC);
		$c = 10;
		while ($rC = mysql_fetch_array($resultC)) {
			mysql_query("UPDATE Homepg SET plaats='$c' WHERE id='$rC[id]'");
			$c++;
		}
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$queryD = "SELECT * FROM Homepg WHERE id='$id'"; 
		$resultD = mysql_query($queryD);
		$rD = mysql_fetch_array($resultD);
		$plaats = $rD[plaats];
		$plaatsD = $plaats-1;
		mysql_query("UPDATE Homepg SET plaats='$plaats' WHERE plaats='$plaatsD'");
		mysql_query("UPDATE Homepg SET plaats='$plaatsD' WHERE id='$id'");
		mysql_close($db);
		echo"<script>self.location='index.php';</script>";
  	}
	else if ($task == "nieuw") {
		if ($_POST['submit_text']) {
			$folder = $_POST['folder'];
			$best = $_POST['best'];
			$textfoto = $_POST['textfoto'];
			$textfoto = nl2br($textfoto);
			$text = $_POST['text'];
			$text = nl2br($text);
			$visible = $_POST['visible'];
			$plaats = "";
			$foto = "$folder/$best";

			if ($text == "")  {
				echo"<script>self.location='?action=nieuw&folder=$folder&best=$best&nieuw=nok';</script>";
			}
			else { 
				$db = mysql_connect($dbhost,$dbuname,$dbpass); 
				mysql_select_db($dbname) or die($dberror);
				mysql_query("INSERT INTO Homepg(foto,textfoto,text,plaats,visible) VALUES('$foto','$textfoto','$text','$plaats','$visible')");
				mysql_close($db);
				echo"<script>self.location='index.php';</script>";
			}
		}	
	}
	else if ($task == "edit") {
		if ($_POST['submit_text']) {
		    $id = $_REQUEST['id'];
			$foto = $_POST['foto'];
			$textfoto = $_POST['textfoto'];
			$textfoto = nl2br($textfoto);
			$text = $_POST['text'];
			$text = nl2br($text);
			$visible = $_POST['visible'];
			$plaats = "";

			if ($text == "")  {
				echo"<script>self.location='?action=edit&id=$id&edit=nok';</script>";
			}
			else {
				$db = mysql_connect($dbhost,$dbuname,$dbpass); 
				mysql_select_db($dbname) or die($dberror);
				mysql_query("UPDATE Homepg SET foto='$foto' WHERE id='$id'");
				mysql_query("UPDATE Homepg SET textfoto='$textfoto' WHERE id='$id'");
				mysql_query("UPDATE Homepg SET text='$text' WHERE id='$id'");
				mysql_query("UPDATE Homepg SET visible='$visible' WHERE id='$id'");
				mysql_close($db);
				echo"<script>self.location='index.php';</script>";
			}
		}	
	}
	else if ($task == "delete") {
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$queryH = "SELECT * FROM Homepg ORDER BY plaats ASC"; 
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
				mysql_query("DELETE FROM Homepg WHERE id='$id'");
				$i3++;
			}
			$i2++;
		}
		if ($i3 == 0) {
			echo"<script>self.location='?delete=nok';</script>";
		}
		else {
			echo"<script>self.location='index.php';</script>";
		}
	}
	if ($nieuw == "nok") {
		echo "
			<div class='alert alert-danger' role='alert'>
						<b>". $nieuw_nok_1 ."</b><br><br>". $nieuw_nok_2 ."
			</div>
		";
	}
	if ($delete == "nok") {
		echo "
			<div class='alert alert-danger' role='alert'>
					
						<b>". $delete_nok_1 ."</b><br><br>". $delete_nok_2 ."
					</div>
		";
	}
	if ($action == 'nieuw') { nieuw();}
	else if ($action == 'edit') { edit();}
	else if ($action == 'upload') {
	?>
	<form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
	  <table width='100%' align='left' border='0' cellspacing='0' cellpadding='5'>
		<tr>
		  <td class='title' colspan='2'>
			Upload foto
		  </td>
		</tr>
		<tr>
		  <td colspan='2' valign='top'>
		    <table width='100%' align='left' border='0' cellspacing='0' cellpadding='1'>
			  <tr>
				<td>
					<i>Upload foto:</i> <input type="file" name="file" id="file" class='verzenden'/>	<span class='important'>De foto moet <b><300kb</b> en een <b>'jpg, gif, png, bmp, jpeg, JPG'</b> bestand zijn!</span><br /><br /><br /><br />
				</td>
			  </tr>
			  <tr>
				<td>
					<input tabindex='3' name="UploadFoto" type="submit" class='verzenden' value="<? echo $submit_new_article; ?>">
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
		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
		mysql_select_db($dbname) or die($dberror);
		$queryR = "SELECT * FROM Instellingen"; 
		$resultR = mysql_query($queryR);
		$rR = mysql_fetch_array($resultR);
		$path = "../$rR[mediafolder]";
	
  		$db = mysql_connect($dbhost,$dbuname,$dbpass); 
  		mysql_select_db($dbname) or die($dberror);
		$query = "SELECT * FROM Homepg ORDER BY plaats DESC"; 
		$shorten = $textchars;
		$result = mysql_query($query);
		$i=1;
		echo"
		<form name='fr' method='post' action='index.php?task=delete'>
		";
		while ($r = mysql_fetch_array($result)) {
			$vtext = ReplaceSmilies($r[text]);
			$plaatsMax=0;
			$db = mysql_connect($dbhost,$dbuname,$dbpass); 
			mysql_select_db($dbname) or die($dberror);
			$queryMax = "SELECT * FROM Homepg ORDER BY plaats DESC"; 
			$resultMax = mysql_query($queryMax);
			while ($rMax = mysql_fetch_array($resultMax)) {
				$plaatsMax++;
			}

			if ($i % 2 == 0) {
				echo "
				<table align='center' cellspacing='0' cellpadding='5' border='0' width='100%'>
					<tr>
						<td width='25' align='center'>
							<table align='center' cellspacing='0' cellpadding='0' border='0' width='100%'>
								<tr>
									<td width='25' align='center' class='edit'>
				";
				if ($i > 1) {
					echo"
						<a title=\"". $up ."\" href=\"index.php?task=up&id=$r[id]\"><img border='0' src='../_images/10px_up.gif'></a><br><br>
					";
				}
				else {
					echo"
						<img border='0' src='../_images/10px_up_disabled.gif'><br><br>
					";
				}
					echo"<input type='checkbox' name='v$i' value='$r[id]'><br>
						<a title=\"Tabel ". $i ." aanpassen\" href=\"index.php?action=edit&id=$r[id]&pl=$i\"><img border='0' src='../_images/button/edit.gif'></a>";
				if ($i < $plaatsMax) {
					echo"<br><br><a title=\"". $down ."\" href=\"index.php?task=down&id=$r[id]\"><img border='0' src='../_images/10px_down.gif'></a>";
				}
				else {
					echo"<br><br><img border='0' src='../_images/10px_down_disabled.gif'>
					";
				}
					echo"
									</td>
								</tr>
							</table>
					  </td>
					  <td width='5' align='center'>
					  </td>
					  <td BORDER='0' align='left'>
						";
						if ($r[visible] <> "YES") {
								echo"<span class='memo_visible'>";
						}
						echo"$vtext</span>
					  </td>
					  <td width='165'>
						<table align='right' cellspacing='0' cellpadding='0' border='0' width='150'>
						  <tr>
							<td align='right'>
							  <img width='150' class='images_home' src='$path$r[foto]'><br />
							</td>
						  </tr>
				";
						if ($r[textfoto] <> "") {
				echo"
						  <tr>
							<td align='center' class='images_home'>
							  $r[textfoto]
							</td>
						  </tr>
				";
						}
				echo"	
						</table>
					  </td>
					</tr>
				</table><br />
				";
				}
				else {
					echo "
				<table align='center' cellspacing='0' cellpadding='5' border='0' width='100%'>
					<tr>
					  <td width='25' align='center'>
							<table align='center' cellspacing='0' cellpadding='0' border='0' width='100%'>
								<tr>
									<td width='25' align='center' class='edit'>
					";
					if ($i > 1) {
						echo"
							<a title=\"". $up ."\" href=\"index.php?task=up&id=$r[id]\"><img border='0' src='../_images/10px_up.gif'></a><br><br>";
					}
					else {
						echo"
							<img border='0' src='../_images/10px_up_disabled.gif'><br><br>";
					}
						echo"<input type='checkbox' name='v$i' value='$r[id]'><br>
							<a title=\"Tabel ". $i ." aanpassen\" href=\"index.php?action=edit&id=$r[id]\"><img border='0' src='../_images/button/edit.gif'></a>";
					if ($i < $plaatsMax) {
						echo"<br><br><a title=\"". $down ."\" href=\"index.php?task=down&id=$r[id]\"><img border='0' src='../_images/10px_down.gif'></a>";
					}
					else {
						echo"<br><br><img border='0' src='../_images/10px_down_disabled.gif'>
						";
					}
						echo"
									</td>
								</tr>
							</table>
						  </td>
						  <td width='5' align='center'>
						  </td>
						  <td width='165'>
							<table align='right' cellspacing='0' cellpadding='0' border='0' width='150'>
							  <tr>
								<td align='right'>
								  <img width='150' class='images_home' src='$path$r[foto]'><br />
								</td>
							  </tr>
					";
							if ($r[textfoto] <> "") {
					echo"
							  <tr>
								<td align='center' class='images_home'>
								  $r[textfoto]
								</td>
							  </tr>
					";
							}
					echo"	
							</table>
						  </td>
						  <td BORDER='0' align='left'>
						";
						if ($r[visible] <> "YES") {
								echo"<span class='memo_visible'>";
						}
						echo"
							$vtext</span>
						  </td>
						</tr>
					</table><br />
					";
				}
			$i++;
		}
		echo"
				<table align='center' cellspacing='0' cellpadding='5' border='0' width='100%'>
					<tr>
						<td align='center' width='21' height='21'>
							<table align='center' cellspacing='0' cellpadding='0' border='0' width='100%'>
								<tr>
									<td width='21' height='21' align='center' class='edit'>
										<input type='image' src='../_images/button/delete.gif' title=\"". $delete ."\" name='Wissen' class='login'>
									</td>
								</tr>
							</table>
						</td>
						<td>
						</td>
					</tr>
				</table>
			</form>
		";
	}
}

function nieuw() {
	include ("config.php");
	$best = $_GET['best'];
	
	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$folder = '../'.$instellingen[mediafolder].$instellingen[homefolder];
	$folder2 = $instellingen[mediafolder].$instellingen[homefolder];
	
	$aantal=0;
	$bestand = Array();
	$dhc = @opendir($folder);
	while (($filec = readdir($dhc))!== false) {
		$file = explode(".",$filec);
		$fileName = $file[0];
		$fileEnd = $file[1];
		if($filec != "." && $filec != ".." && $fileName != "index" && $fileName != "thumbs" && $fileName != "Thumbs") {
			$bestand[$aantal] = $filec;
			$aantal++;
		}
	}
	sort($bestand);
	
	?>
		<form name='fr' method='post' action='index.php?task=nieuw'> 
			<table align='center' cellspacing='0' cellpadding='5' border='0' width='100%'>
				<tr>
					<td colspan='2' class='title'>
						Selecteer een afbeelding voor de nieuwe tabel
					</td>
				</tr>
				<tr>
					<td colspan='2' valign='top'>
						<i>Afbeeldingen MOETEN toegevoegd worden via de knop "upload foto" in de navigatiebalk !<br>
						Afbeeldingen kunnen verwijderd worden via FTP in de map "<? echo $folder2?>" !</i><br><br>
<?
							$i=0;
							$o=0;
							while($i < $aantal) {
									$file = explode(".",$bestand[$i]);
									$fileName = $file[0];
									$fileEnd = $file[1];
									if($fileEnd == "jpg" || $fileEnd == "JPG") {
						echo"
										<a href='index.php?action=nieuw&best=$bestand[$i]#afbeelding'><img class='imagesSelect' BORDER='0' src='$folder$bestand[$i]'></a>&nbsp;&nbsp;
						";
										if ($o == "4") {
											$o = 0;
						echo"
											<br><br>
						";
										}
									}
								$i++;
								$o++;
							}
?>
					</td>
				</tr>
				<tr>
					<td>
						<br>
					</td>
				</tr>
				<tr>
					<td colspan='2' class='title'><a name='afbeelding'></a>Informatie bij de nieuwe tabel
					</td>
				</tr>
					<tr>
					  <td width='165' valign='top'>
						<table align='left' cellspacing='0' cellpadding='0' border='0' width='150'>
						  <tr>
							<td BORDER='1' align='center' class='images'>
								<? if ($best <> "") { ?>
									<img width='150' border='0' src='<? echo $folder.'/'.$best ?>'><br>
								<? } else { ?>
									<img width='150' border='0' src='../_images/vraagteken.jpg'><br>
								<? } ?>
								<a href="javascript:void(0);" onClick='geef("b_onder");'><img border="0" src="../_images/button/b.png" alt="Vet" title="Vet"></a>
								<a href="javascript:void(0);" onClick='geef("i_onder");'><img src="../_images/button/i.png" border="0" alt="Cursief" title="Cursief"></a>
								<a href="javascript:void(0);" onClick='geef("u_onder");'><img src="../_images/button/u.png" border="0" alt="Onderlijnen" title="Onderlijnen"></a>
								<a href="javascript:void(0);" onClick='geef("url_onder");'><img src="../_images/button/url.png" border="0" alt="Weblink" title="Weblink"></a>
								<a href="javascript:void(0);" onClick='geef("email_onder");'><img src="../_images/button/email.png" border="0" alt="Email" title="Email"></a><br>
								<? if ($best <> "") { ?>
									<textarea class='input_home_onder' name='textfoto' tabindex='1'></textarea>
								<? } else { ?>
									<textarea class='input_home_onder_disabled' name='textfoto' tabindex='1' disabled></textarea>
								<? } ?>
							</td>
						  </tr>
						</table>
					  </td>
					  <td BORDER='0' align='left' valign='top'>
					  	&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick='geef("b");'><img border="0" src="../_images/button/b.png" alt="Vet" title="Vet"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("i");'><img src="../_images/button/i.png" border="0" alt="Cursief" title="Cursief"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("u");'><img src="../_images/button/u.png" border="0" alt="Onderlijnen" title="Onderlijnen"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("url");'><img src="../_images/button/url.png" border="0" alt="Weblink" title="Weblink"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("email");'><img src="../_images/button/email.png" border="0" alt="Email" title="Email"></a><br>
						<? if ($best <> "") { ?>
							<textarea class='input_home' name='text' tabindex='2'></textarea><br>
						<? } else { ?>
							<textarea class='input_home_disabled' name='text' tabindex='2' disabled></textarea><br>
						<? } ?>
						<table border='0' align='left' cellspacing='0' cellpadding='0'>
						  <tr>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:)&nbsp;';"><img border="0" src="../_images/smilies/smile.gif" alt="Smile" title="Smile"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:p&nbsp;';"><img border="0" src="../_images/smilies/tong.gif" alt="Tong" title="Tong"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;;)&nbsp;';"><img border="0" src="../_images/smilies/wink.gif" alt="Knipoog" title="Knipoog"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:D&nbsp;';"><img border="0" src="../_images/smilies/biglaugh.gif" alt="HAHA" title="HAHA"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:(&nbsp;';"><img border="0" src="../_images/smilies/frown.gif" alt="Teleurgesteld" title="Teleurgesteld"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:lol:&nbsp;';"><img border="0" src="../_images/smilies/lol.gif" alt="Lol" title="LoL"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:no:&nbsp;';"><img border="0" src="../_images/smilies/nono.gif" alt="Nope" title="Nope"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:cry:&nbsp;';"><img border="0" src="../_images/smilies/cry.gif" alt="Blèt" title="Blèt"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:wtf:&nbsp;';"><img border="0" src="../_images/smilies/wtf.gif" alt="WTF" title="WTF"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:confused:&nbsp;';"><img border="0" src="../_images/smilies/confused.gif" alt="Verward" title="Verward"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:bow:&nbsp;';"><img border="0" src="../_images/smilies/bow.gif" alt="Buig" title="Buig"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:eek:&nbsp;';"><img border="0" src="../_images/smilies/eek.gif" alt="Eek" title="EEK"></a></td>
						  </tr>
						  <tr>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:music:&nbsp;';"><img border="0" src="../_images/smilies/music.gif" alt="Muziek" title="Muziek"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:ironic:&nbsp;';"><img border="0" src="../_images/smilies/ironic.gif" alt="Ironisch" title="Ironisch"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:crazy:&nbsp;';"><img border="0" src="../_images/smilies/crazy.gif" alt="Gek" title="Gek"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:oink:&nbsp;';"><img border="0" src="../_images/smilies/oink.gif" alt="Oink" title="Oink"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:cool:&nbsp;';"><img border="0" src="../_images/smilies/cool.gif" alt="Cool" title="Cool"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:mad:&nbsp;';"><img border="0" src="../_images/smilies/mad.gif" alt="Kwaad" title="Kwaad"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:unsure:&nbsp;';"><img border="0" src="../_images/smilies/unsure.gif" alt="Onzeker" title="Onzeker"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:thumb:&nbsp;';"><img border="0" src="../_images/smilies/thumb.gif" alt="Duim" title="Duim"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;.party.&nbsp;';"><img border="0" src="../_images/smilies/party.gif" alt="Party" title="Party"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:clap:&nbsp;';"><img border="0" src="../_images/smilies/clap.gif" alt="Clap" title="Clap"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;.doh.&nbsp;';"><img border="0" src="../_images/smilies/doh.gif" alt="Doh" title="Doh"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:x&nbsp;';"><img border="0" src="../_images/smilies/shutup.gif" alt="Zwijg" title="Zwijg"></a></td>
						  </tr>
						</table>
					  </td>
					</tr>
					<tr>
					  <td colspan='2'>
						<fieldset class='fieldset'><legend class='legend'><i>Publicatie-opties</i></legend><br>
							<input type="hidden" name="folder" value="<? echo $folder ?>">
							<input type="hidden" name="best" value="<? echo $best ?>">
							<? if ($best <> "") { ?>
								<input type="checkbox" value="YES" name="visible" checked> <? echo $enable_visible; ?><br /><br />
							<? } else { ?>
								<input type="checkbox" value="YES" name="visible" checked disabled> <? echo $enable_visible; ?><br /><br />
							<? } ?>
						</fieldset><br><br>
							<? if ($best <> "") { ?>
								<input type="submit" name="submit_text" class='verzenden' value="<? echo $submit_new_article ?>">
							<? } else { ?>
								
							<? } ?>
					  </td>
					</tr>
				</table>
			</form>
<?
}

function edit() {
	include ("config.php");
	$foto = $_GET['foto'];
	$best = $_GET['best'];
	
	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$folder = '../'.$instellingen[mediafolder].$instellingen[homefolder];
	$folder2 = $instellingen[mediafolder].$instellingen[homefolder];
	
	$aantal=0;
	$bestand = Array();
	$dhc = @opendir($folder);
	while (($filec = readdir($dhc))!== false) {
		$file = explode(".",$filec);
		$fileName = $file[0];
		$fileEnd = $file[1];
		if($filec != "." && $filec != ".." && $fileName != "index" && $fileName != "thumbs" && $fileName != "Thumbs") {
			$bestand[$aantal] = $filec;
			$aantal++;
		}
	}
	sort($bestand);
	
	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$id = $_REQUEST['id'];
	$query = mysql_query("SELECT * FROM Homepg WHERE id='$id'");
	$r = mysql_fetch_array($query);
	
?>
		<form name='fr' method='post' action='index.php?task=edit'> 
			<table align='center' cellspacing='0' cellpadding='5' border='0' width='100%'>
				<tr>
					<td colspan='2' class='title'>
						Selecteer een andere afbeelding indien nodig
					</td>
				</tr>
				<tr>
					<td colspan='2' valign='top'>
						<br><a href='?action=edit&id=<?=$id?>&foto=ja'><img border='0' src='../_images/10px_right.gif'>&nbsp;
						<? if ($foto <> "neen") { ?> <b> <? } ?>Ja</b></a><br><br>
						<a href='?action=edit&id=<?=$id?>&foto=neen'><img border='0' src='../_images/10px_right.gif'>&nbsp;
						<? if ($foto <> "ja") { ?> <b> <? } ?>Neen</b></a><br><br><br>
<?
						if ($foto == "ja") {
?>
						<i>Afbeeldingen MOETEN toegevoegd worden via de knop "upload foto" in de navigatiebalk !<br>
						Afbeeldingen kunnen verwijderd worden via FTP in de map "<? echo $folder2?>" !</i><br><br>
<?
							$i=0;
							$o=0;
							while($i < $aantal) {
									$file = explode(".",$bestand[$i]);
									$fileName = $file[0];
									$fileEnd = $file[1];
									if($fileEnd == "jpg" || $fileEnd == "JPG") {
						echo"
										<a href='index.php?action=nieuw&best=$bestand[$i]#afbeelding'><img class='imagesSelect' BORDER='0' src='$folder$bestand[$i]'></a>&nbsp;&nbsp;
						";
										if ($o == "4") {
											$o = 0;
						echo"
											<br><br>
						";
										}
									}
								$i++;
								$o++;
							}
						}
?>
					</td>
				</tr>
				<tr>
					<td>
						<br>
					</td>
				</tr>
				<tr>
					<td colspan='2' class='title'>Informatie bij de tabel
					</td>
				</tr>
					<tr>
					  <td width='165' valign='top'>
						<table align='left' cellspacing='0' cellpadding='0' border='0' width='150'>
						  <tr>
							<td BORDER='1' align='center' class='images'>
								<? if ($foto == "ja" && $best <> "") { ?>
									<img width='150' border='0' src='<? echo $folder.'/'.$best ?>'><br>
								<? } else if ($foto == "neen" || $foto == "") { ?>
									<img width='150' border='0' src='<? echo $folder.$r[foto] ?>'><br>
								<? } else { ?>
									<img width='150' border='0' src='../_images/vraagteken.jpg'><br>
								<? } ?></textarea>
								<a href="javascript:void(0);" onClick='geef("b_onder");'><img border="0" src="../_images/button/b.png" alt="Vet" title="Vet"></a>
								<a href="javascript:void(0);" onClick='geef("i_onder");'><img src="../_images/button/i.png" border="0" alt="Cursief" title="Cursief"></a>
								<a href="javascript:void(0);" onClick='geef("u_onder");'><img src="../_images/button/u.png" border="0" alt="Onderlijnen" title="Onderlijnen"></a>
								<a href="javascript:void(0);" onClick='geef("url_onder");'><img src="../_images/button/url.png" border="0" alt="Weblink" title="Weblink"></a>
								<a href="javascript:void(0);" onClick='geef("email_onder");'><img src="../_images/button/email.png" border="0" alt="Email" title="Email"></a><br>
								<? $textfoto = str_replace("<br />", "", $r[textfoto]);
								if ($foto == "neen" || $best <> "") { ?>
									<textarea class='input_home_onder' name='textfoto' tabindex='1'><?php echo stripslashes($textfoto);?></textarea><br>
								<? } else { ?>
									<textarea class='input_home_onder_disabled' name='textfoto' tabindex='1' disabled><?php echo stripslashes($textfoto);?></textarea><br>
								<? } ?></textarea>
							</td>
						  </tr>
						</table>
					  </td>
					  <td BORDER='0' align='left' valign='top'>
					  	&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick='geef("b");'><img border="0" src="../_images/button/b.png" alt="Vet" title="Vet"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("i");'><img src="../_images/button/i.png" border="0" alt="Cursief" title="Cursief"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("u");'><img src="../_images/button/u.png" border="0" alt="Onderlijnen" title="Onderlijnen"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("url");'><img src="../_images/button/url.png" border="0" alt="Weblink" title="Weblink"></a>
						&nbsp;<a href="javascript:void(0);" onClick='geef("email");'><img src="../_images/button/email.png" border="0" alt="Email" title="Email"></a><br>
						<? $text = str_replace("<br />", "", $r[text]);
						if ($foto == "neen" || $best <> "") { ?>
							<textarea class='input_home' name='text' tabindex='2'><?php echo stripslashes($text); ?></textarea><br>
						<? } else { ?>
							<textarea class='input_home_disabled' name='text' tabindex='2' disabled><?php echo stripslashes($text); ?></textarea><br>
						<? } ?>
						<table border='0' align='left' cellspacing='0' cellpadding='0'>
						  <tr>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:)&nbsp;';"><img border="0" src="../_images/smilies/smile.gif" alt="Smile" title="Smile"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:p&nbsp;';"><img border="0" src="../_images/smilies/tong.gif" alt="Tong" title="Tong"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;;)&nbsp;';"><img border="0" src="../_images/smilies/wink.gif" alt="Knipoog" title="Knipoog"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:D&nbsp;';"><img border="0" src="../_images/smilies/biglaugh.gif" alt="HAHA" title="HAHA"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:(&nbsp;';"><img border="0" src="../_images/smilies/frown.gif" alt="Teleurgesteld" title="Teleurgesteld"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:lol:&nbsp;';"><img border="0" src="../_images/smilies/lol.gif" alt="Lol" title="LoL"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:no:&nbsp;';"><img border="0" src="../_images/smilies/nono.gif" alt="Nope" title="Nope"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:cry:&nbsp;';"><img border="0" src="../_images/smilies/cry.gif" alt="Blèt" title="Blèt"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:wtf:&nbsp;';"><img border="0" src="../_images/smilies/wtf.gif" alt="WTF" title="WTF"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:confused:&nbsp;';"><img border="0" src="../_images/smilies/confused.gif" alt="Verward" title="Verward"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:bow:&nbsp;';"><img border="0" src="../_images/smilies/bow.gif" alt="Buig" title="Buig"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:eek:&nbsp;';"><img border="0" src="../_images/smilies/eek.gif" alt="Eek" title="EEK"></a></td>
						  </tr>
						  <tr>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:music:&nbsp;';"><img border="0" src="../_images/smilies/music.gif" alt="Muziek" title="Muziek"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:ironic:&nbsp;';"><img border="0" src="../_images/smilies/ironic.gif" alt="Ironisch" title="Ironisch"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:crazy:&nbsp;';"><img border="0" src="../_images/smilies/crazy.gif" alt="Gek" title="Gek"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:oink:&nbsp;';"><img border="0" src="../_images/smilies/oink.gif" alt="Oink" title="Oink"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:cool:&nbsp;';"><img border="0" src="../_images/smilies/cool.gif" alt="Cool" title="Cool"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:mad:&nbsp;';"><img border="0" src="../_images/smilies/mad.gif" alt="Kwaad" title="Kwaad"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:unsure:&nbsp;';"><img border="0" src="../_images/smilies/unsure.gif" alt="Onzeker" title="Onzeker"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:thumb:&nbsp;';"><img border="0" src="../_images/smilies/thumb.gif" alt="Duim" title="Duim"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;.party.&nbsp;';"><img border="0" src="../_images/smilies/party.gif" alt="Party" title="Party"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:clap:&nbsp;';"><img border="0" src="../_images/smilies/clap.gif" alt="Clap" title="Clap"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;.doh.&nbsp;';"><img border="0" src="../_images/smilies/doh.gif" alt="Doh" title="Doh"></a></td>
							<td width='35' ><a href="javascript:void(0);" onClick="document.fr.text.value+='&nbsp;:x&nbsp;';"><img border="0" src="../_images/smilies/shutup.gif" alt="Zwijg" title="Zwijg"></a></td>
						  </tr>
						</table>
					  </td>
					</tr>
					<tr>
					  <td colspan='2'>
						<fieldset class='fieldset'><legend class='legend'><i>Publicatie-opties</i></legend><br>
							<? if ($foto == "neen") {
								$fotofolder = $r[foto];
							} else {
								$fotofolder = "$folder$best";
							} ?>
							<input type="hidden" name="id" value="<? echo $id ?>">
							<input type="hidden" name="foto" value="<? echo $fotofolder ?>">
							<? if ($foto <> "neen" && $best == "") { ?>
								<input type="checkbox" value="YES" name="visible" checked disabled> <? echo $enable_visible; ?><br /><br />
							<? } else if ($r[visible] == "YES"){ ?>
								<input type="checkbox" value="YES" name="visible" checked> <? echo $enable_visible; ?><br /><br />
							<? } else { ?>
								<input type="checkbox" value="YES" name="visible"> <? echo $enable_visible; ?><br /><br />
							<? } ?>
						</fieldset><br><br>
							<? if ($foto == "neen" || $best <> "") { ?>
								<input type="submit" name="submit_text" class='verzenden' value="<? echo $submit_new_article ?>">
							<? } ?>
					  </td>
					</tr>
				</table>
			</form>
<?
}
?>