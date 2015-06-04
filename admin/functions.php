 <html>
 <head>
 <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
		<link href = "../bootstrap/css/bootstrap.min.css" rel = "stylesheet">
    	<link href = "../bootstrap/css/bootstrap.css" rel = "stylesheet">
   		<script	src="http://code.jquery.com/jquery-2.1.4.min.js"> </script>
  	    <script	src="../bootstrap/js/bootstrap.js"> </script>
</head>
<body>
<?php

function map() {
	include ("config.php");
	$gegevens = $_GET['gegevens'];
	$wachtwoord = $_GET['wachtwoord'];
	$gebruikersnaam = $_GET['gebruikersnaam'];

	if (isset($_POST['registreren'])) {
		if (strlen($_POST['gebruikersnaam']) > 2 AND strlen($_POST['voornaam']) > 2 AND strlen($_POST['naam'])> 0 AND strlen($_POST['wachtwoord']) > 2 AND strlen($_POST['bev_wachtwoord']) > 2) {
			$db = mysql_connect($dbhost,$dbuname,$dbpass); 
			mysql_select_db($dbname) or die($dberror);
			$query = "SELECT * FROM Leden";
			$result = mysql_query($query);
			$b=0;
			while ($r = mysql_fetch_array($result)) {
				if (($_POST['gebruikersnaam']) == $r[username]) {
					$b++;
				}
			}
			
			if ($b == 0) {			
				if ($_POST['wachtwoord'] == $_POST['bev_wachtwoord']) {
					$gebruikersnaam = $_POST['gebruikersnaam'];
					$voornaam = ucwords($_POST['voornaam']);
					$naam = ucwords($_POST['naam']);
					$straat = ucwords($_POST['adres']);
					$nr = $_POST['nr'];
				$wachtwoord = md5($_POST['wachtwoord']);
					$bev_wachtwoord = md5($_POST['bev_wachtwoord']);
					
					$db = mysql_connect($dbhost,$dbuname,$dbpass); 
					mysql_select_db($dbname) or die($dberror);
					$query = "SELECT * FROM Leden";
					$result = mysql_query($query);
					$i=0;
					$a=0;
					while ($r = mysql_fetch_array($result)) {
						$a++;
					}
					$db = mysql_connect($dbhost,$dbuname,$dbpass); 
					mysql_select_db($dbname) or die($dberror);
					$query = "SELECT * FROM Leden";
					$result = mysql_query($query);
					while ($r = mysql_fetch_array($result)) {
						$i++;
						if ($r[voornaam] == $voornaam && $r[naam] == $naam) {
						
								if ($r[username] == '' && $r[user_password] == '') {
									$db = mysql_connect($dbhost,$dbuname,$dbpass); 
									mysql_select_db($dbname) or die($dberror);
									mysql_query("UPDATE Leden SET username='$gebruikersnaam' WHERE id='$r[id]'");
									mysql_query("UPDATE Leden SET user_password='$wachtwoord' WHERE id='$r[id]'");
									mysql_query("UPDATE Leden SET rights='SEMI' WHERE id='$r[id]'");
									echo"<script>self.location='$print?gegevens=ok';</script>";
								}
								else {
									echo"<script>self.location='$print?action=registreren&gegevens=reg';</script>";
								}
						
						}
					}
				}
				else {
					echo"<script>self.location='$print?action=registreren&wachtwoord=nok';</script>";
				}
			}
			else {
				echo"<script>self.location='$print?action=registreren&gebruikersnaam=nok';</script>";
			}
		}
		else {
			echo"<script>self.location='$print?action=registreren&gegevens=nok';</script>";
		}
	}
	if ( $wachtwoord == "nok" ) {
		echo "
			<div class='alert alert-danger' role='alert'>
						<b>". $gegevens_error ."</b><br><br>". $wachtwoord_reasons ."
					</div>
		";
	}
	if ( $gegevens == "nok" ) {
		echo "
		<div class='alert alert-danger' role='alert'>
			<b>". $gegevens_error ."</b><br><br>". $gegevens_reasons ."
					</div>
		";
	}
	if ( $gebruikersnaam == "nok" ) {
		echo "
			<div class='alert alert-danger' role='alert'>
						<b>". $gegevens_error ."</b><br><br>". $gebruikersnaam_nok2 ."
					</div>
		";
	}
	if ( $gegevens == "nreg" ) {
		echo "
			<div class='alert alert-danger' role='alert'>
						<b>". $gegevens_error ."</b><br><br>". $gegevens_nreg ."
				</div>

		";
	}
	if ( $gegevens == "reg" ) {
		echo "
				<div class='alert alert-success' role='alert'>
						<b>". $gegevens_error ."</b><br><br>". $gegevens_reg ."
					</div>
		";
	}
	if ( $gegevens == "ok" ) {
			echo "
			<div class='alert alert-success' role='alert'>
						<b>". $gegevens_sent ."</b><br><br>". $gegevens_ok ."
					</div>
		";
	}
	if ( $_GET['action'] == "#registreer") {
		 registreren ();
	}
	else {
?>
<style type="text/css">
.registreer {
	font-size: 18px;
}

</style>

	<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    <center>
		<form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Gebruikersnaam</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputEmail3" placeholder="Gebruikersnaam" name="mname_name">
    </div>
  </div><p>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Wachtwoord</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="inputPassword3" placeholder="Wachtwoord" name="mpass_pass"></div></p>

<div class="form-group">
 
 	  
     <div class="col-sm-offset-2 col-sm-10">
 	  </div>
      <p><center>
      <button type="submit" class="btn btn-default" name="Verzenden">
      Inloggen
      </button> </p><p>
      <a class="btn btn-default" href="#registreer" data-toggle="modal" role="button">Registreer</a>
     </center></p>
</div>
</form>
<center>

<!-- modal staat hieronder! -->		

	</form>
    	<div class="modal fade" id="registreer" role="dialog">
			<div class="modal-dialog modal-lg">
    			<div class="modal-content">
       		 		<div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
             			<center><h3> Registreer </h3></center>
          				<center> <h4> Alleen leden van KLJ Gistel kunnen zich aanmelden! </h4> </center>
				
          			</div>
                    <div class="modal-body">
     					 <form name="fr" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		 					<div class="form-group">
    						<label for="exampleInputEmail1">Gebruikersnaam</label>
   						    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Gebruikersnaam" name="gebruikersnaam">
  					</div>
				   <div class="form-group">
    			   <label for="exampleInputEmail1">Voornaam</label>
    			   <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Voornaam" name="voornaam">
 				   </div>
                   
  				 <div class="form-group">
    			<label for="exampleInputEmail1">Naam</label>
   			    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Naam" name="naam">
 			     </div>
				
				 <div class="form-group">
   				 <label for="exampleInputPassword1">Wachtwoord</label>
    			 <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Wachtwoord" name="wachtwoord">
 			    </div>
				
					 <div class="form-group">
    				<label for="exampleInputPassword1">Herhaal uw wachtwoord</label>
    				<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Wachtwoord" name="bev_wachtwoord">
 				    </div>
				
			<p><center>
            	<input type='submit' name='registreren' value='Registreren' class='btn btn-success' />
       	   </p></center>
       	         
                
					<div class="alert alert-info" role="alert">
                    Namen en voornamen zijn in hoofdletters!
                    <br>Gebruik geen speciale tekens bij het opgeven van een gebruikersnaam (vb. / ' ")</br>
                    </div>
			
            
	</form>
            </div>
            <div class="modal-footer">
            	<a class="btn btn-default" data-dismiss ="modal">Sluiten</a>            
            
            </div>
      	
        	</div>
        </div>
    </div>
</div>
<!-- Wordt niet meer gebruikt! laten staan anders error! -->	
<?
	}
}

function registreren () {
	?>
    
<?
	
}
?>
</body>
</html>
