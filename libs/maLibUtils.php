<?php


/**
 * @file maLibUtils.php
 * Ce fichier définit des fonctions d'accès ou d'affichage pour les tableaux superglobaux
 */

/**
 * Vérifie l'existence (isset) et la taille (non vide) d'un paramètre dans un des tableaux GET, POST, COOKIES, SESSION
 * Renvoie false si le paramètre est vide ou absent
 * @note l'utilisation de empty est critique : 0 est empty !!
 * Lorsque l'on teste, il faut tester avec un ===
 * @param string $nom
 * @param string $type
 * @return string|boolean
 */
function valider($nom,$type="REQUEST")
{	
	switch($type)
	{
		case 'REQUEST': 
		if(isset($_REQUEST[$nom]) && !($_REQUEST[$nom] == "")) 	
			return proteger($_REQUEST[$nom]); 	
		break;
		case 'GET': 	
		if(isset($_GET[$nom]) && !($_GET[$nom] == "")) 			
			return proteger($_GET[$nom]); 
		break;
		case 'POST': 	
		if(isset($_POST[$nom]) && !($_POST[$nom] == "")) 	
			return proteger($_POST[$nom]); 		
		break;
		case 'COOKIE': 	
		if(isset($_COOKIE[$nom]) && !($_COOKIE[$nom] == "")) 	
			return proteger($_COOKIE[$nom]);	
		break;
		case 'SESSION': 
		if(isset($_SESSION[$nom]) && !($_SESSION[$nom] == "")) 	
			return $_SESSION[$nom]; 		
		break;
		case 'SERVER': 
		if(isset($_SERVER[$nom]) && !($_SERVER[$nom] == "")) 	
			return $_SERVER[$nom]; 		
		break;
	}
	return false; // Si pb pour récupérer la valeur 
}


/**
 * Vérifie l'existence (isset) et la taille (non vide) d'un paramètre dans un des tableaux GET, POST, COOKIE, SESSION
 * Prend un argument définissant la valeur renvoyée en cas d'absence de l'argument dans le tableau considéré

 * @param string $nom
 * @param string $defaut
 * @param string $type
 * @return string
*/
function getValue($nom,$defaut=false,$type="REQUEST")
{
	// NB : cette commande affecte la variable resultat une ou deux fois
	if (($resultat = valider($nom,$type)) === false)
		$resultat = $defaut;

	return $resultat;
}

/**
*
* Evite les injections SQL en protegeant les apostrophes par des '\'
* Attention : SQL server utilise des doubles apostrophes au lieu de \'
* ATTENTION : LA PROTECTION N'EST EFFECTIVE QUE SI ON ENCADRE TOUS LES ARGUMENTS PAR DES APOSTROPHES
* Y COMPRIS LES ARGUMENTS ENTIERS !!
* @param string $str
*/
function proteger($str)
{
	// attention au cas des select multiples !
	// On pourrait passer le tableau par référence et éviter la création d'un tableau auxiliaire
	if (is_array($str))
	{
		$nextTab = array();
		foreach($str as $cle => $val)
		{
			$nextTab[$cle] = addslashes($val);
		}
		return $nextTab;
	}
	else 	
		return addslashes ($str);
	//return str_replace("'","''",$str); 	//utile pour les serveurs de bdd Crosoft
}



function tprint($tab)
{
	echo "<pre>\n";
	print_r($tab);
	echo "</pre>\n";	
}


function rediriger($url,$qs="")
{
	// if ($qs != "")	 $qs = urlencode($qs);	
	// Il faut respecter l'encodage des caractères dans les chaînes de requêtes
	// NB : Pose des problèmes en cas de valeurs multiples
	// TODO: Passer un tabAsso en paramètres

	if ($qs != "") $qs = "?$qs";
 
	header("Location:$url$qs"); // envoi par la méthode GET
	die(""); // interrompt l'interprétation du code 

	// TODO: on pourrait passer en parametre le message servant au die...
}

// TODO: intégrer les redirections vers la page index dans une fonction :

/*
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php");
	die("");
}
*/

function consoleDebug($data)
{
	if (is_array($data))
		$output = "<script>console.log('" . /*addslashes*/(implode(',', $data)) . "');</script>";
	else
		$output = "<script>console.log('" . /*addslashes*/($data) . "');</script>";

	echo "$output\n";
}



function imageExist($chemin)
{
	if (file_exists($chemin.".jpg")) 
    {
        // consoleDebug(" le fichier existe");
        return $chemin.".jpg";
    }
    else if(file_exists($chemin.".jpeg"))
    {
        // consoleDebug(" le fichier existe");
        return $chemin.".jpeg";
        
    }
    else if(file_exists($chemin.".png"))
    {
        // consoleDebug(" le fichier existe");
        return $chemin.".png";
        
    }
    return "ressources/profil/default.jpg";
          
}

function miniature($type,$nom,$dw,$nomMin)
{
	// Crée une miniature de l'image $nom
	// de largeur $dw
	// et l'enregistre dans le fichier $nomMin 

	// lecture de l'image d'origine, enregistrement dans la zone mémoire $im
	switch($type)
	{
		case "jpeg" : $im =  imagecreatefromjpeg ($nom);break;
		case "png" : $im =  imagecreatefrompng ($nom);break;
		case "gif" : $im =  imagecreatefromgif ($nom);break;		
	}

	$sw = imagesx($im); // largeur de l'image d'origine
	$sh = imagesy($im); // hauteur de l'image d'origine
	$dh = $dw * $sh / $sw;

	$im2 = imagecreatetruecolor($dw, $dh);

	$dst_x= 0;
	$dst_y= 0;
	$src_x= 0; 
	$src_y= 0; 
	$dst_w= $dw ; 
	$dst_h= $dh ; 
	$src_w= $sw ; 
	$src_h= $sh ;
	
	imagecopyresized ($im2,$im,$dst_x , $dst_y  , $src_x  , $src_y  , $dst_w  , $dst_h  , $src_w  , $src_h);
	
	
	switch($type)
	{
		case "jpeg" : imagejpeg($im2,$nomMin);break;
		case "png" : imagepng($im2,$nomMin);break;
		case "gif" : imagegif($im2,$nomMin);break;		
	}

	imagedestroy($im);
	imagedestroy($im2);
}


/************************************************************/
/**************** 	Fonction copyright 	*********************/
/************************************************************/


function logo_copyright($image,$nomSimple,$nomRep){

		/*	Mettre un logo de dimension : 20% de la hauteur de l'image et avec une marge de 3% du bord
		*	Mettre un texte horizontal en rouge en bas de la photo : à une hauteur de 10% du bas de l'image
		*/	

		// créer le répertoire cpyright et son répertoir miniature s'ils n'existent pas
		$nomSousRep = "copyright";
		$nomCopy = "./Upload/$nomRep/$nomSousRep/$nomSimple";
		if (!is_dir("./Upload/$nomRep/$nomSousRep")) 
		{
			mkdir("./Upload/$nomRep/$nomSousRep");
		}
		if (!is_dir("./Upload/$nomRep/$nomSousRep/thumbs")) 
		{
			mkdir("./Upload/$nomRep/$nomSousRep/thumbs");
		}

		/** CHARGEMENT DE L'IMAGE **/
		list($width, $height) = getimagesize($image);						// Obtenir les dimensions du fichier // width : largeur / height : hauteur
		/** Vérifie si l'image est un .jpeg ou un .png ou un .gif **/
		if(exif_imagetype($image) == IMAGETYPE_JPEG)
			$im = imagecreatefromjpeg($image);								// Transformation du fichier en image
		if(exif_imagetype($image) == IMAGETYPE_PNG)
			$im = imagecreatefrompng($image);
		if(exif_imagetype($image) == IMAGETYPE_GIF)
			$im = imagecreatefromgif($image);
		$couleur = imagecolorallocatealpha($im, 255,0,0,80); 				// Alloue une couleur pour le texte : 0 0 0 = noir | 255 0 0 = rouge
		imagecolortransparent($im, $couleur); 								// Définir la couleur transparente
		
		/** CHARGEMENT DU LOGO COPYRIGHT **/
		$logo = "ressources/ig2i.jpeg";								// Chemin du fichier
		/** Vérifie si l'image est un .jpeg ou un .png ou un .gif **/
		if(exif_imagetype($logo) == IMAGETYPE_JPEG)
			$imlogo = imagecreatefromjpeg($logo);
		if(exif_imagetype($logo) == IMAGETYPE_PNG)
			$imlogo = imagecreatefrompng($logo);
		if(exif_imagetype($logo) == IMAGETYPE_GIF)
			$imlogo = imagecreatefromgif($logo);
		list($logowidth, $logoheight, $logotype) = getimagesize($logo);		// Récupére les dimensions du logo

		/** CALCUL DE LA NOUVELLE DIMENSION DU LOGO **/
		$percent = 20;														//Ajustement à 20% de la hauteur de l'image
		$newheight = $height * $percent / 100;
		$delta = $logoheight / $newheight;
		$newwidth = $logowidth / $delta;
		
		/**	SUPERPOSE L'IMAGE & LE LOGO **/
		imagecopyresized($im, $imlogo, $width-$newwidth-$width*0.03, $height*0.03, 0, 0, $newwidth, $newheight, $logowidth, $logoheight);
		
		/** DEFINIR LA POLICE DU TEXTE **/
		putenv('GDFONTPATH=' . realpath('./ressources/polices'));
		$font = "./ressources/fonts/chumbly.ttf";
		
		/** SUPERPOSE L'IMAGE ET LE TEXTE **/
		imagettftext($im, $width/10, 0, $width * 0.25, $height * 0.9, $couleur, $font, "copyright");	// affiche le texte sur l'image
			//$image : l'image sur laquelle inserer le texte
			//$size : Taille des caractères
			//$angle : Angle d'inclinaison du texte en degré
			//$x : Coordonée du premier caractère
			//$y : Coordonée de la ligne de base du texte
			//$color : Couleur du texte
			//$fontfile : Police des caractères
			//$text : Texte a inserer
		
		/** SAUVEGARDE DE L'IMAGE **/
		if(exif_imagetype($image) == IMAGETYPE_JPEG)
			imagejpeg($im,$nomCopy);					// Enregistre l'image sous le nom : $nomCopy
		if(exif_imagetype($image) == IMAGETYPE_PNG)
			imagepng($im,$nomCopy);
		if(exif_imagetype($image) == IMAGETYPE_GIF)
			imagegif($im,$nomCopy);
		imagedestroy($im);								// Libère toute la mémoire associé à l'image
}	//fin logo_copyright()

?>
