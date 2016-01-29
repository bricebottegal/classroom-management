<?php require('identification_check.php'); ?>
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
	<title><?php echo strtoupper($_SESSION['nom_professeur']).' '.$_SESSION['prenom'] ?> - Mes informations</title>
	<link href="styles/defaut.css" rel="stylesheet" title="style" type="text/css" />
	<script type="text/javascript" src="javascript/defaut.js"></script>
</head>
<body>
	<?php
		require('infos/connexion.php');
		$tableau = mysql_fetch_assoc(mysql_query('SELECT nom_professeur, prenom, email, date_inscription FROM gp_professeur WHERE id_professeur = '.$_SESSION['id_prof']));
		$_SESSION['id_salle'] = $_GET['id_salle'];
	?>
	<form id='ajout_prof' method='post' enctype='multipart/form-data'>
		<table class='voir_salle'>
			<tr>
				<td>Nom :</td><td><input name='nom' type='text' value='<?php echo $tableau['nom_professeur'] ?>' /></td>
			</tr>
			<tr>
				<td>Prenom :</td><td><input name='prenom' type='text' value='<?php echo $tableau['prenom'] ?>' /></td>
			</tr>
			<tr>
				<td>Date d'inscription</td><td><?php echo date('d/m/Y',$tableau['date_inscription']) ?></td>
			</tr>
			<tr>
				<td>Mot de passe actuel :</td><td><input name='mdp' type='password' /></td>
			</tr>
			<tr>
				<td>Nouveau le mot de passe :</td><td><input name='mdp1' type='password' /></td>
			</tr>
			<tr>
				<td>Entrez à nouveau le mot de passe :</td><td><input name='mdp2' type='password' /></td>
			</tr>
			<tr>
				<td>Email :</td><td><input name='email' type='text' value='<?php echo $tableau['email'] ?>' /></td>
			</tr>
			<tr>
				<td colspan='2' class='haut_menu'><input type='submit' value='Modifier' onclick='return verif_modif_membre()' /></td>
			</tr>
		</table>
	</form>
	<?php
		if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']))
		{
			if (preg_match("!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$!", $_POST['email']))
			{
				if (!empty($_POST['mdp']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2']))
				{
					if ($_POST['mdp1'] == $_POST['mdp2'])
					{
						require('infos/connexion.php');
						$tableau_professeur = mysql_fetch_assoc(mysql_query('SELECT mdp FROM gp_professeur WHERE id_professeur = '.$_SESSION['id_prof']));
						if ($_POST['mdp'] == $tableau_professeur['mdp'])
							$requete = 'UPDATE gp_professeur SET nom_professeur = \''.$_POST['nom'].'\', prenom = \''.$_POST['prenom'].'\', mdp = \''.$_POST['mdp1'].'\', email = \''.$_POST['email'].'\' WHERE id_professeur = '.$_SESSION['id_prof'];
						else
						{
							?>
							<script type='text/javascript'>
								alert("Le mot de passe actuelle n'est pas le bon.");
							</script>
							<?php
						}
					}
					else
					{
						?>
						<script type='text/javascript'>
							alert("Les deux nouveaux mots de passes sont différents");
						</script>
						<?php
					}
				}
				else
					$requete = 'UPDATE gp_professeur SET nom_professeur = \''.$_POST['nom'].'\', prenom = \''.$_POST['prenom'].'\', email = \''.$_POST['email'].'\' WHERE id_professeur = '.$_SESSION['id_prof'];
			}
			else
			{
				?>
					<script type='text/javascript'>
						alert("Adresse email non valide.");
					</script>
				<?php
			}
			if (!empty($requete))
			{
				if (mysql_query($requete))
				{
					?>
					<script type='text/javascript'>
						alert('Votre fiche a bien été modifiée');self.close();
					</script>
					<?php
				}
				else
				{
					?>
					<script type='text/javascript'>
						alert("Probleme lors de la modification");
					</script>
					<?php
				}
			}
		}
	?>
</body>
</html>