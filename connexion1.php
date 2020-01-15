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
$passwordalg = "ssha";
//ratachement de fichiers

include "ldap_bind.php";
include "messages.php";
include "functions.php";
include "Ad_bind_2012.php";
include "Ad_connect_2012.php";
 
 //ON recupère les donnees du formulaire
if (!empty($_POST['username']) && (!empty($_POST['password']) && (!empty($_POST['newPassword1']) && (!empty($_POST['newPassword2']))))) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];

    // On retire les slashs rajoutés par PHP
    $password = stripslashes($password);
    $newPassword1 = stripslashes($newPassword1);
    $newPassword1 = stripslashes($newPassword2);
    $username = stripslashes($username);

    //on fait de ces variables des variable de session
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['newPassword1'] = $newPassword1;
    $_SESSION['newPassword2'] = $newPassword2;
} else {

	$result = $messages['completeinfo'];
	echo "$result";
	exit();
}

//Verification de la disponibilité du serveur AD à l'aide d'un ping

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
//Vérification de la disponibilité du serveur Openldap à l'aide d'un  ping

	$ping=exec('/bin/ping -c1 -q -w1 '.$server.' | grep transmitted | cut -f3 -d"," | cut -f2 -d"," | cut -f1 -d"%"');
	sleep(1);
	if ($ping !=0) {
	$result = $messages['noldapserver']; 
  	echo "$result"; 
  	exit();
	  }

// hahshage du mot de passe renseigné par l'utilisateur pour qu'il corresponde au hashage dans existant dans la base

/*
if ($passwordalg == "ssha") {
	if (!function_exists('mhash') && !function_exists('mhash_keygen_s2k')) {
		echo "ERROR = mhash seems to be missing on the system" . "<br />";
		exit - 1;
	}

	mt_srand((float) microtime() * 1000000);
	$salt = mhash_keygen_s2k(MHASH_SHA256, $newPassword1, substr(pack("h*", md5(mt_rand())), 0, 8), 4);
	if ($salt == FALSE) {
		echo "ERROR = salting didn't work" . "<br />";
	}
	$hash = "{SSHA}" . base64_encode(mhash(MHASH_SHA256, $newPassword1 . $salt) . $salt);

	// affectation de la valeur du hasahge obtenu dans la varaible $Password

	$Password = $hash;
}
*/
//$hash_password = make_sha_256_password($password);
//$Password = $hash_password;
$hash_password = password_hash($password,CRYPT_SHA512);
$Password = $hash_password;

/*
$password = password_hash($password, 'sha512');
$password = hash('sha512', $password);
*/

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
        echo "l'ancien mot de passe est :"."$oldPassword"."\n"."\n";
    }
    if (password_verify($password,$oldPassword)) {

    $renewPassword = hash('sha256', $newPassword1);
    $renewPassword = $Password;
    $result = $messages['tokensent'];
    echo "$result";
  } else {
    $result = $messages['wrongOldPassword'];
    echo "$result" . "\n";
    echo "$Password";
    exit();
  }
}
    /* if ($Password == $oldPassword) {
			// $renewPassword = password_hash($newPassword1, 'sha512');
			$renewPassword = hash('sha256', $newPassword1);
			$renewPassword = $Password;
			$result = $messages['tokensent'];
			echo "$result";	
			
  		 } else {
			$result = $messages['wrongOldPassword'];
			echo "$result"."\n";
			echo "$Password";
			exit();
		   }
  	}
  */

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
   