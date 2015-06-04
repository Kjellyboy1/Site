<?php

function map($print) {
	include ("config.php");
	$action = $_GET['action'];
	$menu = $_GET['menu'];
	$id = $_GET['id'];
	$sort = $_GET['sort'];
			
	switch ($print) {
		case"yes":
			$print = "print.php";
		break;
		default:
			$print = "index.php";
	}
	
	if ($action == 'upload') {
		echo "
			<table align='center' cellspacing='5' cellpadding='5' border='0' width='100%'>
				<tr>
					<td class='error'>
						<b>Under Construction</b><br><br>We zijn vollop bezig om deze functie zo snel mogelijk actief te maken
					</td>
				</tr>
			</table>
		";
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
		  $queryb = "SELECT * FROM Leden WHERE position = 5 && visible = 'YES' ORDER BY maand, dag ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 && visible = 'YES' ORDER BY maand, dag ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 && visible = 'YES' ORDER BY maand, dag ASC";
		break;
		case "email":
		  if ($id <> "") {
			$emailb = "10px_down_disabled";
		  }
		  else {
		    $emailb = "10px_down";
		  }
		  $queryb = "SELECT * FROM Leden WHERE position = 5 && visible = 'YES' ORDER BY email ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 && visible = 'YES' ORDER BY email ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 && visible = 'YES' ORDER BY email ASC";
		break;
		case "gemeente":
		  if ($id <> "") {
			$gemeenteb = "10px_down_disabled";
		  }
		  else {
		    $gemeenteb = "10px_down";
		  }
		  $queryb = "SELECT * FROM Leden WHERE position = 5 && visible = 'YES' ORDER BY gemeente ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 && visible = 'YES' ORDER BY gemeente ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 && visible = 'YES' ORDER BY gemeente ASC";
		break;
		default:
		  if ($id <> "") {
			$naamb = "10px_down_disabled";
		  }
		  else {
		    $naamb = "10px_down";
		  }
		  $queryb = "SELECT * FROM Leden WHERE position = 5 && visible = 'YES' ORDER BY naam ASC";
		  $queryf = "SELECT * FROM Leden WHERE position = 4 && visible = 'YES' ORDER BY naam ASC";
		  $queryl = "SELECT * FROM Leden WHERE position = 1 && visible = 'YES' ORDER BY naam ASC";
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
			<table width='100%' align='left' border='0' cellspacing='0' cellpadding='0'>
			  <tr>
				<td>
				  <table width='100%' BORDER='0' align='left' cellspacing='0' cellpadding='5'>
					<tr>
					  <td class='title'>
						Bestuur
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
						<td width='30%' class='$classledenboven'>
							<a title='$sorteer' href='$print'><img border='0' src='../../_images/$naamb.gif'></a>&nbsp;&nbsp;<b>Naam</b>
						</td>
						<td width='33%' class='$classledenboven'>
							<a title='$sorteer' href='$print?sort=email'><img border='0' src='../../_images/$emailb.gif'></a>&nbsp;&nbsp;<b>Email</b>
						</td>						
						<td width='17%' class='$classledenboven'>
							<a title='$sorteer' href='$print?sort=gemeente'><img border='0' src='../../_images/$gemeenteb.gif'></a>&nbsp;&nbsp;<b>Gemeente</b>
						</td>						
						<td width='17%' class='$classledenboven'>
							<a title='$sorteer' href='$print?sort=bday'><img border='0' src='../../_images/$bdayb.gif'></a>&nbsp;&nbsp;<b>Geboortedatum</b>
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
				$email = substr(stripslashes($email), 0, $textchars);
	echo "
					<tr>
					  <td width='3%' class='$classledenonder'><center>$i</center></td>
					  <td width='30%' class='$classledenonder'>
	";
					if ($sort <> "") {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?sort=$sort&id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
					else {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
	echo"
					  </td>
					  <td width='33%' class='$classledenonder'>
						  <a title='$contacteer' href='mailto:$r[email]'>$email</a>
					  </td>
					  <td width='17%' class='$classledenonder'>
						  $r[gemeente]
					  </td>
					  <td width='17%' class='$classledenonder'>
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
						<td width='30%' class='$classledenboven'>
							<b>Naam
						</td>
						<td width='33%' class='$classledenboven'>
							<b>
						</td>						
						<td width='17%' class='$classledenboven'>
							<b>Gemeente
						</td>						
						<td width='17%' class='$classledenboven'>
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
			  
			  else if ($sort == "gemeente" && $r[gemeente] == "") { echo""; }
			  else {
			
				$jaar = $r[jaar];
				if ($jaar == "") {
					$jaar = '&nbsp;';
				}
				$imin = $i + 1;
				$email = substr(stripslashes($email), 0, $textchars);
	echo "
					<tr>
					  <td width='3%' class='$classledenonder'><center>$i</center></td>
					  <td width='30%' class='$classledenonder'>
	";
					if ($sort <> "") {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?sort=$sort&id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
					else {
					  echo"<a name='$r[id]' title='$meer_info' href='$print?id=$r[id]#$r[id]'>$r[naam] $r[voornaam]</a>";
					}
	echo"
					  </td>
					  <td width='33%' class='$classledenonder'>
						  
					  <td width='17%' class='$classledenonder'>
						  $r[gemeente]
					  </td>
					  <td width='17%' class='$classledenonder'>
	";
						if ($r[maand] == "") {
							echo "$jaar";
						}
						else if ($r[dag] == "") {
							echo "$jaar";
						}
						else {
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
echo"
							$jaar
";
						}
echo"
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
	$instellingen_mediafolder = '../../' . $instellingen[mediafolder].'/../';
	$instellingen_fotoNa = $instellingen[fotoNa];
	
	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query = "SELECT * FROM Leden WHERE id = $id";
	$result = mysql_query($query);
	$r = mysql_fetch_array($result);
	if ($r[fotos] == "na" || $r[fotos] == "" || $r[fotos] == "NONE") {
		$fotos = "$instellingen_fotoNa";
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
						<td width='30%' class='ledenborderboven'>
							<b><a name='$r[id]'>Foto</a></b>
						</td>						
						<td colspan='2' width='50%' class='ledenborderboven'>
							<b>Info</b>
						</td>						
						<td width='17%' class='ledenborderboven'>
							<b>Geboortedatum</b>
						</td>
					  </tr>
					  <tr>
					    <td width='3%' class='ledenborderonder'><center>$i</center></td>
					    <td width='30%' class='ledenborderonder'>
							<img width='150' height='150' src='$instellingen_mediafolder$fotos' class='images'>
						</td>
					    <td colspan='2' width='47%' class='ledenborderonder' valign='top'>
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
							<u><i></i></u><br />
							
						</td>
						<td width='17%' class='ledenborderonder'>
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
				      </tr>
				";
}
?>