<?php
	$ip = explode('.',$ip);
	$ip = sha1($ip_m1[0].'13'.$ip_m1[1].'12'.$ip_m1[2].'11'.$ip_m1[3]);
	session_name($ip);
	session_start();
	if (!empty($_GET['deconnexion']))
	{
		if ($_GET['deconnexion'] == 1)
		{
			$_SESSION=array();
			session_destroy();
			?>
			<script type='text/javascript'>
				opener.location.reload();
				document.location.replace('identification.php');
			</script>
			<?php
			die();
		}
	}
?>
<!-- 
	Script de gestion de planning developpé par BOTTEGAL BRICE pour la FAC du havre, département sciences et technique dans le cadre d'un stage pour ma 1ère année de BTS informatique et gestion.
	Durée de réalisation : 2 semaines.
	Langages utilisés : php, xhtml, css, javascript, sql. 
	Courriel : bricetouket@hotmail.com
 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Identification</title>
	<link href="styles/defaut.css" rel="stylesheet" title="style" type="text/css" />
	<script type="text/javascript" src="javascript/defaut.js"></script>
</head>
<body>
	<form id='identification' action='#' method='post' enctype='multipart/form-data'>
		<table class='voir_salle'>
			<thead>
				<tr>
					<td class='haut_menu' colspan='2' style='border-bottom: 1px solid'>Connexion</td>
				</tr>
			</thead>
			<tr>
				<td style='height: 31px;'>Nom d'utilisateur :</td><td class='jaune'><input type='text' name='nom_util' /></td>
			</tr>
			<tr>
				<td style='height: 29px'>Mot de passe :</td><td class='jaune'><input type='password' name='mdp' /></td>
			</tr>
			<tfoot>
				<tr>
					<td class='haut_menu' colspan='2' style='border-top: 1px solid'><a href='#' onclick='return verif_connexion()' title='Entrer'>Entrer</a></td>
				</tr>
			</tfoot>
		</table>
	</form>
<?php
if (!empty($_POST['nom_util']) && !empty($_POST['mdp']))
{
	require('infos/connexion.php');
	if ($tableau = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_professeur WHERE nom_professeur = \''.escapeshellcmd($_POST['nom_util']).'\'')))
	{
		if ($tableau['mdp'] == escapeshellcmd($_POST['mdp']))
		{
			?>
			<script type='text/javascript'>
				opener.location.reload();
			</script>
			<?php
			$fichier = fopen('infos/logs.txt','a');
			$texte = "\n\nLe ".date('d/m/y').' à '.date('H\h i\m\i\n s\s\e\c').', connexion de '.$_POST['nom_util'].'. Adresse IP : '.$_SERVER['REMOTE_ADDR'];
				fputs($fichier,$texte);
			fclose($fichier);
			$_SESSION['id_prof'] = $tableau['id_professeur'];
			$_SESSION['statut'] = $tableau['statut'];
			$_SESSION['email'] = $tableau['email'];
			$_SESSION['nom_professeur'] = $_POST['nom_util'];
			$_SESSION['prenom'] = $tableau['prenom'];
			$_SESSION['ip'] = sha1($_SERVER['REMOTE_ADDR']);
			$_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT']);
			$_SESSION['derniere_acces'] = time();
			?>
				<script type='text/javascript'>alert('Bienvenue '+'<?php echo strtoupper($_SESSION['nom_professeur']).' '.$_SESSION['prenom'] ?>'+'.');self.close();</script>
			<?php
		}
		else
		{
			$_SESSION=array(); 
			session_destroy();
			?>
				<script type='text/javascript'>alert('Mot de passe ou nom d\'utilisateur erroné(s)');document.location.replace('identification.php');</script>
			<?php
		}
	}
	else
	{
		$_SESSION=array(); 
		session_destroy();
		?>
			<script type='text/javascript'>alert('Mot de passe ou nom d\'utilisateur erroné(s)');document.location.replace('identification.php');</script>
		<?php
	}
}
?>
</body>
</html>