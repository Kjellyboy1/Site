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
$website_email = "kljgistel@gmail.com";
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

$menu = "leden";

// beginjaar telling
$jaar_begin = "1974";

// Eindjaar telling
$jaar_einde = "2015";

// Aantal karakters voor email
$textchars = 32;
$textchars_admin = 23;

// Error
$dberror = "Error while connecting to the database!";

// Administration
$submit_new_article = "Verzenden";
$sendfoto = "Foto instellen";
$geen_profielfoto = "Het (bestuurs-)lid wilt niet dat er een foto van mij op de site komt (ONOMKEERBAAR)";
$zoeken = "Zoeken";
$delete = "Geselecteerde tabellen wissen";
$rights_all = "Rechten: allemaal";
$rights_semi = "Rechten: semi";
$rights_none = "Rechten: geen";
$rights_notregistered = "Niet geregistreerd";
$del_article = "Verwijder geselecteerde personen";
$edit_article = "Wijzig gegevens persoon";
$edit_profielfoto = "Profielfoto wijzigen";
$send = "Verzenden";
$delete_profiel = "Delete registratie";
$enable_visible = "(bestuurs-)lid zichtbaar maken";

$delete_nok_1 = "Leden niet verwijderd";
$delete_nok_2 = "Er werden geen leden aangevinkt om te verwijderen!";
$profielfoto_nok_1 = "Foto niet geüpload";
$profielfoto_nok_2 = "Mogelijke oorzaak: de foto is groter dan 300kb of de foto is geen 'jpg, gif, png, bmp, jpeg, JPG' bestand";

$admin_error = "Fout!";
$deleted = "Gewist!";
$toegevoegd = "Toegevoegd!";
$gewijzigd = "Gewijzigd!";

$naam_error = "Je hebt geen naam en/of voornaam ingegeven";
$checkbox = "Er is geen enkele rij aangevinkt";

$categorie = "Categorie";
$categorie5 = "Dagelijks bestuurslid";
$categorie4 = "Bestuurslid";
$categorie1 = "Lid";
$naam = "Voornaam + Naam";
$fotos = "Foto";
$geboortedatum = "Geboortedatum";
$adres = "Straat + Nr";
$gemeente = "Postcode + Gemeente";
$telefoon = "Telefoon/GSM";
$email = "E-mail";

?>