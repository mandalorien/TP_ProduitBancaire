<?php
require_once("ProduitBancaire.class.php");

# http://php.net/manual/fr/keyword.class.php
# http://php.net/manual/fr/language.oop5.inheritance.php
class Action extends ProduitBancaire
{
	/** les attributs **/
	private $_Bourse;
	
	/** le constructeur : Permet d'inialiser l'objet **/
	public function __construct($id,$nom,$montant,$bourse)
	{
		parent::SetId($id);
		parent::SetNom($nom);
		parent::SetMontant($montant);
		$this->_Bourse = $bourse;
	}

	public function GetBourse()
	{
		return $this->_Bourse;
	}
	
	public function GetInfo()
	{
		return "Nom :". Parent::GetNom() ." Montant :". Parent::GetMontant() ." Bourse :".$this->_Bourse;
	}
}
?>