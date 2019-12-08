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

//ratachement de fichiers

include "ldap_bind.php";
include "messages.php";
include "functions.php";
include "Ad_bind_2012.php";
include "Ad_connect_2012.php";
 
 //ON recupère les donne du formulaire
if (!empty($_POST['username']) && (!empty($_POST['password']) && (!empty($_POST['newPassword1']) && (!empty($_POST['newPassword2']))))) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password = password_hash($password, 'sha512');
		$newPassword1 = $_POST['newPassword1'];
		$newPassword2 = $_POST['newPassword2'];
    
	//on fait de ces variables des variable de session
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['newPassword1'] = $newPassword1;
		$_SESSION['newPassword2'] = $newPassword2;
}

	else {

		$result = $messages['completeinfo'];
	    echo "$result"; 
	    exit();
	}

$ping=exec('/bin/ping -c1 -q -w1 '.$ADserver.' | grep transmitted | cut -f3 -d"," | cut -f2 -d"," | cut -f1 -d"%"');
  sleep(1);
  if ($ping !=0) {
  $result = $messages['noadserver']; 
    echo "$result"; 
    exit();
    }

  if ($ADbind == false) {

  $result = $messages['bindnotavailable'];
  echo "$result";
  exit();
}

	$ping=exec('/bin/ping -c1 -q -w1 '.$server.' | grep transmitted | cut -f3 -d"," | cut -f2 -d"," | cut -f1 -d"%"');
	sleep(1);
	if ($ping !=0) {
	$result = $messages['noldapserver']; 
  	echo "$result"; 
  	exit();
  	} 

$filter ="(|(uid=$username))";
 $fu = ldap_search($link,$racine,$filter);
 $retour = ldap_get_entries($link, $fu);
 $eval = $retour["count"];
  if ($eval==0) {
    $result = $messages['noExistingLogin'];
    echo "$result";
    exit();
  
  } else {
  	for ($i=0; $i < $retour['count'] ; $i++) {
		   $oldPassword = $retour[$i]["userpassword"][0];
		   echo ($oldPassword);
  		 if (! password_verify($password, $oldPassword)) {
  		 	 $result = $messages['wrongOldPassword'];
  		 	 echo "$result";
  		 	 exit();
  		 } else {
			   $renewPassword = password_hash($newPassword1, CRYPT_SHA512);
			   $result = $messages['tokensent'];
			   echo "$result";
		   }
  	}
  }

//$temp_pwd = passgen();
  //  echo "$temp_pwd"."\n";
    $temp_ldap_pwd = $renewPassword;
    $temp_AD_pwd = "\"".$temp_pwd."\"";

    $r = ldap_mod_replace($link, $userdn, array('userPassword' => "$temp_ldap_pwd"));

    if (!$r) {
      
      $result = $messages['couldnotresetldappswd'];
      echo "$result";
      exit();
    } else {
      $result = $messages['passwordreset'];
      echo "$result";

    }

?>
   