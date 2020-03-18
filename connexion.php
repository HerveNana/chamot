<?php
session_start();
/*header("Content-type:application/json;charset=utf-8"); */
/*ini_set('error_reporting', E_ALL);
// Afficher les erreurs à l'écran
ini_set('display_errors', 1);
// Enregistrer les erreurs dans un fichier de log
ini_set('log_errors', 1);
// Nom du fichier qui enregistre les logs (attention aux droits à l'écriture)
//ini_set('error_log', dirname(__file__) . '/log_error_php.txt');
// Afficher les erreurs et les avertissements
error_reporting(E_ALL); */

// Decclaration des variables

require "ldap_bind.php";
require "messages.php";
require "functions.php";
require "Ad_bind_2012.php";
require "Ad_connect_2012.php";


//on recupere ensuite les données renseignées du formulaire dans des variables...
// 1 - vérifie par acquis de consience que leqs champs on tous été renseignés... Dans le cas contraire on affecte àla variable result le message d'erreure approprié...

if (!empty($_POST['prenom']) && (!empty($_POST['nom']) && (!empty($_POST['mail'])))) {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $emailPerso = $_POST['mail'];
    $account = $_POST['account'];
    //on fait de ces variables des vaiables de session...
    $_SESSION["prenom"] = $prenom;
    $_SESSION["nom"] = $nom;
    $_SESSION["emailPerso"] = $emailPerso;
    $_SESSION["account"] = $account;
  
     // On retire les slashs rajoutés par PHP
     // $prenom = stripslashes($nom);
    //$nom = stripslashes($prenom);
    //$emailPerso = stripslashes($emailPerso);
    //$word2 = stripslashes($newPassword2);

} else {
    $result = $messages['completeinfo'];
    echo "$result"; 
    exit();
} 

//On vérifie la disponibilité des serveur LDAP et Active Directory

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

$filter ="(&(sn=$nom)(givenName=$prenom))";
$fu = ldap_search($link, $racine, $filter);
$retour = ldap_get_entries($link, $fu);
$eval = $retour["count"];
 //echo($eval);
if ($eval==0) {
    $result = $messages['usernotexist'];
    echo "$result";
    exit();
  
}  

$filter1 ="(&(sn=$nom)(givenName=$prenom)(supannMailPerso=$emailPerso))";
$fu = ldap_search($link, $racine, $filter1);
$retour = ldap_get_entries($link, $fu);
$eval = $retour["count"];
if ($eval==0) {
    $result =$messages['nocorrectinfo'];
    echo "$result";
    exit();
}

if ($eval == 2 && $account == "") {  // Si le compte sélectionné est un compte professionnel
    
    echo "<script> $('#uiduser').addClass('active');
    $('#uiduser').css({
      display : 'block'
      });
      <!-- alert('test:".$account."'); -->
      </script>";
      $result = $messages['multipleaccount'];
      echo "$result";
      exit();
}

if ($eval == 2 && $account !== "") {
      switch ($account) {
    case 'compte_professionnel':
        $affiliation = 'staff';
        $filter2 = "(&(sn=$nom)(givenName=$prenom)(eduPersonAffiliation=$affiliation))";
        $user_entry_pro = ldap_search($link, $racine, $filter2);
        $info = ldap_get_entries($link, $user_entry_pro);
        $entry = ldap_first_entry($link, $user_entry_pro);
        echo "entry :"."$entry"."\n";
        $userdn = ldap_get_dn($link, $entry);
        for ($i=0; $i < $info['count']; $i++) {
            $login = $info[$i]["uid"][0];
            $usersec = $info[$i]["userpassword"][0];
             echo "$login : ";
             echo "$userdn" . "\n";
            echo "$usersec";
        }
        break;

    case 'compte_vacataire':
        $affiliation = 'affiliate';
        $filter2 = "(&(sn=$nom)(givenName=$prenom)(eduPersonAffiliation=$affiliation))";
        $user_entry_pro = ldap_search($link, $racine, $filter2);
        $info = ldap_get_entries($link, $user_entry_pro);
        $entry = ldap_first_entry($link, $user_entry_pro);
        echo "entry :"."$entry"."\n";
        $userdn = ldap_get_dn($link, $entry);
        for ($i=0; $i < $info['count']; $i++) {
            $login = $info[$i]["uid"][0];
            $usersec = $info[$i]["userpassword"][0];
            echo "$login : ";
            echo "$userdn" . "\n";
            echo "$usersec";
        }
        break;

    case 'compte_etudiant':
        $affiliation = 'student';
        $filter3 = "(&(sn=$nom)(givenName=$prenom)(eduPersonAffiliation=$affiliation))";
        $user_entry_etu1 = ldap_search($link, $racine, $filter3);
        $info = ldap_get_entries($link, $user_entry_etu1);
        $entry = ldap_first_entry($link, $user_entry_etu1);
        echo "entry :"."$entry"."\n";
        $userdn = ldap_get_dn($link, $entry);
        for ($i=0; $i < $info['count']; $i++) {
            $login = $info[$i]["uid"][0];
            $usersec = $info[$i]["userpassword"][0];
            echo "$login : ";
            echo "$userdn"."\n";
            echo "$usersec";
        }
        break;   
      }
}

if ($eval == 1 && $account !== "") {
      $affiliation = 'student';
      $filter4 = "(&(sn=$nom)(givenName=$prenom)(supannMailPerso=$emailPerso))";
      $user_entry_etu_or_pro = ldap_search($link, $racine, $filter4);
      $info = ldap_get_entries($link, $user_entry_etu_or_pro);
      $entry = ldap_first_entry($link, $user_entry_etu_or_pro);
      echo "entry :"."$entry"."\n";
      $userdn = ldap_get_dn($link, $entry);
    for ($i=0; $i < $info['count']; $i++) {
        $login = $info[$i]["uid"][0];
        $usersec = $info[$i]["userpassword"][0];
        echo "$login : ";
        echo "$userdn"."\n";
        echo "$usersec";
    }
}

    $temp_pwd = passgen();
   // echo "$temp_pwd"."\n";
    $temp_ldap_pwd = password_hash($temp_pwd, PASSWORD_DEFAULT);
    echo "le nouveau mot de passe est : "."$temp_pwd"."\n";
    $temp_AD_pwd = "\"".$temp_pwd."\"";
    $userattr = [

      [ "attrib" => "userpassword",
      "modtype" => LDAP_MODIFY_BATCH_REMOVE,
      "values" => ["$usersec"],
      ],

      [ "attrib" => "userpassword",
      "modtype" => LDAP_MODIFY_BATCH_ADD,
       "values" => ["$temp_ldap_pwd"],
      ],  

    ];
    echo "$user_attr_passwd";
    $r = ldap_modify_batch($link, $userdn, $userattr);

    if (!$r) {
  
        $result = $messages['couldnotresetldappswd'];
        echo "$result";
        exit();
    } else {
        $result = $messages['passwordreset'];
        echo "$result";

    }


/*
    for ($i = 0; $i < strlen($temp_AD_pwd); $i++) {
  $PASSWORD_INITIALISE .= "{$AdPwdpreset{$i}}\000";
 }
$ADuserdata["unicodePwd"]=array($PASSWORD_INITIALISE);
ldap_mod_replace ($link, $userdn, array('userPassword' => "$temp_ldap_pwd"));

// On rechereche l' UID qui correspond au prénom, nom, et email perso  qui ont été renseigné par l'utilisateur.
$params = [
      'host' => $server,
      'port' => 389,
      'givenname' => $prenom,
      'sn' => $nom,
      'supannMailPerso' => $emailPerso
      'gudnumber' => $groupeID_2 
    ];*/
//  }
//}
 /*
  $found = print_r(find_ldap_user());
  echo "<script> alert('test:".$found."nbr:".$eval."'); </script>";
  $eval = ($found?'success':'failure');
  echo "<script> alert('$eval'); </script>";
  if ($eval == 'failure'){
  $result = $messages['usernotexist'];
  echo "$result";
  exit();
  } else {
    echo 'success'; 


  } /*else {
    // On rechereche l' UID qui correspond au prénom, nom, et email perso  qui ont été renseigné par l'utilisateur.

    $params = [
      'host' => $server,
      'port' => 389,
      'givenname' => $prenom,
      'sn' => $nom,
      'supannMailPerso' => $emailPerso
    ];

    $ldap new ldap($params);
    $resultats = $ldap->search('(&|(sn='$nom')($givenName ='$prenom')(supannMailPerso = '$emailPerso')'); 
}
     
  $motdepasse = passgen();
     if (!preg_match("#^[a-zO-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#",$mail)) {
            $passage_ligne ="\r\n";
          }
          else
          {
            $passage_ligne= "\n";
          }
  $header.="From:\"EXPEDITEUR\"<support-si@univ-guyane.fr>".$passage_ligne;
  $header.="Reply-to: \"RETOUR\"<support-si@univ-guyane.fr>".$passage_ligne;
  $header.="MIME-Version: 1.0".$passage_ligne;
  $header.="Content-type: multipart/alternative;".$passage_ligne."boundary=\$boundary\"".$passage_ligne;
  } 
}
*/
?>
