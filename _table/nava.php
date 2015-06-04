<?php 

function nav($folder) {

?>
  <div id="navigation">
	<ul>
	  <li
	  <? if ($folder == 'home') { ?> class="selected" <? }
	  ?>><span></span><a href="../home/">Home</a><span></span></li>
	  <li
	  <? if ($folder == 'leden') { ?> class="selected" <? }
	  ?>><span></span><a href="../leden/">Leden</a><span></span></li>
	  <li
	  <? if ($folder == 'activi') { ?> class="selected" <? }
	  ?>><span></span><a href="../activi/">Activiteiten</a><span></span></li>
	  <li
	 
	  <? if ($folder == 'weetjes') { ?> class="selected" <? }
	  ?>><span></span><a href="../weetjes/">Foto's</a><span></span></li>
	  <li
	  <? if ($folder == 'gasten') { ?> class="selected" <? }
	  ?>><a href="../gasten/">Sport</a><span></span></li>
	  <li
	  <? if ($folder == 'admin') { ?> class="selected" <? }
	  ?>><a href="../admin/"><b>Admin</b></a><span></span></li>
	</ul>
  </div>
<?
}

?>