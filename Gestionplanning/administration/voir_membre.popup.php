<?php session_start() ?>
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
	<title>Voir fiche membre</title>
	<link href="../styles/defaut.css" rel="stylesheet" title="style" type="text/css" />
	<script type="text/javascript" src="../javascript/defaut.js"></script>
</head>
<body>
<?php
if (!empty($_GET['voir']) || !empty($_GET['modification']))
{
	if (!empty($_GET['voir']))
		$id = $_GET['voir'];
	else
		$id = $_GET['modification'];
	require('../infos/connexion.php');
	$tableau = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_professeur WHERE id_professeur = '.$id));
	$_SESSION['id_professeur'] = $id;
	?>
	<form id='ajout_prof' method='post' enctype='multipart/form-data'>
	<table class='voir_salle'>
		<tr>
			<td class='haut_menu'>Nom :</td>
			<td>
			<?php 
				if (!empty($_GET['modification']))
					echo '<input name=\'nom\' type=\'text\' value=\''.$tableau['nom_professeur'].'\' />';
				else
					echo $tableau['nom_professeur'];
			?>
			</td>
		</tr>
		<tr>
			<td class='haut_menu'>Prenom :</td>
			<td>
			<?php 
				if (!empty($_GET['modification']))
					echo '<input name=\'prenom\' type=\'text\' value=\''.$tableau['prenom'].'\' />';
				else
					echo $tableau['prenom'];
			?>
			</td>
		</tr>
		<tr>	
			<td class='haut_menu'>Date d'inscription</td><td><?php echo date('d/m/Y',$tableau['date_inscription']) ?></td>
		</tr>
		<tr>	
			<td class='haut_menu'>Mot de passe</td><td><?php echo $tableau['mdp'] ?></td>
		</tr>
		<?php if (!empty($_GET['modification'])) { ?>
		<tr>
			<td class='haut_menu'>Nouveau mot de passe :</td><td><input name='mdp1' type='password' /></td>
		</tr>
		<tr>
			<td class='haut_menu'>Entrez à nouveau le mot de passe :</td><td><input name='mdp2' type='password' /></td>
		</tr>
		<?php } ?>
		<tr>
			<td class='haut_menu'>Email :</td>
			<td>
			<?php 
				if (!empty($_GET['modification']))
					echo '<input name=\'email\' type=\'text\' size=\'35\'value=\''.$tableau['email'].'\' />';
				else
					echo $tableau['email'];
			?>
			</td>
		</tr>
		<tr>
			<td class='haut_menu'>Statut :</td>
			<td>
				<?php
				if (!empty($_GET['modification']))
				{
					if ($tableau['statut'] == 'professeur')
					{
						?>
						<select name='statut'>
							<option class='en_cours' value='professeur' selected='selected'>Professeur</option>
							<option value='admin'>Administrateur</option>
						</select>
						<?php
					}
					else
					{
						?>
						<select name='statut'>
							<option value='professeur'>Professeur</option>
							<option class='en_cours' value='admin' selected='selected'>Administrateur</option>
						</select>
						<?php
					}
				}
				else
					echo $tableau['statut'];
				?>
			</td>
		</tr>
		<tr>
			<tr>
				<td colspan='2' class='haut_menu'>
				<?php
					if (!empty($_GET['modification']))
						echo '<input type=\'submit\' value=\'Modifier\' onclick=\'return verif_modif_membre()\' />';
				?>
					<input type='button' value='Fermer' onclick='self.close()' />
				</td>
			</tr>
		</table>
		</form>
<?php
}
if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['statut']))
{
	if (preg_match("!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$!", $_POST['email']))
	{
		if (!empty($_POST['mdp1']) && !empty($_POST['mdp2']))
		{
			if ($_POST['mdp1'] == $_POST['mdp2'])
				$requete = 'UPDATE gp_professeur SET nom_professeur = \''.$_POST['nom'].'\', prenom = \''.$_POST['prenom'].'\', mdp = \''.$_POST['mdp1'].'\', email = \''.$_POST['email'].'\', statut = \''.$_POST['statut'].'\' WHERE id_professeur = '.$_SESSION['id_professeur'];
			else
			{
				?>
				<script type='text/javascript'>
					alert("Les deux nouveaux mots de passes sont différents");self.close();
				</script>
				<?php
			}
		}
		else
			$requete = 'UPDATE gp_professeur SET nom_professeur = \''.$_POST['nom'].'\', prenom = \''.$_POST['prenom'].'\', email = \''.$_POST['email'].'\', statut = \''.$_POST['statut'].'\' WHERE id_professeur = '.$_SESSION['id_professeur'];
	}
	else
	{
		?>
		<script type="text/javascript">
			alert('L\'adresse email est invalide.');
		</script>
		<?php
	}
	if (!empty($requete))
	{
		require('../infos/connexion.eea');
		if (mysql_query($requete))
		{
			?>
			<script type='text/javascript'>
				opener.location.reload();
				alert('Fiche de '+'<?php echo $_POST['nom'].' '.$_POST['prenom'] ?>'+' modifiée');self.close();
			</script>
			<?php
		}
		else
		{
			?>
			<script type='text/javascript'>
				alert("Probleme lors de la modification");self.close();
			</script>
			<?php
		}
	}
}
?>
</body>
</table>