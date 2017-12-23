<?php

//C'est la propriété php_self qui nous l'indique : 
// Quand on vient de index : 
// [PHP_SELF] => /chatISIG/index.php 
// Quand on vient directement par le répertoire templates
// [PHP_SELF] => /chatISIG/templates/accueil.php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte



if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=contact");
	die("");
}

    $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
    if (!valider("connecte","SESSION"))
    {
      header("Location:". $urlBase. "?view=accueil");
    }


?>

<br/>
<br/>
<br/>

<?php

if(isset($_POST['submit'])){
    $to = "cediediting@hotmail.fr"; 
    $from = $_POST['email']; 
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $subject = "Fée des jeux";
    $subject2 = "Copie du formulaire";
    $message = $first_name . " " . $last_name . " a ecrit :" . "\n\n" . $_POST['message'];
    $message2 = "Ici c'est une copie du message " . $first_name . "\n\n" . $_POST['message'];

    $headers = "From:" . $from;
    $headers2 = "From:" . $to;
    mail($to,$subject,$message,$headers);
    mail($from,$subject2,$message2,$headers2); // sends a copy of the message to the sender
    echo "Mail envoyé , merci " . $first_name . ", nous vous contacterons ulterieurement";
    }
?>

<!DOCTYPE html>
<head>
<title>Formulaire :</title>
</head>
<body>

<form action="" method="post">
Prénom : <input class="form-control input-md" type="text" name="first_name"><br>
Nom : <input class="form-control input-md" type="text" name="last_name"><br>
Email : <input  class="form-control input-md" type="text" name="email"><br>
Message :<br><textarea class="form-control" rows="5" name="message" cols="30"></textarea><br>
<input class="btn btn-default" type="submit" name="submit" value="Envoyer">
</form>

</body>
</html> 