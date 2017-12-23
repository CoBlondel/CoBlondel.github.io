
<script type="text/javascript">

	function recupCheckBox() {
	var valeurs = [];
	$('input:checked').each(function() {
  		valeurs.push($(this).val()); // on recupere les noms des fichiers cochés
	});
	console.log(valeurs);
	var tab=valeurs.join('|'); // va permettre de recuperer chaque fichier cocher grace a la ft explode de php par la suite
	var nomR=recupGET("nomRep"); // permet de recuperer le repertoire sur lequel on est 
	document.location.href="controleur.php?action=download&nomRep="+nomR+"&tab="+tab;
}

</script>

<?php
if (isset($_REQUEST["nomRep"]))  $nomRep = $_REQUEST["nomRep"];
else $nomRep = false;
?>

<h1>Gestion des r&eacute;pertoires </h1>

<form action="controleur.php">
	<label>Cr&eacute;er un nouveau r&eacute;pertoire : </label>
	<input type="text" name="nomRep"/>
	<input type="submit" name="action" value="Creer" />
</form>

<form action="controleur.php">
	<label>Choisir un r&eacute;pertoire : </label>
	<select name="nomRep">
	<?php
		$rep = opendir("./Upload/"); // ouverture du repertoire 
		while ( $fichier = readdir($rep))
		{
			// On &eacute;limine le r&eacute;sultat '.' (r&eacute;pertoire courant) 
			// et '..' (r&eacute;pertoire parent)

			if (($fichier!=".") && ($fichier!=".."))
			{
				// Pour &eacute;liminer les autres fichiers du menu d&eacute;roulant, 
				// on dispose de la fonction 'is_dir'
				if (is_dir("./Upload/" . $fichier))
					printf("<option value=\"$fichier\">$fichier</option>");
			}
		}
		closedir($rep);
	?>
	</select>
	<input type="submit" value="Explorer"> <input type="submit" name="action" value="Supprimer Repertoire">
</form>

<?php
	if (!$nomRep)  die("Choisissez un r&eacute;pertoire"); 
	// interrompt imm&eacute;diatement l'ex&eacute;cution du code php
?>

<hr />
<h2> Contenu du r&eacute;pertoire '<?php echo$_GET["nomRep"]?>' </h2>


<form enctype="multipart/form-data" method="post" action="controleur.php">
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000">
	<input type="hidden" name="nomRep" value="<?php echo $nomRep; ?>">
	<label>Ajouter un fichier image : </label>
	<input type="file" name="FileToUpload">
	<input type="submit" value="Uploader" name="action">
</form>

<?php
		if(($id_User = valider("id_User","SESSION"))&&(isVip($id_User)==1))
		{

			echo "<input type=\"button\" class=\"btn_renommer\" value=\"Telecharger\" onclick=\"recupCheckBox()\" />\n";
		}

	$numImage = 0;
	$rep = opendir("./Upload/$nomRep");
	$nomRepSave = $nomRep;
	$nomRep = "$nomRep/copyright";
	if($id_User = valider("id_User","SESSION"))
	{
		if(isVip($id_User)==1)
		{
			$nomRep = $nomRepSave;
		}
	}
    // ouverture du repertoire 


	while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce r&eacute;pertoire
	{
	
		if (($fichier!=".") && ($fichier!=".."))
		{
			// Pour éliminer les autres répertoires du menu déroulant, 
			// on dispose de la fonction 'is_dir'
			if (!is_dir("./Upload/$nomRep/" .$fichier))
			{
				// Un fichier... est-ce une image ?
				// On ne liste que les images ... 
				$formats = ".jpeg.jpg.gif.png";
				if (strstr($formats,strrchr($fichier,"."))) 
				{
					$numImage++;
					// echo $fichier;
					$dataImg = getimagesize("./Upload/$nomRep/$fichier"); 

					// A compléter : récupérer le type d'une image, et sa taille 
					$width= $dataImg[0];
					$height= $dataImg[1]; 
					$type= substr($dataImg["mime"],6);

					// A compl&eacute;ter : On cherche si une miniature existe pour l'afficher...
					// Si non, on cr&eacute;e &eacute;ventuellement le r&eacute;pertoire des miniatures, 
					// et la miniature que l'on place dans ce sous-r&eacute;pertoire				

					echo "<div class=\"mini\">\n";
                    if(valider("connecte","SESSION")==1)
                    {
                        echo "<a target=\"_blank\" href=\"./Upload/$nomRep/$fichier\"><img src=\"./Upload/$nomRep/thumbs/$fichier\"/></a>\n";

                    }
                    else
                    {
                        echo " <img src=\"./Upload/$nomRep/thumbs/$fichier\"/>\n";
                    }
					echo "<div>$fichier \n";			
					if(isVip($id_User)==1)
					{
						echo "<a href=\"controleur.php?nomRep=./Upload/$nomRep&fichier=$fichier&action=Supprimer\" >Supp</a>\n";
					}
					echo "<br />($width * $height $type)\n";
					echo "<br />\n";

					if(isVip($id_User)==1)
					{
						echo "<form action='controleur.php'>\n";
						echo "<input type=\"hidden\" name=\"fichier\" value=\"$fichier\" />\n";
						echo "<input type=\"hidden\" name=\"nomRep\" value=\"$nomRep\" />\n";
						echo "<input type=\"hidden\" name=\"action\" value=\"Renommer\" />\n";
						echo "<input type=\"text\" class=\"renommer\" name=\"nomFichier\" value=\"$fichier\" onclick=\"this.select();\" />\n";
						echo "<input type=\"submit\" class=\"btn_renommer\" value=\">\" />\n";

						echo "</form>\n";


					}

					echo "</div></div>\n";

					// A compl&eacute;ter : appeler echo "<br style=\"clear:left;\" />"; si on a affich&eacute; 5 images sur la ligne actuelle
					
					if (($numImage%5) ==0)
					echo "<br style=\"clear:left;\" />";
					if(($id_User = valider("id_User","SESSION"))&&(isVip($id_User)==1))
					{
						echo "<input type=\"checkbox\" class=\"cbox\" name=\"nomFichier\" value=\"$fichier\">Telecharger ?";
					}
				}
			}
		}

	
	}
	closedir($rep);

	// A compl&eacute;ter : afficher un message lorsque le r&eacute;pertoire est vide
	if ($numImage==0) echo "<h3>Aucune image dans le r&eacute;pertoire</h3>";

?>

