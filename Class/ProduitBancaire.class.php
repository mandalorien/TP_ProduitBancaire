<?php
# http://php.net/manual/fr/keyword.class.php
class ProduitBancaire
{
	/** les attributs **/
	private $_Id;
	private $_Nom;
	private $_Montant;
	
	public function GetId()
	{
		return $this->_Id;
	}	
	public function GetNom()
	{
		return $this->_Nom;
	}
	
	public function GetMontant()
	{
		return $this->_Montant;
	}

	public function SetId($val)
	{
		$this->_Id = $val;
	}
	
	public function SetNom($val)
	{
		$this->_Nom = $val;
	}
	
	public function SetMontant($val)
	{
		$this->_Montant = $val;
	}
	
	public function GetInfo()
	{
		return "Nom : ". $this->_Nom ." Montant : ". $this->_Montant;
	}
}
?>