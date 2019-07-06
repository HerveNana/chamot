<?php
session_start();
/*header("Content-type:application/json;charset=utf-8"); */
set_time_limit(30);
ini_set('error_reporting', E_ALL);
// Afficher les erreurs à l'écran
ini_set('display_errors', 1);
// Enregistrer les erreurs dans un fichier de log
ini_set('log_errors', 1);
// Nom du fichier qui enregistre les logs (attention aux droits à l'écriture)
//ini_set('error_log', dirname(__file__) . '/log_error_php.txt');
// Afficher les erreurs et les avertissements
error_reporting(E_ALL);

// Decclaration des variables

include "ldap_bind.php";
include "messages.php";
include "functions.php";


//on recupere ensuite les données renseignées du formulaire dans des variables...
// 1 - vérifie par acquis de consience que leqs champs on tous été renseignés... Dans le cas contraire on affecte àla variable result le message d'erreure approprié...

		if (!empty($_POST['prenom']) && (!empty($_POST['nom']) && (!empty($_POST['mail'])))) {		
		$prenom = $_POST['prenom'];
		$nom = $_POST['nom'];
		$emailPerso = $_POST['mail'];
	//on fait de ces variables des vaiables de session...
		$_SESSION["prenom"] = $prenom;
		$_SESSION["nom"] = $nom;
		$_SESSION["emailPerso"] = $emailPerso;
	
	} else {
		$result = $messages['completeinfo'];
	    echo "$result"; 
	    exit();
	} 

//Decclaration de fonctions:
function find_ldap_user() {
 include "ldap_bind.php";
 global $nom, $prenom, $emailPerso;
  $filter ="(&(sn=$nom)(givenName=$prenom)(supannMailPerso=$emailPerso))";
 $fu = ldap_search($link,$racine,$filter);
 return $fu;
 $retour = ldap_get_entries($link, $fu);
  }


	$ping=exec('/bin/ping -c1 -q -w1 '.$server.' | grep transmitted | cut -f3 -d"," | cut -f2 -d"," | cut -f1 -d"%"');
	sleep(1);
	if ($ping !=0) {
	$result = $messages['noldapserver']; 
  	echo "$result"; 
  	exit();
  	} 

 $filter ="(&(sn=$nom)(givenName=$prenom)(supannMailPerso=$emailPerso))";
 $fu = ldap_search($link,$racine,$filter);
 $retour = ldap_get_entries($link, $fu);
 $eval = $retour["count"];
  if ($eval==0) {
  	$result =$messages['nocorrectinfo'];
  	echo "$result";
  	exit();
  
  }  else {
 
  $found = find_ldap_user();
  echo "<script> alert('$found');</script>";
  $eval = ($found?'success':'failure');

echo "<script> alert('$eval');</script>";
  if ($eval == 'failure'){
  	$result = $messages['usernotexist'];
  	echo "$result";
  	exit();
  } else {
  	echo "success";
  
  }
}

?>