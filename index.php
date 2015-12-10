<?php
#si le fichier constantes est vide , on redirige vers l'install.php
if (filesize('constantes.php') == 0)
{
	header('location: install.php');
	exit();
}

#on inclus le fichier de connexion
require_once("connexion.php");


$msg = "";
if(isset($_POST['add']))
{
	#si c'est le radio button action qui est coché
	if(isset($_POST['group2']) && $_POST['group2'] == "Action")
	{
		if(isset($_POST['NomProd']) && isset($_POST['MontantProd']) && isset($_POST['Bourse']))
		{
			$nom = htmlentities($_POST['NomProd'],ENT_QUOTES,'UTF-8');
			$Montant = floatval($_POST['MontantProd']);
			$bourse = floatval($_POST['Bourse']);
			if(!empty($nom) && !empty($Montant) && !empty($bourse))
			{		
				$INSERT = "INSERT INTO `produitbancaire` (`nom`, `montant`) VALUES (:nom_produit,:montant_produit);";
				$requete = $connect->prepare($INSERT);
				$requete->execute(array(':nom_produit'=>$nom,':montant_produit'=>$Montant));
				$IdProd = intval($connect->lastInsertId()); #on selectionne l'id de la derniere requete effectué

				$INSERT2 = "INSERT INTO `action` (`bourse`,`id_prod`) VALUES (:bourse,:id_prod);";
				$requete2 = $connect->prepare($INSERT2);
				$requete2->execute(array(':bourse'=>$bourse,':id_prod'=>$IdProd));
				$Insertion2 = $requete2->rowCount();
				
				if($Insertion2 >= 1) # insertion reussite
				{
					$msg .='<div class="alert alert-dismissible alert-success">';
					$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
					$msg .='insertion reussite.';
					$msg .='</div>';
				}
				else # probléme
				{
					$msg .='<div class="alert alert-dismissible alert-danger">';
					$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
					$msg .='erreur dans l\'insertion , veuillez contacter un administrateur';
					$msg .='</div>';					
				}
			}
			else #champs non rempli
			{
				$msg .='<div class="alert alert-dismissible alert-warning">';
				$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
				$msg .='Un des champs présents n\'existe pas!';
				$msg .='</div>';					
			}
		}
	}
	elseif(isset($_POST['group2']) && $_POST['group2'] == "Emprunt") #si c'est le radio button emprunt qui est coché
	{
		if(isset($_POST['NomProd']) && isset($_POST['MontantProd']) && isset($_POST['taux']) && isset($_POST['duree']))
		{
			$nom = htmlentities($_POST['NomProd'],ENT_QUOTES,'UTF-8');
			$Montant = floatval($_POST['MontantProd']);
			$taux = floatval($_POST['taux']);
			$duree = intval($_POST['duree']);
			if(!empty($nom) && !empty($Montant) && !empty($taux) && !empty($duree))
			{		
				$INSERT = "INSERT INTO `produitbancaire` (`nom`, `montant`) VALUES (:nom_produit,:montant_produit);";
				$requete = $connect->prepare($INSERT);
				$requete->execute(array(":nom_produit"=>$nom,":montant_produit"=>$Montant));
				$IdProd = intval($connect->lastInsertId());

				$INSERT2 = "INSERT INTO `emprunt` (`taux`, `duree`,`id_prod`) VALUES (:taux,:duree,:id_prod);";
				$requete2 = $connect->prepare($INSERT2);
				$requete2->execute(array(":taux"=>$taux,":duree"=>$duree,":id_prod"=>$IdProd));
				$Insertion2 = $requete2->rowCount();
				if($Insertion2 >= 1) # insertion reussite
				{
					$msg .='<div class="alert alert-dismissible alert-success">';
					$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
					$msg .='Insertion reussite .';
					$msg .='</div>';
				}
				else #probléme
				{
					$msg .='<div class="alert alert-dismissible alert-danger">';
					$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
					$msg .='erreur dans l\'insertion , veuillez contacter un administrateur';
					$msg .='</div>"';					
				}
			}
			else #champs non rempli
			{
				$msg .='<div class="alert alert-dismissible alert-warning">';
				$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
				$msg .='Un des champs présents n\'existe pas!';
				$msg .='</div>';
			}
		}
	}
	else #il manque des champs ...
	{
		$msg .='<div class="alert alert-dismissible alert-danger">';
		$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
		$msg .='Veuillez selectionner un Action ou Emprunt dans le formulaire';
		$msg .='</div>';		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<!-- Cette page est valide W3C -->
	<!-- Debut du head -->
	<head>
		<!-- Title de la page -->
		<title>Banque</title>
		<meta name="viewport" content="width=device-width, target-densitydpi=device-dpi"/>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="content-language" content="fr" />
		<link rel="stylesheet" href="css/style.css"  type="text/css" />
		<script src="js/jquery-2.1.4.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/assets_js_custom.js"></script>
	</head>
	<body>
	<center>
		<h1>TP produit bancaire</h1>
		<div class="row">
		<div class="col-lg-4">
			<!-- menu<div class="col-lg-6 col-md-3 col-sm-4">
				<div class="list-group table-of-contents">
					<a class="list-group-item" href="http://localhost/Banque/index.php">Accueil</a>
					<a class="list-group-item" href="http://localhost/Banque/Action.php">Produit Action</a>
					<a class="list-group-item" href="http://localhost/Banque/Emprunt.php">Produit Emprunt</a>
				</div>
			</div>-->		
		</div>
		<div class="col-lg-4">
		<?php echo $msg;?>
			<form method="POST" action="" class="form-horizontal">
			<fieldset>
				<legend>Formulaire </legend>
				<div class="form-group">
					<label class="col-lg-2 control-label">Nom du Produit</label>
					<div class="col-lg-10">
						<input type="text" class="form-control" placeholder="Nom du Produit" name="NomProd">
					</div>
				</div>
				<div class="form-group">
					<label  class="col-lg-2 control-label">Montant du Produit</label>
					<div class="col-lg-10">
						<input type="text" class="form-control" placeholder="Montant du Produit" name="MontantProd">
					</div>
				</div>
				<div class="form-group">
					<label  class="col-lg-2 control-label">Choix</label>
					<div class="col-lg-10">
						<p><input type="radio" id="Action" name="group2" value="Action"> Action<br>
						<input type="radio" id="Emprunt" name="group2" value="Emprunt"> Emprunt<br></p>
					</div>
				</div>
				<div class="form-group" id="form1">
					<label  class="col-lg-2 control-label">Bourse</label>
					<div class="col-lg-10">
						<input type="text" class="form-control" placeholder="Bourse" name="Bourse">
					</div>
				</div>
				<div id="form2">
					<div class="form-group">
						<label  class="col-lg-2 control-label">Taux</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" placeholder="Taux" name="taux">
						</div>
					</div>
					<div class="form-group">
						<label  class="col-lg-2 control-label">Durée</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" placeholder="Durée" name="duree">
						</div>
					</div>
				</div>
				<!-- Formulaire Bourse -->
				<div class="form-group">
					<div class="col-lg-10 col-lg-offset-2">
						<button type="reset" class="btn btn-default">Cancel</button>
						<button type="submit" name="add" class="btn btn-primary">ajouter</button>
					</div>
				</div>
				</fieldset>
			</form>
			</div>
			<div class="col-lg-4"></div>
			</div>
			<div class="row">
				<div class="col-lg-4"></div>
				<div class="col-lg-4">
				<ul class="nav nav-tabs">
					<li class="active"><a aria-expanded="true" href="#AffAction" data-toggle="tab">Action</a></li>
					<li><a aria-expanded="true" href="#AffEmprunt" data-toggle="tab">Emprunt</a></li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div class="tab-pane fade active in" id="AffAction">
					<!-- tableau des action id,nom,montant,bourse -->
						<table class="table table-striped table-hover ">
						  <thead>
							<tr>
							  <th>Nom</th>
							  <th>Montant</th>
							  <th>Bourse</th>
							</tr>
						  </thead>
						  <tbody>
						  <?php
						  #on parcours le tableaux des objets Actions
						  foreach($LesProduitsAction as $LeProdAction)
						  {
							  echo '<tr>';
							  echo '<td>'.$LeProdAction->GetNom().'</td>';
							  echo '<td>'.$LeProdAction->GetMontant().'</td>';
							  echo '<td>'.$LeProdAction->GetBourse().'</td>';
							  echo '</tr>';
						  }
						  ?>
						</tbody>
						</table>
					<!--fin de tableau des produits avec actions -->
					</div>
					<div class="tab-pane fade" id="AffEmprunt">
					<!-- tableau des emprunts id,nom,montant,taux,duree -->
						<table class="table table-striped table-hover ">
						  <thead>
							<tr>
							  <th>Nom</th>
							  <th>Montant</th>
							  <th>Taux</th>
							  <th>Durée</th>
							</tr>
						  </thead>
						  <tbody>
						  <?php
						  #on parcours le tableaux des objets Emprunts
						  foreach($LesProduitsEmprunt as $LeProdEmpt)
						  {
							  echo '<tr>';
							  echo '<td>'.$LeProdEmpt->GetNom().'</td>';
							  echo '<td>'.$LeProdEmpt->GetMontant().'</td>';
							  echo '<td>'.$LeProdEmpt->GetTaux().'</td>';
							  echo '<td>'.$LeProdEmpt->GetTaux().'</td>';
							  echo '</tr>';
						  }
						   ?>
						</tbody>
						</table>
					<!--fin de tableau des produits avec emprunt -->				
					</div>
				</div>
				</div>
				<div class="col-lg-4"></div>
			</div>
		</center>
	<script>
	// code jquery
	// une foix la page chargé
	$( document ).ready(function()
	{
		//on cache les div id=form1 et id=form2
		$("#form1").hide();
		$("#form2").hide();
	});
	
	//si on clique sur le radio button action
	$('#Action').click(function()
	{
		$("#form1").show(); //on affiche les div id=form1
		$("#form2").hide(); //on cache le div id=form2
	});
	
	//si on clique sur le radio button emprunt
	$('#Emprunt').click(function()
	{
		$("#form1").hide(); //on cache les div id=form1
		$("#form2").show(); //on affiche le div id=form2
	});
	</script>
	</body>
</html>