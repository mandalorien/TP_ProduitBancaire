<?php
require_once("ProduitBancaire.class.php");

# http://php.net/manual/fr/keyword.class.php
# http://php.net/manual/fr/language.oop5.inheritance.php
class Emprunt extends ProduitBancaire
{
	/** les attributs **/
	private $_Taux;
	private $_Duree;
	
	/** le constructeur : Permet d'inialiser l'objet **/
	public function __construct($id,$nom,$montant,$taux,$duree)
	{
		parent::SetId($id);
		parent::SetNom($nom);
		parent::SetMontant($montant);
		$this->_Taux = $taux;
		$this->_Duree = $duree;
	}

	public function GetTaux()
	{
		return $this->_Taux;
	}
	
	public function GetDuree()
	{
		return $this->_Duree;
	}
	
	public function GetInfo()
	{
		return "Nom :". Parent::GetNom() ." Montant :". Parent::GetMontant() ." Taux :".$this->_Taux ." Durée :".$this->_Duree;
	}
}
?>