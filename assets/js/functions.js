// On commence par la decclaraton de fonctions qui vont être réutilisées;


    

// Gestion des évènements

$(document).ready(function() { 
       //fonction pour la gestion des onglets du formulaire de login    
  $('#login-form-link').click(function(e) {
    $("#login-form").delay(100).fadeIn(100);
    $('#panel-password-warning').fadeIn(100);
    $('#panel-personal-info-warning').fadeOut(100);
    $("#register-form").fadeOut(100);
    $('#register-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
  });
  $('#register-form-link').click(function(e) {
    $("#register-form").delay(100).fadeIn(100);
    $("#login-form").fadeOut(100);
    $('#panel-password-warning').fadeOut(100);
    $('#panel-personal-info-warning').fadeIn(100);
    $('#login-form-link').removeClass('active');
    $('#panel-password-warning').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
  });

function validateEmail(email){
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var validation = emailReg.test(email);
    if (!validation) {
        return false;
    } else {
        return true;
    }
}
/*
function showUserAccountChoice(e) {
  $('#uiduser').delay(100).fadeIn(100);
  $(this).css({
    display : 'block'
    )};
}
*/
var username = $('#username').val();
var passwd = $('#oldPassword').val();
var passwd1 = $('#newPassword1').val();
var passwd2 = $('#newPassword2').val();
var prenom = $('#prenom').val();
var nom = $('#nom').val();
var mail = $('#email').val();


$('#username').keyup(function(){
  if ($(this).val().length =="" ){
    $(this).css({
      borderColor :'red',
      color :'red'
    });
    $('#messages').html("<p span class ='loginMissingAlert'>Veuillez renseigner votre login</span></P>");
  }
  else{
    $(this).css({
      borderColor :'green',
      color :'green'
    });
    $('#messages').html("<p>Veuillez renseigner votre login <span class ='glyphicon glyphicon-ok'></span></p>")
  }
});

$('#oldPassword').keyup(function(){
if ($(this).val().length == "") { // Si la chaie de caractère est egale à 0
$(this).css({
  borderColor :'red',
  color :'red'
});
$('#messages1').html("<p span class = 'oldPasswordMissingAlert'>Veuillez renseigner le champs ancien mot de passe.</p>");

}
else{
  $(this).css({
    borderColor :'green',
    color:'green'
  });
  $('#messages1').html("<p span class= 'oldPasswordPresentAlert'>Veuillez renseigner le champs ancien mot de passe <span class ='glyphicon glyphicon-ok'></span></p>")
}

});

$('#newPassword1').keyup(function(){
  if($(this).val().length < 8){ // si la cahîne de caractères est inférieure à 8
  $(this).css({ // on rend le champ rouge
  borderColor : 'red',
  color : 'red'
});
  $('#messages2').html("<p span class ='passwordLenthAlert'>Nouveau mot de passe: 8 caractères minimum</p>"); //dans le cas contraire on modifie les attributs du message affiché
}
else{
  $(this).css({
    borderColor : 'green',
    color : 'green'
  });
  $('#messages2').html("<p>Nouveau mot de passe: 8 caractères minimum<span class ='glyphicon glyphicon-ok'></span></p>"); //dans le cas contraire on modifie les attributs du message affiché
}  

});

$('#newPassword1').keyup(function(){
  if($(this).val() != $('#newPassword2').val() || $(this).val().length == "") {
    $('#messages3').html("<p span class ='passwordLenthAlert'>Nouveau mot de passe identiques</p>");
  }
    else{
    $('#messages3').html("<p>Nouveaux mots de passe identiques <span class ='glyphicon glyphicon-ok'></span></p>"); //dans le cas contraire on modifie les attributs du message affiché
  }
  });

$('#newPassword2').keyup(function(){
 if ($(this).val().length == "") {  // si la cahîne de caractères est vide
  $(this ).css({ //on rend le champs rouge
    borderColor  : 'red',
    color : 'red' 
  });
}
  else if($(this).val() != $('#newPassword1').val()) {
  $(this).css({ // on rend le champ rouge
  borderColor : 'red',
  color : 'red'
});
    $('#messages3').html("<p span class ='passwordLenthAlert'>Nouveaux mot de passe identiques</p>");  // On affiche le message d'alerte
  }
    else{
       $(this).css({
    borderColor : 'green',
    color : 'green'
  });
    $('#messages3').html("<p>Nouveaux mots de passe identiques <span class ='glyphicon glyphicon-ok'></span></p>"); //dans le cas contraire on modifie les attributs du message affiché
  }
  });

/*
$('#login-submit').click(function() {
if ($('#username').val().length == "" || $('#oldPassword').val().length == "" || $('#newPassword1').val().length == "" 
  || $('#newPassword1').val().length < 8 || $('#newPassword2').val().length == "" || $('#newPassword1').val() != $('#newPassword2').val() ) {
  alert("Veuillez renseigner tous les champs recquis");
} else {
  alert("everything ok");
}
*/

$('#login-submit').click(function() {
if ($('#username').val().length == "" || $('#oldPassword').val().length == "" || $('#newPassword1').val().length == "" 
  || $('#newPassword1').val().length < 8 || $('#newPassword2').val().length == "") {
  alert("Veuillez renseigner tous les champs recquis");
} else if ($('#newPassword1').val() != $('#newPassword2').val()) {
  alert("Les mots de passe renseignés ne sont pas identiques");
} else {
  alert("everything ok");
}

});

$('#prenom').keyup(function() {
  if ($(this).val().length == "") { // Si la chaie de caractère est egale à 0 on rend le cahmps rouge
$(this).css({
  borderColor :'red',
  color :'red'
});

$('#Message').html("<p span class ='givenNamePresentAlert'>Veuillez renseigner votre prénom</p>"); //et on affiche le message d'alerte
  } else {
     $(this).css({
    borderColor : 'green',
    color : 'green'
  });
    $('#Message').html("<p>Veuillez renseigner votre prénom <span class ='glyphicon glyphicon-ok'</span></p>");
  }
});

$('#nom').keyup(function() {
  if ($(this).val().length == "") { // Si la chaie de caractère est egale à 0 on rend le cahmps rouge
$(this).css({
  borderColor :'red',
  color :'red'
});
$('#Message1').html("<p span class ='namePresentAlert'>Veuillez renseigner votre nom</p>"); //et on affiche le message d'alerte

  } else {
     $(this).css({
    borderColor : 'green',
    color : 'green'
  });
    $('#Message1').html("<p>Veuillez renseigner votre nom <span class ='glyphicon glyphicon-ok'</span></p>"); //dans le cas contraire on modifie les attributs du message affiché
  } 
});

$('#mail').keyup(function() {
  if ($(this).val().length == "") { // Si la chaie de caractère est egale à 0 on rend le cahmps rouge
$(this).css({
  borderColor :'red',
  color :'red'
});
$('#Message2').html("<p span class ='emailPresentAlert'>Veuillez indiquer une adresse email valide</p>"); //et on affiche le message d'alerte

  } else {
     $(this).css({
    borderColor : 'green',
    color : 'green'
  });
    $ ('#Message2').html("<p>Veuillez indiquer une adresse email valide <span class ='glyphicon glyphicon-ok'</span></p>"); //dans le cas contraire on modifie les attributs du message affiché
  }

});

$('#mail').keyup(function() {
  if(!validateEmail($(this).val())) {
  $(this).css({
  borderColor :'red',
  color :'red'
});
  $('#Message2').html("<p span class ='emailPresentAlert'>Veuillez indiquer une adresse email valide</p>");
  } else {
     $(this).css({
    borderColor : 'green',
    color : 'green'
  });
    $('#Message2').html("<p>Veuillez indiquer une adresse email valide <span class ='glyphicon glyphicon-ok'</span></p>"); //dans le cas contraire on modifie les attributs du message affiché
  } 
});

$('#password-request').click(function(e) {
      e.preventDefault();
    /*  var prenom = $('#prenom').val();
      var nom = $('#nom').val();
      var mail = $('#mail').val(); */
      var donnees = $("#register-form").serialize();
        $.ajax({
        url: 'connexion.php',
        type: 'post',
        data : donnees,
        dataType: 'text' ,
        success : function(data) {
          if(data == "success") {
            $('#Message3').html("<p>tout se passe bien</p>");
          } else {
            $('#Message3').css("color", "red").html(data);
            exit();
          }
        }
      });
    });
   });
  