<?php
global $id;
global $show;

// *******************
//    SITE OPTIONS
// *******************

// MySQL host:
$sitenaam = "KLJ Gistel";
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
$website = "http://www.site.kljgistel.be";
// Website title
$website_title = "fghjk";
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

$menu = "admin";

// Error
$dberror = "Fout in de database.";

// Ledeninfo
$geboortedatum = "Geboortedatum";
$adres = "Straat + Nr / Postcode + Gemeente";
$telefoon = "Telefoon OF GSM";
$email = "E-mail";

// Wachtwoord
$wachtwoord_oud = "Voer je huidige wachtwoord in om door te gaan";
$wachtwoord_1 = "Nieuw wachtwoord";
$wachtwoord_2 = "Bevestig nieuw wachtwoord";

$send = "Verzenden";
$sendmediafolder = "Folder instellen";
$sendfoto = "Foto instellen";

// Error
$gegevens_sent = "Je bent geregistreerd";
$gegevens_error = "Je bent niet geregistreerd";
$gegevens_reasons = "Je hebt niet alle gegevens ingevuld";
$gebruikersnaam_nok1 = "Profiel niet gewijzigd";
$gebruikersnaam_nok2 = "De gekozen gebruikersnaam bestaat al";
$gebruikersnaam_nok3 = "De nieuwe gebruikersnaam is te kort";
$gegevens_nreg = "De ingevulde gegevens komen niet overeen met de geregistreerde gevegevens bij ons";
$gegevens_reg = "Je bent reeds geregistreerd op deze website";
$wachtwoord_reasons = "Je hebt niet hetzelfde wachtwoord opgegeven";
$gegevens_ok = "Je kan voortaan inloggen met je ingevulde gebruikersnaam";
$geen_profielfoto = "Ik wil niet dat er een foto van mij op de site komt";

$profiel_nok_1 = "";
$profiel_nok_2 = "";
$profielfoto_nok_1 = "Foto niet geüpload";
$profielfoto_nok_2 = "Mogelijke oorzaak: de foto is groter dan 300kb of de foto is geen 'jpg' bestand";
$wachtwoord_nok_1 = "Wachtwoord niet gewijzigd";
$wachtwoord_nok_2 = "Het huidig wachtwoord is niet correct";
$wachtwoord_nok_3 = "De bevestiging komt niet overeen met het nieuwe wachtwoord";
$wachtwoord_nok_4 = "Het nieuwe wachtwoord is te kort (min. 6 karakters)";


?>