<?php
global $id;
global $show;

// *******************
//    SITE OPTIONS
// *******************

// MySQL host:
$dbhost = "localhost";
// Database name
$dbname = "kljgistel_be";
$dbname_login = "kljgistel_be";
// Username
$dbuname = "kljgistel_be";
// Database password
$dbpass = "54002j";
// Number of news displayed on the front page
$text_limit = 1;
// Charset
$charset = "iso-8859-1";
// Number of characters to display of a single article on the main page
$textchars = 480;
// Date format
$date = "d.m.YY.";
// If the date format need a substraction, enter it
$start = 0;
$end = 6;
// Website url
$website = "http://www.kljgistel.be";
// Website title
$website_title = ".: KLJ Gistel :.: Nie Geweune :.";
// Primary email (info@yoursite.com)
$website_email = "kjellbaertsoen98@hotmail.com";
// Subject of the contact form message
$contact_subject = "Contact Form";
// Folder to save images
$image_folder = "img";

//*************************
// ADMINISTRATION SETTINGS
//*************************

// time user can remain idle without having to re-login (0 - unlimited, 300 - 5 minutes)
$session_duration = 0;
// number of attempts (0 - unlimited)
$login_attempts = 0;

$menu = "foto";

// Error
$dberror = "Error while connecting to the database!";

// Administration
$submit_new_article = "Verzenden";
$up = "Tabel één positie omhoog";
$down = "Tabel één positie omlaag";
$delete = "Geselecteerde tabellen wissen";

$foto_nok_1 = "Foto niet geüpload";
$foto_nok_2 = "Mogelijke oorzaak: de foto is groter dan 300kb of de foto is geen 'jpg, gif, png, bmp, jpeg, JPG' bestand";

$delete_nok_1 = "Tabel(len) niet verwijderd";
$delete_nok_2 = "Er werden geen tabellen aangevinkt om te verwijderen!";
$nieuw_nok_1 = "De data is niet verzonden";
$nieuw_nok_2 = "Je hebt niets ingevuld als tekst";
$deleted = "Gewist!";
$toegevoegd = "Toegevoegd!";
$gewijzigd = "Gewijzigd!";

$checkbox = "Er is geen enkele tabel aangevinkt";
$text_error = "Je moet een tekst ingeven";
$tabel_toegevoegd = "Tabel toegevoegd.";
$tabel_gewijzigd = "Tabel gewijzigd";
$enable_visible = "Tabel zichtbaar maken voor het publiek";

?>
<?php
/*
MINIGAL NANO
- A PHP/HTML/CSS based image gallery script

This script and included files are subject to licensing from Creative Commons (http://creativecommons.org/licenses/by-sa/2.5/)
You may use, edit and redistribute this script, as long as you pay tribute to the original author by NOT removing the linkback to www.minigal.dk ("Powered by MiniGal Nano x.x.x")

MiniGal Nano is created by Thomas Rybak

Copyright 2010 by Thomas Rybak
Support: www.minigal.dk
Community: www.minigal.dk/forum

Please enjoy this free script!
*/

// EDIT SETTINGS BELOW TO CUSTOMIZE YOUR GALLERY
$thumbs_pr_page 		= "28"; //Number of thumbnails on a single page
$gallery_width 			= "100%"; //Gallery width. Eg: "500px" or "70%"
$backgroundcolor 		= "white"; //This provides a quick way to change your gallerys background to suit your website. Use either main colors like "black", "white", "yellow" etc. Or HEX colors, eg. "#AAAAAA"
$templatefile 			= "mano"; //Template filename (must be placed in 'templates' folder)
$title 					= "MiniGal Nano Testsite"; // Text to be displayed in browser titlebar
$author 				= "Rybber";
$folder_color 			= "black"; // Color of folder icons: blue / black / vista / purple / green / grey
$sorting_folders		= "name"; // Sort folders by: [name][date]
$sorting_files			= "name"; // Sort files by: [name][date][size]
$sortdir_folders		= "ASC"; // Sort direction of folders: [ASC][DESC]
$sortdir_files			= "ASC"; // Sort direction of files: [ASC][DESC]

//LANGUAGE STRINGS
$label_home 			= "Home"; //Name of home link in breadcrumb navigation
$label_new 				= "Nieuw"; //Text to display for new images. Use with $display_new variable
$label_page 			= "Pagina"; //Text used for page navigation
$label_all 				= "Alles"; //Text used for link to display all images in one page
$label_noimages 		= "Geen foto's"; //Empty folder text
$label_loading 			= "Laden..."; //Thumbnail loading text

//ADVANCED SETTINGS
$thumb_size 			= 120; //Thumbnail height/width (square thumbs). Changing this will most likely require manual altering of the template file to make it look properly! 
$label_max_length 		= 30; //Maximum chars of a folder name that will be displayed on the folder thumbnail  
$display_exif			= 1;
?>