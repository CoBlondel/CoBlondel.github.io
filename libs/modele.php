<?php


// inclure ici la librairie faciliant les requêtes SQL
include_once("maLibSQL.pdo.php");


// ************************ Inscription Login *****************************************
// ************************************************************************************
// ************************************************************************************
// ************************************************************************************


function listerUtilisateurs($classe = "both")
{

	// NB : la présence du symbole '=' indique la valeur par défaut du paramètre s'il n'est pas fourni
	// Cette fonction liste les users de la base de données 
	// et renvoie un tableau d'enregistrements. 
	// Chaque enregistrement est un tableau associatif contenant les champs 
	// id,pseudo,blacklist,connecte,couleur

	// Lorsque la variable $classe vaut "both", elle renvoie tous les users
	// Lorsqu'elle vaut "bl", elle ne renvoie que les users blacklistés
	// Lorsqu'elle vaut "nbl", elle ne renvoie que les users non blacklistés
	$array = array();
	$SQL = "select * from users";
	if ($classe == "bl")
		$SQL .= " where blacklist=1";
	if ($classe == "nbl")
		$SQL .= " where blacklist=0";
	
	// echo $SQL;
	return parcoursRs(SQLSelect($SQL,$array));

}


function interdireUtilisateur($id_User)
{
	$array = array($id_User);

	// cette fonction affecte le booléen "blacklist" à vrai
	$SQL = "UPDATE users SET blacklist=1 WHERE id_User= ? ";
	// les apostrophes font partie de la sécurité !! 
	// Il faut utiliser addslashes lors de la récupération 
	// des données depuis les formulaires

	SQLUpdate($SQL,$array);
}

function autoriserUtilisateur($id_User)
{
	$array = array($id_User);
	// cette fonction affecte le booléen "blacklist" à faux 
	$SQL = "UPDATE users SET blacklist=0 WHERE id_User= ? ";
	SQLUpdate($SQL,$array);
}

function verifUserBdd($login)
{
	$array = array($login);
	// Vérifie l'identité d'un utilisateur 
	// dont les identifiants sont passes en paramètre
	// renvoie faux si user inconnu
	// renvoie l'id de l'utilisateur si succès

	$SQL="SELECT id_User FROM users WHERE ndc_User= ? ";

	return SQLGetChamp($SQL,$array);
	// si on avait besoin de plus d'un champ
	// on aurait du utiliser SQLSelect
}

function getPasswordHash($id)
{
	$array = array($id);
	$SQL ="SELECT  password_User FROM users WHERE id_User= ? ";
	return SQLGetChamp($SQL,$array);
}


function creerUser($pseudo,$passe)
{
	$array = array($pseudo,$passe);
	$SQL = "INSERT INTO users(pseudo_User,password_User) VALUES (?,?)";

	return SQLInsert($SQL,$array);
}

function isValid($id)
{
	$array = array($id);
	$SQL = "SELECT valide FROM users WHERE id_User= ? ";

	return SQLGetChamp($SQL,$array);

	//equivalent a 
	// $tabR = parcoursRs(SQLSelect($SQL,$array));
	// return $tabR[0]["valide"];

}

function isAdmin($id_User)
{
	$array = array($id_User);
	// vérifie si l'utilisateur est un administrateur
	$SQL ="SELECT admin_User FROM users WHERE id_User=?";
	return SQLGetChamp($SQL,$array); 
}

function isVip($id_User)
{
	$array = array($id_User);
	//Vérifie si l'utilisateur à un compte VIP
	$SQL = "SELECT VIP FROM users WHERE id_User=?";
	return SQLGetChamp($SQL,$array);
}

function mkUser($ndc, $pseudo,$passwd1,$mail)
{
	$array = array($ndc, $pseudo,$passwd1,$mail);

	// Cette fonction crée un nouvel utilisateur et renvoie l'identifiant de l'utilisateur créé


	$SQL = "INSERT INTO users(ndc_User, pseudo_User, password_User,mail_User,dateSignin_User) 
			VALUES (?,?,?,?,current_date)";

	return SQLInsert($SQL,$array);

}

function ndcExistant($ndc_User)
{
	$array = array($ndc_User);
	$SQL = "SELECT ndc_User FROM users WHERE ndc_User=?";
	return SQLGetChamp($SQL,$array); 

	
}


function pseudoExistant($pseudo_User)
{
	$array = array($pseudo_User);
	$SQL = "SELECT pseudo_User FROM users WHERE pseudo_User=?";
	return SQLGetChamp($SQL,$array); 
}


function changerPseudo($id_User, $pseudo)
{
	$array = array($pseudo, $id_User);
	// cette fonction modifie le pseudo d'un utilisateur
	$SQL = "UPDATE users SET pseudo_User=? WHERE id_User=?"; 
	//mettre des guillemets autour des valeurs envoyé par l'utilisateur (ex = $pseudo) permet de sécuriser des injections SQL
	SQLUpdate($SQL,$array);
}

function changerAbonnement($id_User,$vip)
{
	$array = array($vip, $id_User);
	// cette fonction modifie le pseudo d'un utilisateur
	$SQL = "UPDATE users SET VIP=? WHERE id_User=?"; 
	//mettre des guillemets autour des valeurs envoyé par l'utilisateur (ex = $pseudo) permet de sécuriser des injections SQL
	SQLUpdate($SQL,$array);
}

function changerPasse($id_User, $passe)
{
	$array = array($passe,$id_User);
	// cette fonction modifie le mot de passe d'un utilisateur
	$SQL = "UPDATE users SET password_User=? WHERE id_User=?";
	SQLUpdate($SQL,$array);
}

function changerMail($id_User, $mail)
{
	$array = array($mail, $id_User);
	// cette fonction modifie le mot de passe d'un utilisateur
	$SQL = "UPDATE users SET mail_User=? WHERE id_User=?";
	SQLUpdate($SQL,$array);
}


// ************************ Profil *****************************************
// ************************************************************************************
// ************************************************************************************
// ************************************************************************************

function recupererAvecId($id_User)
{
	$array = array($id_User);
	$SQL = "SELECT * FROM users WHERE id_User = ?";
	return parcoursRs(SQLSelect($SQL,$array));
}



function modifierPhoto($id_User,$chemin)
{
	$array = array($chemin,$id_User);
	$SQL = "UPDATE users set photo_User=? where id_User = ? ";
	return SQLUpdate($SQL,$array);
}


function chercherUsers($id_User,$chaine)
{
	$array = array($id_User,'%'. $chaine .'%');
	$SQL = "SELECT pseudo_User FROM users WHERE id_User != ? AND pseudo_User LIKE ?";
	return parcoursRs(SQLSelect($SQL,$array));
}

function getIdFromPseudo($pseudo)
{
	$array= array($pseudo);
	$SQL = "SELECT id_User FROM users WHERE pseudo_User LIKE ?";
	return SQLGetChamp($SQL,$array);
}

// function getPseudoFromId($pseudo)
// {
// 	$array= array($pseudo);
// 	$SQL = "SELECT id_User FROM users WHERE pseudo_User LIKE ?";
// 	return SQLGetChamp($SQL,$array);
// }





?>
