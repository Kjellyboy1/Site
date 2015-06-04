<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>



<script>
	$(document).ready(function(e) {
		
        $("#newdir").append("<option>dfghjklm</option>")
		
    });
	
	function getDir(){
			
	}
</script>
</head>



<body>

	<head>
	
	</head>

	<body>
			 <?php
// Controleren of het formulier verzonden is
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $target_dir = '';
    if(!empty($_POST['map']) && !empty($_POST['eigen_map']))
    {
        echo "Er mag slechts 1 optie gekozen zijn (map of eigen map)"; // Beide ingevuld
    }
    elseif(empty($_POST['map']) && empty($_POST['eigen_map']))
    {
        echo "Er moet minimaal 1 optie gekozen zijn (map of eigen map)"; // Niks ingevuld
    }
    elseif(empty($_POST['map']) && !empty($_POST['eigen_map']))
    {
        if(!file_exists($_POST['eigen_map']))
        {

            mkdir ('../media/werkjaar/2014-2015/'.$_POST['eigen_map']  , 0777, true ); // Map aanmaken, met rechten 0777 (Let op: Aanpassen indien anders gewenst)
			echo "Map aangemaakt! + ";

			
		
        }
     $target_dir = '../media/werkjaar/2014-2015/'. $_POST['eigen_map']; 

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
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if($uploadOk == 0)
            {
                echo "Sorry, your file was not uploaded.";
            }
            else
            {
                // if everything is ok, try to upload file
                if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
                {
                    echo "Foto/'s ". basename( $_FILES["fileToUpload"]["name"]). " zijn geüpload.";
                }
                else
                {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
}
?> 
<form action="upload.php" method="post" enctype="multipart/form-data">
  <p>Selecteer een map:
    <select name="map" id="newdir">

    </select>
<br />
<br />
Of vul een nieuwe map in: <input type="text" name="eigen_map">
 </p>
  <p>Datum Activiteit + Titiel.</p>
  <p>Bv. Jaar.Maand.Dag.Titel  </p>
  <p>Gebruik A.U.B. jullie verstand! Deze foto's komen openbaar!<br />
    <br />
    <input type="file" name="fileToUpload" id="fileToUpload" >
    <input type="submit" name="versturen" value="Versturen">
  </p>
</form>

  </body>

</html> 
