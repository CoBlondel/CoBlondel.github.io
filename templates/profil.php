<?php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
  header("Location:../index.php?view=profil&" . $_SERVER["QUERY_STRING"]);
  
  die("");
}

include_once("libs/modele.php");
include_once("libs/maLibUtils.php");
include_once("libs/maLibForms.php");

?>


<?php


$informationsUser = recupererAvecId(valider("id_User", "SESSION")); // recupere les donnée dans la bdd de l'user
$cheminPhoto = "ressources/profil/img_" . valider("id_User", "SESSION") . '.*'; // chemin de la photo sans l'extension
// consoleDebug($cheminPhoto);
// $extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);


// foreach (glob("*.jpg") as $filename) {
//     echo "$filename occupe " . filesize($filename) . "\n";
// }

// if (fnmatch("*.jpg", $filename)) {
//   echo "des formes de gris ...";
// }


echo "<br>" ;
echo "<br>" ;

 // print_r($informationsUser[0]["passwordUser"]);
echo "<div class=\"container\">";
  echo "<div class=\"col-md-4 \">";

    
      if(imageExist($cheminPhoto))
        // echo imageExist($cheminPhoto;
         echo " <img src=" . imageExist($cheminPhoto) . "  class=\"imgProfil img-thumbnail\">";

  echo "</div>";

  echo "<div class=\" col-md-8 \">";
    echo "<h4> ma date d'inscription : </h4>" . $informationsUser[0]["dateSignIn_User"];
    echo "<h4> mon Pseudo : </h4>" . $informationsUser[0]["pseudo_User"];
    echo "<h4> mon adresse mail : </h4>" . $informationsUser[0]["mail_User"];
    // echo "<h4> mon niveau : </h4>" . $informationsUser[0]["niveauUser"];


  echo "</div>";
echo "</div>";





?>

<h1 class="page-header titre_centre_margintop">Mettre a jour les données de mon profil</h1>
<!-- <div class="page-header titre"> Inscription </div> -->


<form class="form-horizontal"  action="controleur.php" method="POST" enctype="multipart/form-data">
<fieldset>

<!-- Form Name -->
<!-- <legend>Inscriptions</legend> -->


<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pseudoUser">Votre nouveau pseudo</label>  
  <div class="col-md-5">
  <input id="pseudoUser" name="pseudoUser" placeholder="ecrivez ici" class="form-control input-md"  type="text">
  <span class="help-block">*celui ci sera visible en ligne et doit faire moins de 50 caracteres</span>  
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordUser">Votre mot de passe actuel</label>
  <div class="col-md-5">
    <input id="passwordUser" name="passwordActuel" placeholder="ecrivez ici" class="form-control input-md"  type="password">
    <span class="help-block">*moins de 50 caracteres</span>
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordUser">Votre nouveau mot de passe</label>
  <div class="col-md-5">
    <input id="passwordUser" name="passwordNouveau1" placeholder="ecrivez ici" class="form-control input-md"  type="password">
    <span class="help-block">*moins de 50 caracteres</span>
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordUser2">Verifier le nouveau mot de passe</label>
  <div class="col-md-5">
    <input id="passwordUser2" name="passwordNouveau2" placeholder="ecrivez ici" class="form-control input-md"  type="password">
    <span class="help-block">*moins de 50 caracteres</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Mail">changer d'adresse mail</label>  
  <div class="col-md-6">
  <input id="Mail" name="Mail" placeholder="ecrivez ici" class="form-control input-md"  type="text">
  <span class="help-block">*moins de 100 caracteres</span>  
  </div>
</div>



<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="btn-submit-form"></label>
  <div class="col-md-4">
    <input  type="submit" id="submitProfil" class="btn btn-primary" value="Changer">
    <input type="hidden" name="action" value="submitModifProfil">
  </div>
</div>
</fieldset>
</form>



<form class="form-horizontal"  action="controleur.php" method="POST" enctype="multipart/form-data">

<!-- File Button --> 
<div class="form-group">
  <label class="col-md-4 control-label" for="filebutton">Ajouter une photo</label>
  <div class="col-md-4">
    <input id="filebuttonPhotoProfil" name="filebuttonPhotoProfil" class="input-file" type="file">
  <span class="help-block">*Attention la taille sera définie en 300 x 300</span>  
      <input type="hidden" name="MAX_FILE_SIZE" value="10000000"/>
    <!-- <input type="hidden" name="nomRep" value="testimage"/> -->

  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="btn-submit-form"></label>
  <div class="col-md-4">
    <input  type="submit" id="submitPhoto" class="btn btn-primary" value="Changer">
    <input type="hidden" name="action" value="submitPhotoProfil">
  </div>
</div>

</form>

<?php 
  if(isVip(valider("id_User","SESSION"))==0)
  {
    echo "<form class=\"form-horizontal\"  action=\"controleur.php\" method=\"POST\" enctype=\"multipart/form-data\">";
    echo  "<div class=\"form-group\">";
    echo    "<label class=\"col-md-4 control-label\">Soucrire à l'offre preumium ?</label>";
    echo    "<div class=\"col-md-4\">";
    echo      "<input  type=\"submit\" id=\"submitOffre\" class=\"btn btn-primary\" value=\"Souscrire\">";
    echo      "<input type=\"hidden\" name=\"action\" value=\"submitPreumium\">";
    echo    "</div>";
    echo  "</div>";
    echo "</form>";
  } else {
    echo "<form class=\"form-horizontal\"  action=\"controleur.php\" method=\"POST\" enctype=\"multipart/form-data\">";
    echo  "<div class=\"form-group\">";
    echo    "<label class=\"col-md-4 control-label\">Se desabonner de l'offre preumium ?</label>";
    echo    "<div class=\"col-md-4\">";
    echo      "<input  type=\"submit\" id=\"submitOffre\" class=\"btn btn-primary\" value=\"Desabonner\">";
    echo      "<input type=\"hidden\" name=\"action\" value=\"submitStopPreumium\">";
    echo    "</div>";
    echo  "</div>";
    echo "</form>";
  }
?>