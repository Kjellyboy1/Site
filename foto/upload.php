<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
		<link href = "../bootstrap/css/bootstrap.min.css" rel = "stylesheet">
    	<link href = "../bootstrap/css/bootstrap.css" rel = "stylesheet">
   		<script	src="http://code.jquery.com/jquery-2.1.4.min.js"> </script>
  	    <script	src="../bootstrap/js/bootstrap.js"> </script>



<script>
	$(document).ready(function(e) {
		
        $("#newdir").append("<option>dfghjklm</option>")
		
    });
	
	function getDir(){
			
	}
</script>
</head>



<body>

<?php
			 include "config.php";
$db_instellingen = mysql_connect($dbhost,$dbuname,$dbpass); 
	mysql_select_db($dbname) or die($dberror);
	$query_instellingen = "SELECT * FROM Instellingen";
	$result_instellingen = mysql_query($query_instellingen);
	$instellingen = mysql_fetch_array($result_instellingen);
	$werkjaar = $instellingen[werkjaar]; 
	$map = '../media/werkjaar/'.$werkjaar. '/'  ;

// Controleren of het formulier verzonden is
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $target_dir = '';
    if(!empty($_POST['map']) && !empty($_POST['eigen_map']))
    {
        echo "<div class='alert alert-danger' role='alert'>Er mag slechts 1 optie gekozen zijn (map of eigen map)</div>"; // Beide ingevuld
    }
    elseif(empty($_POST['map']) && empty($_POST['eigen_map']))
    {
        echo "<div class='alert alert-danger' role='alert'>Er moet minimaal 1 optie gekozen zijn (map of eigen map)</div>"; // Niks ingevuld
    }
    elseif(empty($_POST['map']) && !empty($_POST['eigen_map']))
    {
        if(!file_exists($_POST['eigen_map']))
        {
	
			global $map;
            mkdir  (''. $map . '/' .$_POST['eigen_map']  , 0777, true ); // Map aanmaken, met rechten 0777 (Let op: Aanpassen indien anders gewenst)
			echo "<div class='alert alert-succses' role='alert'>Map aangemaakt! </div>";

			
		
        }
     $target_dir = ''. $map . '/'. $_POST['eigen_map']; 

    }    elseif(!empty($_POST['map']) && empty($_POST['eigen_map']))
    {
        $target_dir = $_POST['map']; 
    }
    if(!empty($target_dir))
    {
        if($_FILES["fileToUpload"]["name"] != '')
        {
            // Bestand upload script, 
            $target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            // Check if file already exists
            if(file_exists($target_file))
            {
                echo "<div class='alert alert-danger' role='alert'>Sorry, file already exists.</div>";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if($uploadOk == 0)
            {
                echo "<div class='alert alert-danger' role='alert'><strong>sorry,</strong> maar de foto/'s zijn niet upgeload.</div> "; }
            
            else
            {
                // if everything is ok, try to upload file
                if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
                {
                    echo "Foto/'s ". basename( $_FILES["fileToUpload"]["name"]). " zijn geüpload.";
                }
                else
                {
                    echo "<div class='alert alert-danger' role='alert'>Sorry, there was an error uploading your file.</div>";
                }
            }
        }
    }
}

	
	?> 
<form action="functions_all.php" method="post" enctype="multipart/form-data">
  <p>Selecteer een map:
    <select name="map" id="newdir">

    </select>
<br />
<br />
Of vul een nieuwe map in: 
<form class="form-horizontal">
  <div class="form-group"></div>
  </div>
  <p>Datum Activiteit + Titel.</p>
  <p>Bv. Jaar.Maand.Dag.Titel  </p>
  <p>Gebruik A.U.B. jullie verstand! Deze foto's komen openbaar!<br />
  
    <span class="form-group">
    <input type="text"  class="form-control"name="eigen_map" placeholder="20.05.2015.Lokaalavond " maxlength="20" />
    </span><br />
    <input type="file"  name="fileToUpload" id="fileToUpload" >
  <p> <button type="submit"  class="btn btn-success"name="versturen" value="Versturen">Versturen</button></p>
  </p>
  <p> </p>
</form>


</html> 
