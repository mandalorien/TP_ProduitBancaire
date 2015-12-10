<?php
require_once("constantes.php");

#connexion à la base de donnée
try {
    $connect = new PDO('mysql:host='. db_serveur .';dbname='. db_dbname .'',db_user,db_password);
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

#on inclus les fichiers principaux
require_once("Class/ProduitBancaire.class.php");
require_once("Class/Emprunt.class.php");
require_once("Class/Action.class.php");

#2 tableaux qui contiendront les objets 
$LesProduitsAction = array();
$LesProduitsEmprunt = array();

# on selectionne toutes les Actions par rapport à l'id des produits
$SQL = 'SELECT * FROM produitbancaire P,action A where P.id_produit = A.id_prod';
$requete = $connect->prepare($SQL);
$requete->execute();
$ToutLesProduitsAction = $requete->fetchAll(PDO::FETCH_ASSOC);
foreach($ToutLesProduitsAction as $LeProduitAction)
{
	$LesProduitsAction[$LeProduitAction['id_prod']] = new Action($LeProduitAction['id_prod'],$LeProduitAction['nom'],$LeProduitAction['montant'],$LeProduitAction['bourse']);
}
$requete->CloseCursor();

#on selectionne tous les emprunts par rapport à l'id des produits
$SQL2 = 'SELECT * FROM produitbancaire P,Emprunt E  WHERE P.id_produit=E.id_prod';
$requete = $connect->prepare($SQL2);
$requete->execute();
$ToutLesProduitsEmprunt = $requete->fetchAll(PDO::FETCH_ASSOC);
foreach($ToutLesProduitsEmprunt as $LeProduitEmprunt)
{
	$LesProduitsEmprunt[$LeProduitEmprunt['id_prod']] = new Emprunt($LeProduitEmprunt['id_prod'],$LeProduitEmprunt['nom'],$LeProduitEmprunt['montant'],$LeProduitEmprunt['taux'],$LeProduitEmprunt['duree']);
}

$requete->CloseCursor();
?>