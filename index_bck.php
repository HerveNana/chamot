<?php
session_start();

$username = "";
$prenom = "";
$nom = "";
$email = "";
$password = "";
$newPassword1 = "";
$newPassword2 = "";

?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic"> -->
    <link rel="stylesheet" href="assets/css/Login-Center.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/functions.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!--<script src="assets/js/loginswitch.min.js"></script>-->


</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="#"><strong>CHAMOT</strong> </a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav navbar-right"></ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active" role="presentation">
                        <a href="http://univ-guyane.fr"> <img src="assets/img/logo-ug-complet2.jpg" width="150"></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="credential">
   <!--    <div class="jumbotron"> -->
            <div class="container">
                <div class="row">
                    <div class="col-md6 col-md-offset-3">
                        <div class="panel panel-login">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form id="login-form" action="#" method="post" role="form" style="display: block; ">
   					  <h2>MODIFIER SON MOT DE PASSE</h2>
                                        <div class="form-group">
                                            <input type="text" id="username" class="form-control" name="username" tabindex="1" placeholder="Login" >
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="oldPassword" class="form-control" name="password" tabindex="2" placeholder="Ancien mot de passe">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="newPassword1" class="form-control" name="newPassword1" tabindex="3" placeholder="Nouveau mot de passe">
                                          </div>
                                          <div class="form-group">
                                          <input type="password" id="newPassword2" class="form-control" name="newPassword2" tabindex="4" placeholder="Confirmation du nouveau mot de passe">
                                          </div>
                                   <!--     <div class="col-xs-6 form-group pull-left checkbox">
                                            <input type="checkbox" id="checkbox1" name="remember">
    						<label for="checkbox1">Remember Me</label>
					</div>  -->
                             <!--       <div class="col-xs-6 form-group pull-right">  -->
                        <div class="col-sm-6 col-sm-offset-3">
  				<!--		<input type="submit" name="login-submit" id="login-submit" tabindex="5" class="form-control btn btn-login" value="Valider"> -->
                <button name="login-submit" id="login-submit" tabindex="5" class="form-control btn btn-login">Valider</button>
                  			    </div>
              					</form>
              <form id="register-form" action="#" method="post" role="form" style="display: none;">
                <h2>OBTENIR UN MOT DE PASSE PROVISOIRE</h2>
                  <div class="form-group">
                    <input type="text" name="prenom" id="prenom" tabindex="6" class="form-control" placeholder="Prénom (tel qu'orthographié sur votre carte d'étudiant)" value="">
                  </div>

                  <div class="form-group">
                    <input type="text" name="Nom" id="Nom" tabindex="7" class="form-control" placeholder="Nom (tel qu'orthographié sur votre carte d'étudiant)" value="">
                  </div>

                  <div class="form-group">
                    <input type="email" name="email" id="email" tabindex="8" class="form-control" placeholder="Adresse email personnelle (fournie lors de l'inscription)" value="">
                  </div>

            <!--      <div class="form-group">
                    <input type="pasolsword" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
                  </div>
                -->

                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-6 col-sm-offset-3">
                         <button name="password-request" id="password-request" tabindex="5" class="form-control btn btn-login">Envoyer</button>
                      <!-- - <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Envoyer"> -->
                      </div>
                    </div>
                  </div>
              </form>
            </div>

          </div>
        </div>
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6 tabs">
                <button name="loginknown" class="active" id="login-form-link"><div class="login">Je connais mon mot de passe et je le modifie</div></button>

            </div>
            <div class="col-xs-6 tabs">
              <button name="loginasked" id="register-form-link"><div class="login">Je demande un mot de passe provisoire </div></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md6 col-md-offset-3">
                <div class="panel panel-login" id="panel-password-warning" style="display: block;">
                    <div  class="panel-body" >
                      <div class="active">
                        <div class="row">
                            <div class="col-lg-12">
                             <p id="messages"><span class ="tippedMessages">veuillez renseigner votre login</span></p>
                             <p id="messages1"><span class="tippedMessages">veuillez renseigner votre ancien mot de passe</span></p>
                             <p id="messages2"><span class="tippedMessages">nouveau mot de passe 8 caractères minimum</span></p>
                             <p id="messages3"><span class="tippedMessages">nouveaux mots de passe identiques</span></p>
                             <p id="messages4"></p>
                             <p id="messages5"></p>
    </div>
  </div>
</div>
          <div id="panel-personal-info-warning" class="panel panel-login" style="display: block;">
                    <div  class="panel-body">
                    <div class="active">
                        <div class="row">
                            <div class="col-lg-12">
                             <p id="Message"><span class ="tippedMessages">veuillez renseigner votre prénom</span></p>
                             <p id="Message1"><span class="tippedMessages">veuillez renseigner votre nom</span></p>
                             <p id="Message2"><span class="tippedMessages">Veuillez indquer une adresse email valide</span></p>
                             
  </div>
</div>
</div>
</div>

<?php

$_SESSION["username"] = $username;
$_SESSION["prenom"] = $prenom;
$_SESSION["nom"] = $nom;
$_SESSION["email"] = $email;
$_SESSION["oldPassword"] = $password;
$_SESSION["newPassword1"] = $newPassword1;
$_SESSION["newPassword2"] = $newPassword2;

?>
<!--
$('#logSin-submit').click(function() {

  if ($passwd1 !== $passwd2) {
    document.getElementById("messages").innerHTML = "les mot de passe ne sont pas identiques";
   /* console.log("les mots de passe ne sont pas identiques"); */
  };
});

      $.post(
        'connexion.php',
        {
          login : $("#login-submit").val(),

          password : $("#oldPassword").val(),

          newPassword1 : $("#newPassword1").val(),

          newPassword2 : $("#newPassword2").val()

        },

        function(data){
        },
/*'text' */

        };

      });

</script>
-->
</body>

</html>
