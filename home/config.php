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

$menu = "home";

// Error
$dberror = "Error while connecting to the database!";

// Administration
$submit_new_article = "Verzenden";
$up = "Tabel ייn positie omhoog";
$down = "Tabel ייn positie omlaag";
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