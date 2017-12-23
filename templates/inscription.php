<?php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
  header("Location:../index.php?view=login");
  die("");
}

// Chargement eventuel des données en cookies

?>

<!-- <div class="page-header">
  <h1>Inscriptions</h1>
</div>

<p class="lead">

 <form role="form" action="controleur.php">
  <div class="form-group">
    <label for="email">Login</label>
    <input type="text" class="form-control" id="email" name="login" >
  </div>
  <div class="form-group">
    <label for="pwd">Passe</label>
    <input type="password" class="form-control" id="pwd" name="passe">
  </div>
  <div class="checkbox">
    <label><input type="checkbox" name="remember" >Se souvenir de moi</label>
  </div>
  <button type="submit" name="action" value="Inscription" class="btn btn-default">Inscription</button>
</form>


</p> -->



<h1 class="page-header">Inscription</h1>
<!-- <div class="page-header titre"> Inscription </div> -->


<form class="form-horizontal" action="controleur.php" method="POST">
<fieldset>

<!-- Form Name -->
<!-- <legend>Inscriptions</legend> -->

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="ndcUser">Nom de compte</label>  
  <div class="col-md-5">
  <input id="ndcUser" name="ndcUser" placeholder="ecrivez ici" class="form-control input-md" required="" type="text">
  <span class="help-block">*moins de 50 caracteres</span>  
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pseudoUser">Votre pseudo</label>  
  <div class="col-md-5">
  <input id="pseudoUser" name="pseudoUser" placeholder="ecrivez ici" class="form-control input-md" required="" type="text">
  <span class="help-block">*celui ci sera visible en ligne et doit faire moins de 50 caracteres</span>  
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordUser">Mot de passe</label>
  <div class="col-md-5">
    <input id="passwordUser" name="passwordUser" placeholder="ecrivez ici" class="form-control input-md" required="" type="password">
    <span class="help-block">*moins de 50 caracteres</span>
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordUser2">Verifier Mot de passe</label>
  <div class="col-md-5">
    <input id="passwordUser2" name="passwordUser2" placeholder="ecrivez ici" class="form-control input-md" required="" type="password">
    <span class="help-block">*moins de 50 caracteres</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Mail">Votre adresse mail</label>  
  <div class="col-md-6">
  <input id="Mail" name="Mail" placeholder="ecrivez ici" class="form-control input-md" required="" type="text">
  <span class="help-block">*moins de 100 caracteres</span>  
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="btn-submit-form"></label>
  <div class="col-md-4">
    <input  type="submit" id="submitInscription" class="btn btn-primary" value="Inscription">
    <input type="hidden" name="action" value="submitInscription">
  </div>
</div>

</fieldset>
</form>
