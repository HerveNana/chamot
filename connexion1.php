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



require "ldap_bind.php";

require "messages.php";

require "functions.php";

require "Ad_bind_2012.php";

require "Ad_connect_2012.php";



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

/*

$hash_password = password_hash($password,CRYPT_SHA512);

$Password = $hash_password;
*/

//  On vérifie l'existence en base lDAP du login  renseigné par l'utilisateur

$filter ="(|(uid=$username))";

$fu = ldap_search($link,$racine,$filter);

$retour = ldap_get_entries($link, $fu);

$eval = $retour["count"];

if ($eval==0) {       // Si le login n'exite pas, on renvoi un message d'erreur

  $result = $messages['noExistingLogin'];

  echo "$result";

  exit();
  

} else {        //Si le login est présent en base on tente une connexion ldap avec le mot de passe renseigné.

  $userdn = 'uid='."$username".','.'ou=People,dc=univ-guyane,dc=fr';
 // options=ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3);
  $userbind = ldap_bind($link,$userdn,$password);
  
  if (!$userbind)  {
    
  $result = $messages['wrongOldPassword'];

    echo "$result" . "\n";

    echo "$password";

    exit();

  } else  {
    
    $renewPassword = make_sha_256_password($newPassword2);
    
    $userpsswd["userPassword"] = $renewPassword;      
    
    $r = ldap_mod_replace($link, $userdn,$userpsswd);
    
    if (!$r){
      
      $result=$messages['couldnotresetldappswd'];
      
      echo "$result";
      exit();

    }else{
      $result=$messages['passwordreset'];
      
      echo  "$result";
    }
  }
}
/*
$r=true;

if (!$r){
  $result=$messages['couldnotresetldappswd'];
  echo $result;
  exit();
}else{
  $result=$messages['passwordreset'];
  echo  $result;
}
*/

?>