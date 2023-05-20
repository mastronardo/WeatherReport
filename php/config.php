<?php
$username="weatherreport";
$password="";
$address="localhost";
$db_name="my_weatherreport";
$connex_db= new mysqli($address,$username,$password,$db_name);
if (!$connex_db)
    die ("Impossibile connettersi: ". $connex_db->connect_error);

session_start();

// Stabiliamo la connessione al database e creiamo la sessione.
// Tutte le pagine che necessitano di ciò, includono questo file.