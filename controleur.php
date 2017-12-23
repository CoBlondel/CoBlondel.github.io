<?php
session_start();

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 

	$addArgs = "";
	if (isset($_REQUEST["nomRep"]))  $nomRep = $_REQUEST["nomRep"];
	else $nomRep = false;
	$action = valider("action");
	if("$action==Explorer"){
	$addArgs = "?view=galerie&nomRep=$nomRep";
	}
	$cost = 9; // cette variable est utilisée dans le hachage de mots de passe et varie généralement entre 8 et 10 , il permet d'avoir un temps de génération de hachage optimal pour la page
	// on calcule le $cost qui nous convient avec cette fonction
	// le cost optimal est <= a 50 millisecondes

	// $timeTarget = 0.05; // 50 millisecondes

// $cost = 8;
// do {
//     $cost++;
//     $start = microtime(true);
//     password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
//     $end = microtime(true);
// } while (($end - $start) < $timeTarget);

// echo "Valeur de 'cost' la plus appropriée : " . $cost . "\n";

	if ($action = valider("action"))
	{
		ob_start ();
		echo "Action = '$action' <br />";
		// ATTENTION : le codage des caractères peut poser PB si on utilise des actions comportant des accents... 
		// A EVITER si on ne maitrise pas ce type de problématiques

		/* TODO: A REVOIR !!
		// Dans tous les cas, il faut etre logue... 
		// Sauf si on veut se connecter (action == Connexion)

		if ($action != "Connexion") 
			securiser("login");
		*/

		// Un paramètre action a été soumis, on fait le boulot...
		switch($action)
		{
			
			
			// Connexion //////////////////////////////////////////////////
			case 'Connexion' :
				// On verifie la presence des champs login et passe
				if ($login = valider("login"))
					if ($passe = valider("passe"))
					{
						// echo " le login et le passe sont bon ";
					// On verifie l'utilisateur, 
					// et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
						 // echo password_hash($passe, PASSWORD_BCRYPT, ["cost" => $cost]);
						 // echo "<br/>";
						 // echo password_hash($passe, PASSWORD_BCRYPT, ["cost" => $cost]);

			 			// echo verifUser($login,$passe);
						if (verifUser($login,$passe))
						{
							// echo "on rentre dans la boucle verifUser";
						// tout s'est bien passé, doit-on se souvenir de la personne ? 
							if (valider("remember")) 
							{
								setcookie("login",$login , time()+60*60*24*30);
								setcookie("passe",$passe, time()+60*60*24*30);
								setcookie("remember",true, time()+60*60*24*30);
							}
							else 
							{
							setcookie("login","", time()-3600);
							setcookie("passe","", time()-3600);
							setcookie("remember",false, time()-3600);
							}

						}
					}

					// die("");
				// On redirigera vers la page index automatiquement
			break;

			case 'Logout' :
				session_destroy();
			break;

			break;
			case 'submitInscription' : 
				// password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
				$ndc = valider("ndcUser","POST");
				$pseudo = valider("pseudoUser","POST");
				$passwd1 = valider("passwordUser","POST");
				$password1 = password_hash($passwd1, PASSWORD_BCRYPT, ["cost" => $cost]);
				$passwd2 = valider("passwordUser2","POST");
				$password2 =password_hash($passwd2, PASSWORD_BCRYPT, ["cost" => $cost]);
				consoleDebug(password_verify($passwd1,$password1));

				$mail = valider("Mail","POST");
				$creeUser = "";


				if( !$ndc || !$pseudo || !$passwd1 || !$passwd2 )
				{
					$addArgs ="?view=inscription";
				}
				if( $passwd1 !== $passwd2 || password_verify($passwd1,$password1) != TRUE || password_verify($passwd2,$password2) != TRUE )
					{
						echo "il y a un probleme de mot de passe";
						$addArgs ="?view=inscription";
					}
				else
				{
					if (  !ndcExistant($ndc) )
					{
						if (  !pseudoExistant($pseudo) )
						{
							echo " apparemment on a pas de pseudo ou nom de compte existants";
							if (filter_var($mail, FILTER_VALIDATE_EMAIL) ) 
							{
								$creeUser=mkUser($ndc,$pseudo,$password1,$mail);
								$addArgs ="?view=accueil";
								echo " ON CREE LE COMPTE";
							} 
							else 
							{
								echo 'Cet email a un format non adapté.';
								$addArgs ="?view=inscription";

							}
						}
						else
						{
							echo" LE PSEUDO EXISTE DEJA";
							$addArgs ="?view=inscription";

						}
					}
					else
					{
						echo "ndc existants";
						$addArgs ="?view=inscription";
					}

				}
			break;

			case 'submitModifProfil' : 

					// Prochaine vue par defaut = vue inscription
					$addArgs = "?view=profil";
					$iddeluser = valider("id_User","SESSION");


				if ($pseudo = valider("pseudoUser"))
					if ($pseudo != "")
						if(!pseudoExistant($pseudo))
							changerPseudo($iddeluser,$pseudo);

				if ($passwd = valider("passwordActuel"))
				{	
					if (verifPassUser($iddeluser,$passwd))				
						if ($passwdNouv1 = valider("passwordNouveau1"))
						{
							$password1= password_hash($passwdNouv1, PASSWORD_BCRYPT , ["cost" => $cost]);
								if ($passwdNouv2 = valider("passwordNouveau2"))
								{
									$password2= password_hash($passwdNouv2, PASSWORD_BCRYPT , ["cost" => $cost]);
									if($passwdNouv1 == $passwdNouv2)
										changerPasse($iddeluser,$password1);

								}


						}

				}


				if($mailUser = valider("Mail"))
				{
					consoleDebug($mailUser);
					if (filter_var($mailUser, FILTER_VALIDATE_EMAIL))
						changerMail($iddeluser,$mailUser);
				}
					// if (filter_var($Mail, FILTER_VALIDATE_EMAIL))
					// 	changerMail($iddeluser,$Mail);





			break;

			case 'submitPhotoProfil' : 

					// Prochaine vue par defaut = vue inscription
					$addArgs = "?view=profil";
					$informationsUser = recupererAvecId(valider("id_User", "SESSION")); // recupere les donnée dans la bdd de l'user
					$iddeluser = valider("id_User","SESSION");
					consoleDebug($iddeluser);

					$tabEXT= array('jpg','png','jpeg');


					if($photo = valider("filebuttonPhotoProfil"));
					{
						consoleDebug("ON a bien envoye l image");
						if (!empty($_FILES["filebuttonPhotoProfil"]))
        				{


              				// Recuperation de l'extension du fichier
          					$extension  = pathinfo($_FILES['filebuttonPhotoProfil']['name'], PATHINFO_EXTENSION);
          					consoleDebug("ici on a l extension" . $extension);
          					$fichier=basename($_FILES["filebuttonPhotoProfil"]["name"]);
          					consoleDebug($fichier);

          					$fichier = strtr($fichier,
     							'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
     							'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); 
								//On remplace les lettres accentutées par les non accentuées dans $fichier.
								//Et on récupère le résultat dans fichier
 
								//En dessous, il y a l'expression régulière qui remplace tout ce qui n'est pas une lettre non accentuées ou un chiffre
								//dans $fichier par un tiret "-" et qui place le résultat dans $fichier.
								$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

          					if(in_array(strtolower($extension),$tabEXT))
          					{
          						  // $cheminComplet = "ressources/profil/img_11.png";

           						 //  if (file_exists($cheminComplet)) 
            				//   {
            				//     consoleDebug(" on entre dans le if du fileexist");
            				//     echo "Le fichier  existe";
            				//   } 
            				//   else 
            				//   {
            				//      consoleDebug("Le fichier  n'existe pas");
            				//     copy($_FILES["filebuttonPhotoProfil"]["tmp_name"], "./$chemin");

            				//   }

            				// if(filesize($_FILES['fichier']['tmp_name']) <= MAX_SIZE)
            					if (is_uploaded_file($_FILES["filebuttonPhotoProfil"]["tmp_name"]))
            					{
              						consoleDebug("BONJOUR");

              						$chemin = "ressources/profil/img_" . $iddeluser . '.' . $extension;
              						consoleDebug($chemin);

              						consoleDebug($_FILES["filebuttonPhotoProfil"]["name"]);
              						print("Type : " . $_FILES["filebuttonPhotoProfil"]["type"] . "<br>");
              						consoleDebug("type : ");

              						consoleDebug( $_FILES["filebuttonPhotoProfil"]["type"]);


              						if (!is_dir("./ressources"))
              						  mkdir("./ressources");
              						if (!is_dir("./ressources/profil"))
              						  mkdir("./ressources/profil");



              						$name = $_FILES["filebuttonPhotoProfil"]["name"];
              						$cheminSansExt = "ressources/profil/img_" . $iddeluser . '.*'; 

              						$list = glob($cheminSansExt);
              						print_r($list);
             						foreach($list as $nextChemin)
             						{
                 					 	unlink($nextChemin);
                  						echo "Suppression de $nextChemin<br />";
              						}
                					copy($_FILES["filebuttonPhotoProfil"]["tmp_name"], "./$chemin");
                					// consoleDebug($chemin);



            						  // créer le répertoire miniature s'il n'existe pas
            						  // if (!is_dir("./$nomRepParent/$nomRep/thumbs"))
            						  //   mkdir("./$nomRepParent/$nomRep/thumbs");
            						// if (!is_dir("./$nomRepParent/$nomRep/copyright"))
            						//   mkdir("./$nomRepParent/$nomRep/copyright");
            						// if (!is_dir("./$nomRepParent/$nomRep/copyright/thumbs"))
            						//   mkdir("./$nomRepParent/$nomRep/copyright/thumbs");
              
            						$dataImg = getimagesize("./$chemin");  
            						$type = substr($dataImg["mime"], 6);// on enleve "image/"
            						// modifierPhoto($iddeluser,$chemin);

          						}
          						else
            						echo "Problème !";
        					}
    				}
    			}
			break;


	        case 'Creer' : 
	        if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
	            if (!is_dir("./Upload/" . $_GET["nomRep"])) 
	            {
	            // A compléter : Code de création d'un répertoire
	            	// echo $_GET["nomRep"];
	                mkdir("./Upload/" . $_GET["nomRep"]);
	            }
	            // die("");
	            $addArgs = "?view=galerie";
	            break;

	        case 'Supprimer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
			if (isset($_GET["fichier"]) && ($_GET["fichier"] != ""))
			{
				$nomRep = $_GET["nomRep"];
				$fichier = $_GET["fichier"];
				echo "nomRep = ".$nomRep;
				// A compléter : Supprime le fichier image
				unlink($nomRep . "/" . $fichier);
		
				// A compléter : Supprime aussi la miniature si elle existe					
				unlink($nomRep . "/thumbs/" . $fichier);	
			}
			$addArgs = "?view=galerie";
			break;

			case 'Renommer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
			if (isset($_GET["fichier"]) && ($_GET["fichier"] != ""))
			if (isset($_GET["nomFichier"]) && ($_GET["nomFichier"] != ""))
			{
				$nomRep = $_GET["nomRep"];
				$fichier = $_GET["fichier"];
				$nomFichier = $_GET["nomFichier"]; // nouveau nom 
				// A compléter : renomme le fichier et sa miniature si elle existe
				if (file_exists("./Upload/$nomRep/$fichier"))			
					rename("./Upload/$nomRep/$fichier","./Upload/$nomRep/$nomFichier");

				if (file_exists("./Upload/$nomRep/thumbs/$fichier"))			
					rename("./Upload/$nomRep/thumbs/$fichier","./Upload/$nomRep/thumbs/$nomFichier");

				$nomRep = "$nomRep/copyright";

				if (file_exists("./Upload/$nomRep/$fichier"))			
					rename("./Upload/$nomRep/$fichier","./Upload/$nomRep/$nomFichier");

				if (file_exists("./Upload/$nomRep/thumbs/$fichier"))			
					rename("./Upload/$nomRep/thumbs/$fichier","./Upload/$nomRep/thumbs/$nomFichier");
				
				
			}
			$addArgs = "?view=galerie";
			break;

			case 'Uploader' : 
			if (!empty($_FILES["FileToUpload"]))
			{

				if (is_uploaded_file($_FILES["FileToUpload"]["tmp_name"]))
				{
					//print("Quelques informations sur le fichier récupéré :<br>");
					//print("Nom : ".$_FILES["FileToUpload"]["name"]."<br>");
					//print("Type : ".$_FILES["FileToUpload"]["type"]."<br>");
					//print("Taille : ".$_FILES["FileToUpload"]["size"]."<br>");
					//print("Tempname : ".$_FILES["FileToUpload"]["tmp_name"]."<br>");
					$name = $_FILES["FileToUpload"]["name"];
					copy($_FILES["FileToUpload"]["tmp_name"],"./Upload/$nomRep/$name");

					// créer le répertoire miniature s'il n'existe pas
					if (!is_dir("./Upload/$nomRep/thumbs")) 
					{
						mkdir("./Upload/$nomRep/thumbs");
					}
						
					$dataImg = getimagesize("./Upload/$nomRep/$name");  
					$type= substr($dataImg["mime"],6);// on enleve "image/" 

					// créer la miniature dans ce répertoire 
					miniature($type,"./Upload/$nomRep/$name",200,"./Upload/$nomRep/thumbs/$name");
				}
				else
				{
					echo "pb";
				}
				$nameComplet = "./Upload/$nomRep/$name";
				$nameSimple = "$name";

				logo_copyright($nameComplet,$nameSimple,$nomRep);
				$type = pathinfo($nameComplet, PATHINFO_EXTENSION);
				if($type=="jpg")
					$type="jpeg";
				miniature($type,"./Upload/$nomRep/copyright/$name",200,"./Upload/$nomRep/copyright/thumbs/$name");
			}
			// die("");
			$addArgs = "?view=galerie&nomRep=$nomRep";
			break;

			case 'Supprimer Repertoire':
				// On ne peut supprimer que des répertoires vide !
				if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
				{
					// A compléter : Supprime le répertoire des miniatures s'il existe, puis le répertoire principal
					$repCopyright="./Upload/$nomRep/copyright";
					if (is_dir("./Upload/$nomRep/thumbs"))
					{
						$rep = opendir("./Upload/$nomRep/thumbs"); 		// ouverture du repertoire 
						while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce répertoire
						{

							if (($fichier!=".") && ($fichier!=".."))
							{
								// Pour éliminer les autres répertoires du menu déroulant, 
								// on dispose de la fonction 'is_dir'
								if (!is_dir("./Upload/$nomRep/thumbs/" . $fichier))
								{
									unlink("./Upload/$nomRep/thumbs/" . $fichier);
								}
							}
						}
						closedir($rep);
						rmdir("./Upload/$nomRep/thumbs");
					}

					if (is_dir($repCopyright))
					{
						if(is_dir("./Upload/$nomRep/copyright/thumbs"))
						{
							$rep = opendir("./Upload/$nomRep/copyright/thumbs"); 		// ouverture du repertoire 
							while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce répertoire
							{

								if (($fichier!=".") && ($fichier!=".."))
								{
									// Pour éliminer les autres répertoires du menu déroulant, 
									// on dispose de la fonction 'is_dir'
									if (!is_dir("./Upload/$nomRep/copyright/thumbs/" . $fichier))
									{
										unlink("./Upload/$nomRep/copyright/thumbs/" . $fichier);
									}
								}
							}
							closedir($rep);
							rmdir("./Upload/$nomRep/copyright/thumbs");
						}
						$rep=opendir($repCopyright);
						while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce répertoire
						{

							if (($fichier!=".") && ($fichier!=".."))
							{
								// Pour éliminer les autres répertoires du menu déroulant, 
								// on dispose de la fonction 'is_dir'
								if (!is_dir("./Upload/$nomRep/copyright/" . $fichier))
								{
									unlink("./Upload/$nomRep/copyright/" . $fichier);
								}
							}
						}
						closedir($rep);
						rmdir($repCopyright);
					}
					// répertoire principal
					$rep = opendir("./Upload/$nomRep"); 		// ouverture du repertoire 
					while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce répertoire
					{

						if (($fichier!=".") && ($fichier!=".."))
						{
							// Pour éliminer les autres répertoires du menu déroulant, 
							// on dispose de la fonction 'is_dir'
							if (!is_dir("./Upload/$nomRep/" . $fichier))
							{
								unlink("./Upload/$nomRep/" . $fichier);
							}
						}
					}
					closedir($rep);
					chmod("./Upload",0777);
					rmdir("./Upload/$nomRep");
					$nomRep = false;
					$addArgs = "?view=galerie";
				}
			break;

			case 'submitPreumium':
				$id_User = valider("id_User","SESSION");
				changerAbonnement($id_User,1);
				consoleDebug("bjr");
			break;

			case 'submitStopPreumium':
				$id_User = valider("id_User","SESSION");
				changerAbonnement($id_User,0);
			break;


			case 'download':

				$json = valider("tab");
				$nomRep = valider("nomRep");
				$tab=explode("|",$json);
				print_r($tab);
				echo "on rentre bien dans le download et on a $nomRep";
				$foldername="./Upload/$nomRep/";
				$dir = opendir("./Upload/$nomRep");
				$zipName="$nomRep.zip";
				$zip = new ZipArchive;
				$res=$zip->open($zipName,ZipArchive::CREATE);
				if($res==TRUE)
				{
					$zip->addEmptyDir($nomRep);
					if(!(empty($dir)))
					{

							
							while($file = readdir($dir))
							{
								foreach ($tab as $key => $value) 
								{
									echo "<br>";
									// echo $value;

									if (!($file == '.' || $file=='..'))
									{
										if(filetype($foldername . $file)=='file')
										{
											if($file == $value)
											{
												echo $file;
												$zip->addFile($foldername . $file,$nomRep ."/". $file);
											}
										}
									}
								}
							}
					}
					else
					{
						echo "directory empty";
					}
					closedir($dir);

				}
				else
				{
					echo "on ouvre pas l'archive";
				}
				if($zip->close() === false)
				{
					exit("Error creating ZIP file");
				};

				$file=$zipName;
				echo $file;
				if (headers_sent()) 
				{
				    echo 'HTTP header already sent';
				} 
				else 
				{
				    if (!is_file($file)) 
				    {
				        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
				        echo 'File not found';
				    } 
				    else if (!is_readable($file)) 
				    {
				        header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
				        echo 'File not readable';
				    } 
				    else 
				    {
				        header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
				        header("Content-Type: application/zip");
				        header("Content-Transfer-Encoding: Binary");
				        // header("Content-Length: ".filesize($file));
				        header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
				        readfile($file);
				        unlink($file);
				        exit;
				    }
				}
			break;


			}
		}

		

	

	// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments

	header("Location:" . $urlBase . $addArgs);

	// On écrit seulement après cette entête
	ob_end_flush();
	
?>










