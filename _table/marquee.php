<?php 
function ReplaceSmilie($txt)
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

function marquee() {
	include ("configb.php");
	$day = date("j");
	$month = date("m");
	$yearA = date("y");
	$year = date("Y");

	$db = mysql_connect($dbhost,$dbuname,$dbpass); 
  	mysql_select_db($dbname) or die($dberror);
	$queryA = "SELECT * FROM Activi WHERE visible = 'YES' && ((dag = '' && maand = '' && jaar = '') || (einddag >= $day && eindmaand = $month && eindjaar = $yearA) || eindjaar > $yearA || (eindmaand > $month && eindjaar = $yearA)) ORDER BY jaar, maand, dag ASC";
	$resultA = mysql_query($queryA);
	$rA = mysql_fetch_array($resultA);
	
	
	$queryV = "SELECT * FROM Leden WHERE visible = 'YES' && maand = $month && dag = $day ORDER BY maand, dag ASC";
	$resultV = mysql_query($queryV);
	$rV = mysql_fetch_array($resultV);
	
	$queryW = "SELECT * FROM Weetjes WHERE visible = 'YES' ORDER BY plaats DESC"; 
	$resultW = mysql_query($queryW);
	$rW = mysql_fetch_array($resultW);
	$tekst = str_replace("<br />", " ", $rW[text]);
	$tekst = substr($tekst,0,100);
	$tekst = ReplaceSmilie($tekst);
	
	?><marquee behavior="alternate" width="774" direction="left" scrollamount="1" scrolldelay="20" TRUESPEED><?
	
	$i=0;
	while ($i<100) {
		if ($rA[title] <> "") {
			?><a href="http://www.kljgistel.be/activi/index.php?menu=&action=opmerkingen&id=<? echo$rA[id] ?>"><b>A&raquo;</b> <? echo"$rA[dag]/$rA[maand]/$rA[jaar]: $rA[title]";?></a><?
			?> • <?
		}
		$queryEsom1 = "SELECT * FROM Event WHERE visible = 'YES' && ((dag = '' && maand = '' && jaar = '') || (einddag >= $day && eindmaand = $month && eindjaar = $year) || eindjaar > $year || (eindmaand > $month && eindjaar = $year)) ORDER BY jaar, maand, dag ASC";
		$resultEsom1 = mysql_query($queryEsom1);
		$rEsom1 = mysql_fetch_array($resultEsom1);
		if ($rEsom1[naam] <> "") {
			$dagEsom = $rEsom1[dag];
			$maandEsom = $rEsom1[maand];
			$jaarEsom = $rEsom1[jaar];
			$queryEsom2 = "SELECT * FROM Event WHERE visible = 'YES' && (dag = $dagEsom && maand = $maandEsom && jaar = $jaarEsom)  ORDER BY jaar, maand, dag ASC";
			$resultEsom2 = mysql_query($queryEsom2);
			while ($rE = mysql_fetch_array($resultEsom2)) {
				$jaarE = substr($rE[jaar],2);
				?><a href="http://www.kljgistel.be/event/index.php?menu=&action=opmerkingen&id=<? echo$rE[id] ?>"><b>E&raquo;</b> <? echo"$rE[dag]/$rE[maand]/$jaarE: $rE[naam]";?></a><?
				?> • <?
			}
		}
		if ($rV[naam] <> "") {
			?><a href="http://www.kljgistel.be/leden/index.php?&id=<? echo$rV[id] ?>#<? echo$rV[id] ?>"><b>V&raquo;</b> <? echo"$rV[voornaam] $rV[naam]"; ?> is jarig vandaag!<?
			?> • <?
		}
		if ($tekst <> "") {
			?><a href="http://www.kljgistel.be/weetjes/index.php?action=opmerkingen&id=<? echo$rW[id] ?>"><b>W&raquo;</b> Wist je dat <? echo"$tekst";?> ...</a><?
			?> • <?
		}
		$i++;
	}
	?></marquee><?
}
?>