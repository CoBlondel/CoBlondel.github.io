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
	header("Location:../index.php?view=accueil");
	die("");
}

include_once("libs/modele.php");
include_once("libs/maLibUtils.php");
include_once("libs/maLibForms.php");

?>

<style>

.divimage
{
    float: left;
    text-align: center; 
    margin: 5px;
}

.titrejeu
{
  text-align: center;
  color: #dbdbdb;
  font-size: 30px;
  margin-left: 10%;
  font-family: Coves;
}

.images
  {
  float: left; 
  margin: 5px;
  margin-left: 50px;
  border: 1px solid #000000;
  text-align: center;
  height: 150px;
  width: 300px;
  margin-bottom: 90px;
  }

  .titre
  {
    text-align: center;
  text-shadow: 0px 0px 7px #CF000F;
  color: #FFA100;
  font-size: 70px;
  font-family: Coves;
  }

.Carouselstyle
{
  width: 70%;
  height: 30%;
  margin-left: 15%;
}

@font-face {
  font-family: "Coves";
  src:url("fonts/Coves Bold.otf") ;
}

</style>


 <!--    <p class="lead">Contenu </p> -->
  <div class="Carouselstyle">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="ressources/carousel/1.jpeg" alt="1">
      <div class="carousel-caption">
        <h3>Jeux</h3>
        <p>Venez uploader votre jeu free sur notre site !</p>
      </div>
    </div>

    <div class="item">
      <img src="ressources/carousel/2.jpeg" alt="2">
      <div class="carousel-caption">
        <h3>Univers</h3>
        <p>Tout l'univers du Free game !</p>
      </div>
    </div>

    <div class="item">
      <img src="ressources/carousel/3.jpeg" alt="3">
      <div class="carousel-caption">
        <h3>Amis</h3>
        <p>Venez défier vos amis !</p>
      </div>
    </div>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


<div class="container divider1">

 <div class="col-md-12">

 

</div><!-- /.col -->

</div><!-- /.container -->

</div>

<hr class="dashed">





<div >
<!-- <a href="http://localhost/MPM2/index.php?view=jeux" src="img/memory.png" style="width: 300px;" style="height: 300px;" > test </a> -->


</div>