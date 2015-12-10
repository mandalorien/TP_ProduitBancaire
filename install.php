<?php
error_reporting(E_ERROR);

#function qui permet d'executer un fichier sql
function executeQueryFile($filesql,$link)
{
	$query = file_get_contents($filesql);
	$array = explode(";\n", $query);
	$b = true;
	for ($i=0; $i < count($array) ; $i++)
	{
		$str = $array[$i];
		if ($str != '')
		{
			$str .= ';';
			$b &= mysqli_query($link,$str);  
		}
	}
	return $b;
}

if (filesize('constantes.php') == 0)
{
$msg = "";
#si le bouton est cliqué			
if(isset($_POST['go']))
{
	# si les existes ...
	if(isset($_POST['serveur']) && isset($_POST['identifiant']) && isset($_POST['password']))
	{
		#verification des champs non vides
		if(!empty($_POST['serveur']) && !empty($_POST['identifiant']))
		{
			#on se connecte au serveur mysql
			$link = mysqli_connect($_POST['serveur'],$_POST['identifiant'],$_POST['password']);
			
			#impossible de se connecter
			if(!$link)
			{
				#erreur de connexion à la bdd
				$msg .='<div class="alert alert-dismissible alert-danger">';
				$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
				$msg .='Impossible de se connecter au serveur , merci de reporter le bug sur github !';
				$msg .='</div>';
			}
			else
			{
				#si le champs de database existe
				if(isset($_POST['namedb']))
				{
					#si on veux creer la bdd directement sous mysql
					if(isset($_POST['createbd']))
					{
						mysqli_query($link, "CREATE DATABASE ".$_POST['namedb'].";");
					}
					
					#on selectionne la bdd
					$db = mysqli_select_db($link,$_POST['namedb']);
					
					#impossible de se connecter à la bdd
					if(!$db)
					{
							$msg .='<div class="alert alert-dismissible alert-danger">';
							$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
							$msg .='Impossible de se connecter à la base de donnée '.$_POST['namedb'].'!';
							$msg .='</div>';								
					}
					else
					{
						#on lis et execute le fichier sql
						executeQueryFile("banque.sql",$link);
						
						#on ouvre le fichier constantes.php en lecture/écriture
						$dz = fopen("constantes.php", "w");
						
						#on arrive pas à l'ouvrir
						if (!$dz) {
							$msg .='<div class="alert alert-dismissible alert-danger">';
							$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
							$msg .='Impossible d\'ouvrir le fichier constante.php , merci de reporter le bug sur github !';
							$msg .='</div>';					
						}
						else
						{
			$fileData =<<<EOF
<?php
Define("db_serveur","{$_POST['serveur']}");
Define("db_dbname","{$_POST['namedb']}");
Define("db_user","{$_POST['identifiant']}");
Define("db_password","{$_POST['password']}");
?>
EOF;
							#on écris dedans
							fwrite($dz, $fileData);
							
							#on ferme le fichier
							fclose($dz);
							
							$msg .='<div class="alert alert-dismissible alert-success">';
							$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
							$msg .='Installation effectuer avec succée , veuillez vous rendre sur l\'index du site.';
							$msg .='</div>';					
						}
					}
				}
			}
		}
		else
		{
				#erreur de connexion à la bdd
				$msg .='<div class="alert alert-dismissible alert-danger">';
				$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
				$msg .='Vous avez oublié de saisir des champs !';
				$msg .='</div>';			
		}
	}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<!-- Cette page est valide W3C -->
	<!-- Debut du head -->
	<head>
		<!-- Title de la page -->
		<title>Installation Banque</title>
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
		<div class="col-lg-4"></div>
		<div class="col-lg-4">
		<?php echo $msg;?>
		<?php if (filesize('constantes.php') == 0)
		{
		?>
			<form method="POST" action="" class="form-horizontal">
			<fieldset>
				<legend>Installation</legend>
				<div class="form-group">
					<label class="col-lg-2 control-label">Serveur SQL </label>
					<div class="col-lg-10">
						<input type="text" class="form-control" placeholder="localhost..." name="serveur">
					</div>
				</div>
				<div class="form-group">
					<label  class="col-lg-8 control-label">Créer la base de donnée directement sur mysql :</label>
					<label  class="col-lg-2 control-label"><input type="checkbox" name="createbd"> oui</label>
				</div>
				<div class="form-group">
					<label  class="col-lg-2 control-label">Base de donnée </label>
					<div class="col-lg-10">
						<input type="text" class="form-control" placeholder="Nom de votre Base de donnée..." name="namedb">
					</div>
				</div>
				<div class="form-group">
					<label  class="col-lg-2 control-label">Identifiant </label>
					<div class="col-lg-10">
						<input type="text" class="form-control" placeholder="root.." name="identifiant">
					</div>
				</div>
				<div class="form-group">
					<label  class="col-lg-2 control-label">Mot de passe </label>
					<div class="col-lg-10">
						<input type="password" class="form-control" placeholder="root..." name="password">
					</div>
				</div>
				<!-- Formulaire Bourse -->
				<div class="form-group">
					<div class="col-lg-10 col-lg-offset-2">
						<button type="reset" class="btn btn-default">Cancel</button>
						<button type="submit" name="go" class="btn btn-primary">Installer</button>
					</div>
				</div>
				</fieldset>
			</form>
			<?php
			}
			else
			{
				#installation déja faite !
				$msg ='<div class="alert alert-dismissible alert-warning">';
				$msg .='<button type="button" class="close" data-dismiss="alert">X</button>';
				$msg .='la config du serveur est déja faite , veuillez vider le fichier constante.php pour pouvoir effectuer une nouvelle installation.';
				$msg .='</div>';
				echo $msg;
			}
			?>
			</div>
			<div class="col-lg-4"></div>
	</center>
	</body>
</html>