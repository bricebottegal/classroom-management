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
	<title>Voir salle</title>
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
	$tableau = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_salle WHERE id_salle = '.$id));
	$_SESSION['id_salle'] = $id;
	?>
	<form id='modif_salle' action='#'>
	<table class='voir_salle'>
			<tr>
				<td class='haut_menu'>Numero de la salle :</td><td><?php echo $tableau['id_salle'] ?></td>
			</tr>
			<tr>
				<td class='haut_menu'>Date de creation :</td><td><?php echo date('d/m/y',$tableau['date_creation']).' à '.date('H\hi\m\i\n',$tableau['date_creation']) ?></td>
			</tr>
			<tr>
				<td class='haut_menu'>Auteur :</td>
				<td>
				<?php 
					if (!empty($_GET['modification']))
						echo '<input name=\'auteur\' type=\'text\' value=\''.$tableau['createur'].'\' />';
					else 
						echo $tableau['createur'];
				?>
				</td>
			</tr>
			<tr>
				<td class='haut_menu'>Nom de la salle :</td>
				<td>
					<?php 
						if (!empty($_GET['modification']))
							echo '<input name=\'nom_salle\' type=\'text\' value=\''.$tableau['nom_salle'].'\' />';
						else
							echo $tableau['nom_salle'];
					?>
				</td>
			</tr>
			<tr>
				<td class='haut_menu'>Nombre de cours reservé(s) :</td>
				<td>
					<?php
						$tableau = mysql_fetch_assoc(mysql_query('SELECT COUNT(*) AS nb_total FROM gp_cours WHERE id_salle = '.$tableau['id_salle'].' AND prenom != \'NULL\''));
						echo $tableau['nb_total'];
					?>
				</td>
			</tr>
			<tr>
				<td colspan='2' class='haut_menu'>
				<?php
					if (!empty($_GET['modification']))
						echo '<input type=\'submit\' value=\'Modifier\' onclick=\'return verif_modif()\' />';
				?>
					<input type='button' value='Fermer' onclick='self.close()' />
				</td>
			</tr>
		</table>
		</form>
<?php
}
if (!empty($_GET['nom_salle']) && !empty($_GET['auteur']))
{
	require('../infos/connexion.php');
	if (mysql_query('UPDATE gp_salle SET createur = \''.$_GET['auteur'].'\', nom_salle = \''.$_GET['nom_salle'].'\' WHERE id_salle = '.$_SESSION['id_salle']))
	{
		?>
		<script type='text/javascript'>
			opener.location.reload();
			alert("Salle modifiée");self.close();
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
?>
</body>
</table>