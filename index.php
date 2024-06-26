<?php
	//variables du formulaire vendreArticle
	$Nom='';
	$Prenom='';
	$Pays='';
	$Email='';
	$Tel=''; 
	$Fichier='';
	$Prix='';
	$Message='';
	
	$tabConversion=array(	
						'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
						'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 
						'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
						'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
						'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
						'Œ' => 'oe', 'œ' => 'oe',
						'ç' => 'c',
						' ' => '', '-'=> ''
						);
	
	$messageError="";
	$validationOK=False;
	// Vérification du formulaire vendreArticle
	if(isset($_POST['submit']))
	{
		// Vérification du nom (au moins 3 lettres et pas de chiffre) 
		if(  (!isset($_POST['nom']))||(strlen(trim($_POST['nom']))<2))
		{ 
			$messageError.="<li>Nom --> le nom doit avoir plus de 3 lettres</li>";
			echo "<style>#nom{background-color:red;}</style>";
		}
		else{
			$NomLower=strtolower(trim($_POST['nom']));  	// suppression des espaces devant et derrière 
			if(strlen($NomLower)>=3){
				if(preg_match("#[a-z]#i",$NomLower) and preg_match("#[^a-z]#i",$NomLower))
				{
					$messageError.="<li>Nom --> le nom doit être composé de seulement des lettres</li>";
					echo "<style>#nom{background-color:red;}</style>";
				}
				else{
					$NomTab=strtr($NomLower,$tabConversion);
					if(!ctype_alpha($NomTab))
					{ 
						$messageError.="<li>Nom --> le nom ne contient pas des lettres bizarre</li>";
						echo "<style>#nom{background-color:red;}</style>";
					}
					else{
						$Nom=ucfirst($NomTab);
					}
				}
			}
			else
			{
				$messageError.="<li>Nom --> le nom doit avoir plus de 3 lettres</li>";
				echo "<style>#nom{background-color:red;}</style>";
			}	
		}
			
		// Vérification du prenom (au moins 2 lettre et pas de chiffre)
		if(!isset($_POST['prenom'])||(strlen(trim($_POST['prenom']))<2))			// la variable prenom est pas positionnée
		{ 
			$messageError.='<li>Prénom --> le prénom doit avoir plus de 3 lettres</li>';
			echo "<style>#prenom{background-color:red;}</style>";
		}
		else{
			$PrenomLower=strtolower(trim($_POST['prenom']));  	// suppression des espaces devant et derrière 
			if(strlen($PrenomLower)>=3){
				if(preg_match("#[a-z]#i",$PrenomLower) and preg_match("#[^a-z]#i",$PrenomLower))
				{
					$messageError.="<li>Prénom --> le prénom doit être composé de seulement des lettres</li>";
					echo "<style>#prenom{background-color:red;}</style>";
				}
				else{
					$PrenomTab=strtr($PrenomLower,$tabConversion);
					if(!ctype_alpha($PrenomTab))
					{ 
						$messageError.="<li>Prénom --> le prénom ne contient pas des lettres bizarre</li>";
						echo "<style>#prenom{background-color:red;}</style>";
					}
					else
					{
						$Prenom=ucfirst($PrenomTab);
					}
				}
			}
			else
			{
				$messageError.="<li>Prénom --> le prénom doit avoir plus de 3 lettres</li>";
				echo "<style>#prenom{background-color:red;}</style>";
			}
		}
			  
		// Vérification du pays 
		if(!isset($_POST['pays'])||(!in_array(trim($_POST['pays']),array("Allemagne","France","Luxembourg","Espagne","Portugal","Belgique","Pays-Bas","Pologne","Chine","Japon"))))
		{ 
			$messageError.="<li>Pays --> le champ est vide</li>";
			echo "<style>#pays{background-color:red;}</style>";
		}
		else
		{
			$Pays=ucfirst($_POST['pays']);
		}
		
		//Verification du numero telephone
		if(!isset($_POST['tel'])){
			$messageError.="<li>N°Téléphone --> le numéro est composé de seulement de 9 chiffres </li>";
			echo "<style>#tel{background-color:red;}</style>";
		}
		else
		{
			$tel=trim($_POST['tel']);
			if(strlen($tel)!=9 and !is_numeric($tel))
			{
				$messageError.="<li>N°Téléphone --> le numéro est composé de seulement de 9 chiffres </li>";
				echo "<style>#tel{background-color:red;}</style>";
			}
			else{
				$Tel=$tel;
			}
			
		}
		
		// Vérification adresse electronique
		if((!isset($_POST['email'])) 			// la variable email n'est pas positionnée
		   ||(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)))
		{ 
			$messageError.="<li>Email--> le champ est vide ou l'email n'existe pas</li>";
			echo "<style>#email{background-color:red;}</style>";
		}
		else
		{
			$Email=$_POST['email'];
		}
		
		//Vérification de l'image bien telecharger
		if(!isset($_POST['fichier']))
		{
			$messageError.="<li>Fichier non sélectionné</li>";
			"<style>#fichier{background-color:red;}</style>";
		}
		else
		{
			// Vérifie si le fichier a été uploadé sans erreur.
			$allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
			$filename = $_POST["fichier"];

			// Vérifie l'extension du fichier
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if(!array_key_exists($ext, $allowed))
			{
				$messageError.="<li>Fichier non sélectionné</li>";
				echo "<style>#fichier{background-color:red;}</style>";
			}
		
		}
		
		
		// Vérification prix d'achat
		if(  (!isset($_POST['prix']))||(strlen(trim($_POST['prix']))<1))
		{ 
			$messageError.="<li>Prix --> le champ est vide</li>";
			echo "<style>#prix{background-color:red;}</style>";
		}
		else
		{
			if(is_numeric(trim($_POST['prix'])))
			{
				$Prix=$_POST['prix'];
			}
			else
			{
				$messageError.="<li>Prix de l'article--> le prix est numérique</li>";
				echo "<style>#prix{background-color:red;}</style>";
			}
			
		}
		
		// verfication de la description de l'article
		if(!isset($_POST['message']))
		{
			$messageError.="<li>Message --> le champ est vide</li>";
			echo "<style>#message{background-color:red;}</style>";
		}
		else
		{
			if(strlen(trim($_POST['message']))<10)
			{
				$messageError.="<li>Message --> le message doit contenir 10 caractères sans espaces</li>";
				echo "<style>#message{background-color:red;}</style>";
			}
			else
			{
				$Message=trim($_POST['message']);
			}
		}
		
		// Verification validation du formulaire
		if($messageError=='')
		{		
			$validationOK=True;
		}
		else
		{
			$validationOK=False;
		}	
		
	}
	
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		
		<title> Achat&Revendre </title>
		<meta charset="utf-8" />
		<style>
			body{
				background-image: linear-gradient(to bottom right, #CCFFFF, #CC99FF);
				font-family:"Comic Sans MS", cursive, sans-serif;
				color:black;
				font-size:1.5em;
			}
			
			h1{ font-family:"Courier New",cursive,monospace;
				font-weight:bold;
				color:DeepSkyBlue;
				text-decoration:underline;
				margin-top:20px;
			}
			p a, td a{
				color:black;
				font-weight:bold;
			}
			p a:hover, td a:hover{
				text-decoration:none;
				font-weight:bold;
				color:red;
			}
			legend,h2{
				color:CornflowerBlue;
				font-weight:bold;
				text-decoration:underline;
			}
			main {
				margin-top:0px;
				margin-bottom:50px;
			}
			
			.panel-footer{
				color:grey;
				font-weight:bold;
				position:fixed;
				left: 0;
				bottom: 0;
				width: 100%;
				text-align: center;
			}
			.panel-footer a {
				color:grey;
			}
			.radio{
				margin-left:20px;
			}
			main .form-horizontal{
				margin-left:10%;
				margin-right:10%;
			}
			#form{
				text-align:center;
			}
			#message{
				width: 100%;
				height: 150px;
				padding: 12px 20px;
				box-sizing: border-box;
				border: 2px solid #ccc;
				border-radius: 4px;
				background-color: #FFF5EE;
				font-size: 16px;
				resize: none;
			}
			
			.form-horizontal label{
				margin-top:10px;
			}
			.form-horizontal legend+label{
				margin-top:0px;
			}
			.form-horizontal .form-group{
				margin-left:1px;
			}
			nav a{
				font-weight:bold;
				color:CornflowerBlue;
				font-size:21px;
			}
			nav a:hover{
				font-weight:bold;
				color:PaleVioletRed;
			}
			td{
				text-align:justify;
			}
			table{
				background-color:white;
			}
			th{
				color:rgb(204, 51, 255);
				font-weight:bold;
				font-size:18px;
			}

		</style>

	</head>
	<body>
	<header>
			<nav class="navbar navbar-inverse" >
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="index.php?p=mainPage">
							Achat&Revendre
						</a>
					</div>
						
					<ul class="nav navbar-nav">
						<li ><a href="index.php?p=mainPage">Menu</a></li>
						<li><a href="index.php?p=vendreArticle">Store</a></li>
						<li><a href="index.php?p=shop">Shop</a></li>
						<li><a href="index.php?p=contact">Contact</a></li>
					</ul>
					
				</div>
			</nav>
			
	</header>
		
		<main>
		<div class="container-fluid">
		<?php
		//rechercher les pages associer à p
		if(!isset($_GET['p']))
		{
			$_GET['p']='mainPage';
		}
		
		if(isset($_GET['p'])) 
		{ 
			if($_GET['p']=='vendreArticle')
			{
				// validation
				if ($validationOK and isset($_POST['submit'])) {
					if($_POST['sexe']=='f')
					{
						$Sexe="Mme";
					}
					else
					{
						$Sexe="Mr";
					}
					
					$Categorie=$_POST['categorie'];
					$EtreContacter=$_POST["contact"];
					
					$message=$Sexe.". ".$Nom." ".$Prenom." de ".$Pays.
							" veut vendre un article de categorie ".$Categorie." pour ".$Prix."\n"."Le propriétaire a décrit son article: 
							\n".$Message."\n".$Sexe.". ".$Nom." veut être contacter par ".$EtreContacter." .";
					
					file_put_contents('vendreArticle.txt', $message);
					
					echo "<div class='alert alert-success'>
						<p><strong>Success!</strong> Le formulaire est bien rempli, 
						Dans les 24 heures votre article sera en ligne</p>
						</div>";
					exit;
				}
				else
				{
					if(isset($_POST['submit']))
					{
						echo "<div class='alert alert-danger'>
						<p><strong>Error!</strong> Le formulaire n'est pas bien rempli!!</p>
						<p>Veuillez corriger les champs séléctionnés en rouge:<ul>$messageError</ul></p>
						</div>";
					}
				}
		
				$fichier=$_GET['p'].'.php';
			}
			else
			{
				$fichier=$_GET['p'].'.html';
			}
		}
		
		if(file_exists($fichier))
		{
			include($fichier);
		}
		else 
		{
			echo "Erreur 404 : la page demandée n’existe pas";
		}
	
		?>
		</div>
		</main>
		<footer>
			<div class="panel-footer">
				<p>Copyright <a href="index.php?p=contact">Josiane Rocha Fernandes</a></p>
			</div>
		</footer>
	</div>
	</body>
</html>