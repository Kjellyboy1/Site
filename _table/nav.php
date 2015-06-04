<?php 

function nav($folder) {

?>
  <div id="navigation">
	<ul>
	  <li
	  <? if ($folder == 'home') { ?>  class="selected" <? }
	  ?>><span></span><a href="../main/index.php?p=../pagina/home/">HOME</a><span></span></li>
	  <li
	  <? if ($folder == 'leden') { ?>  class="selected" <? }
	  ?>><span></span><a href="../leden/">LEDEN</a><span></span></li>
	  <li
	  <? if ($folder == 'activi') { ?> class="selected" <? }
	  ?>><span></span><a href="../main/index.php?p=../activi/">ACTIVI</a><span></span></li>
	 
	 
	  <li
	  <? if ($folder == 'admin') { ?> class="selected" <? }
	  ?>><a href="../admin/">Login</a><span></span></li>
	</ul>
  </div>
<?
}

?>