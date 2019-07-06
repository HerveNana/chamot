<?php 

function isLdapPresent() {
$ds=ldap_connect("ldap://$server");
if ($ds != TRUE) {
  $resultTpr = $messages["noldapserver"];
  exit -1;
} elseif ($sr=ldap_bind($ds) !=TRUE) {
  $resultTpr = $messages["noserverconnection"];
  exit -1;
}
echo $resultTpr;



 function checkUserPassReqDataForm() {
 	$nomErr = $prenomErr =$emailErr = $result = "";
 	if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["nom"])) {
    $result = $nomErr = "Veuillez renseigner votre nom";
  } else {
    $nom = test_input($_POST["$nom"]);
  }
  if (empty($_POST["prenom"])) {
  	$result = $prenomErr = "Veuillez renseigner votre prénom";
  } else{
  	$prenom = test_input($_POST["$prenom"]);
  }
  
  if (empty($_POST["email"])) {
    $result = $emailErr = "Veuillez renseigner votre adresse email personelle";
  } else {
    $email = test_input($_POST["email"]);
  
	}
 }
}

function make_ssha_password($password) {
    mt_srand((double)microtime()*1000000);
    $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
    $hash = "{SSHA}" . base64_encode(pack("H*", sha1($password . $salt)) . $salt);
    return $hash;
}

# Create SHA password
function make_sha_password($password) {
    $hash = "{SHA}" . base64_encode(pack("H*", sha1($password)));
    return $hash;
}

# Create SMD5 password
function make_smd5_password($password) {
    mt_srand((double)microtime()*1000000);
    $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
    $hash = "{SMD5}" . base64_encode(pack("H*", md5($password . $salt)) . $salt);
    return $hash;
}

# Create MD5 password
function make_md5_password($password) {
    $hash = "{MD5}" . base64_encode(pack("H*", md5($password)));
    return $hash;
}

# Create CRYPT password
function make_crypt_password($password, $hash_options) {

    // Generate salt
    $possible = '0123456789'.
    'abcdefghijklmnopqrstuvwxyz'.
    'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
    './@-_*;:ù|+[()]?!&~';
    $salt = "";

    mt_srand((double)microtime() * 1000000);

    while( strlen( $salt ) < 2 ) {
        $salt .= substr( $possible, ( rand() % strlen( $possible ) ), 1 );
    }

    if ( isset($hash_options['crypt_salt_prefix']) ) {
        $salt = $hash_options['crypt_salt_prefix'] . $salt;
    }

    $hash = '{CRYPT}' . crypt( $password,  $salt);
    return $hash;
}

# Create MD4 password (Microsoft NT password format)
function make_md4_password($password) {
    if (function_exists('hash')) {
        $hash = strtoupper( hash( "md4", iconv( "UTF-8", "UTF-16LE", $password ) ) );
    } else {
        $hash = strtoupper( bin2hex( mhash( MHASH_MD4, iconv( "UTF-8", "UTF-16LE", $password ) ) ) );
    }
    return $hash;
}

# Create AD password (Microsoft Active Directory password format)
function make_ad_password($password) {
    $password = "\"" . $password . "\"";
    $len = strlen(utf8_decode($password));
    $adpassword = "";
    for ($i = 0; $i < $len; $i++){
        $adpassword .= "{$password{$i}}\000";
    }
    return $adpassword;
}

function find_ldap_user_personal_email() {
  $filter ="(&(uid=$username)(supannmailperso=$mail))";
  if (($username !="") and ($mail !="")) {
    include "ldap_bind.php";
$result = ldap_search($link, $racine, $filter);

  }
}

function find_ldap_user() {
  include "ldap_bind.php";
  $filter ="(&(sn=$nom)(givenName=$prenom))";
 $fu = ldap_search($link,$racine,$filter);
 return $fu;
  }

/*
function Cryptage($MDP, $Clef){

 $possible = '0123456789'.
    'abcdefghijklmnopqrstuvwxyz'.
    'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
    './@-_*;:ù|+[()]?!&~';
    $salt = "";



  $LClef = strlen($Clef);
  $LMDP = strlen($MDP);         
  if ($LClef < $LMDP){
        
    // Code qui rallonge la clef
  
  }
        
  elseif ($LClef > $LMDP){

    // Code qui raccourcit la clef
        
  }
      
  return $MDP ^ $Clef; // La fonction envoie le texte crypté
  */
      
}

?>