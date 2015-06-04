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
	$show = $_GET['show'];
	
	$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$folder = '../'.$instellingen[mediafolder].$instellingen[homefolder];
	
  	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
  	mysql_select_db($dbname) or die($dberror);
	$query = "SELECT * FROM Vendelen WHERE foto <> '' && text <> '' && visible = 'YES' ORDER BY plaats DESC"; 
	$shorten = $textchars;
	$result = mysql_query($query);
	$i=1;
  	while ($r = mysql_fetch_array($result)) {
		$vtext = ReplaceSmilies($r[text]);
		if ($i % 2 == 0) {
	echo "
	<table align='center' cellspacing='0' cellpadding='5' border='0' width='100%'>
		<tr>
		  <td BORDER='0' align='left'>
			$vtext
		  </td>
		  <td width='165'>
			<table align='right' cellspacing='0' cellpadding='0' border='0' width='150'>
			  <tr>
				<td align='right'>
				  <img width='150' class='images_home' src='$folder$r[foto]'><br />
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
		  <td width='165'>
			<table align='left' cellspacing='0' cellpadding='0' border='0' width='150'>
			  <tr>
				<td align='left'>
				  <img width='150' class='images_home' src='$folder$r[foto]'><br />
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
			$vtext
		  </td>
		</tr>
	</table><br />
	";
		}
		$i++;
	}
}
?>