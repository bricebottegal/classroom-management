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
	<title>Ajouter une reservation</title>
	<link href="styles/defaut.css" rel="stylesheet" title="style" type="text/css" />
	<script type="text/javascript" src="javascript/defaut.js"></script>
</head>
<body>
<?php
if (!empty($_GET['id_cours']))
{
 require('infos/connexion.php');
 if (mysql_num_rows(mysql_query('SELECT id_cours FROM gp_cours WHERE id_cours = '.$_GET['id_cours'])))
 {
   if (mysql_num_rows(mysql_query('SELECT id_professeur FROM gp_professeur WHERE statut = \'professeur\'')))
   {
	  $tableau_cours = mysql_fetch_assoc(mysql_query('SELECT id_jour FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	  $tableau_salle = mysql_fetch_assoc(mysql_query('SELECT id_salle FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	  $tableau_cfg =  mysql_fetch_assoc(mysql_query('SELECT * FROM gp_configuration'));
	  $tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT id_semaine FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	  $tableau = mysql_fetch_assoc(mysql_query('SELECT id_cours FROM gp_cours WHERE id_salle = '.$tableau_salle['id_salle'].' AND id_semaine = '.$tableau_semaine['id_semaine'].' AND id_jour = '.$tableau_cours['id_jour'].' AND id_cours != '.$_GET['id_cours']));
	  if ($tableau['id_cours'] > $_GET['id_cours'])
		$moment = 'matin';
	  else
		$moment = 'aprem';
	  $_SESSION['id_cours'] = $_GET['id_cours'];
	  ?>
	  <form id='modif_cours' action='#'>
		<table class='voir_salle'>
			<tr>
				<td class='haut_menu' colspan='2' style='border-bottom: 1px solid; height: 40px'>Ajout d'une reservation de cours</td>
			</tr>
			<tr>
				<td>Heure de début :</td>
				<td>
					<select name='heure_deb'>
						<?php
							if ($moment == 'matin')
							{
								for ($i=$tableau_cfg['heure_mat_deb'];$i<=$tableau_cfg['heure_mat_fin']-1;$i++)
									echo '<option value=\''.$i.'\'>'.$i.'h</option>';
							}
							else
							{
								for ($i=$tableau_cfg['heure_aprem_deb'];$i<=$tableau_cfg['heure_aprem_fin']-1;$i++)
									echo '<option value=\''.$i.'\'>'.$i.'h</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Heure de fin :</td>
				<td>
					<select name='heure_fin'>
						<?php
							if ($moment == 'matin')
							{
								for ($i=$tableau_cfg['heure_mat_deb']+1;$i<=$tableau_cfg['heure_mat_fin'];$i++)
									echo '<option value=\''.$i.'\'>'.$i.'h</option>';
							}
							else
							{
								for ($i=$tableau_cfg['heure_aprem_deb']+1;$i<=$tableau_cfg['heure_aprem_fin'];$i++)
									echo '<option value=\''.$i.'\'>'.$i.'h</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Professeur :</td>
				<td>
					<select name='id_prof'>
					<?php
						$donnees = mysql_query('SELECT id_professeur, nom_professeur, prenom FROM gp_professeur WHERE statut = \'professeur\'');
						while ($tableau = mysql_fetch_assoc($donnees))
							echo '<option value=\''.$tableau['id_professeur'].'\'>'.strtoupper($tableau['nom_professeur']).' '.$tableau['prenom'].'</option>';
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Filière :</td><td><input type='text' name='filiere' /></td>
			</tr>
			<tr>
				<td>Type de tp :</td><td><input type='text' name='type_tp' /></td>
			</tr>
			<tr>
				<td class='haut_menu' colspan='2' style='border-top: 1px solid; height: 40px'>
					<input type='submit' value='Enregistrer' onclick='return verif_modif_cours()' />
					<input type='button' value='Fermer' onclick='self.close()' />
				</td>
			</tr>
		</table>
	</form>
<?php
	}
	else
	{
		?>
		<script type="text/javascript">
			alert('Nous ne pouvez pas ajouter de cours si aucun professeur n\'a été enregistré dans la base de donnée.');self.close();
		</script>
		<?php
	}
 }
 else
 {
	?>
	<script type="text/javascript">
		alert('Ce cours n\'existe pas.');self.close();
	</script>
	<?php
 }
}
if (!empty($_GET['filiere']) && !empty($_GET['type_tp']) && !empty($_GET['id_prof']) && !empty($_GET['heure_deb']) && !empty($_GET['heure_fin']))
{
 if (is_numeric($_GET['id_prof']) && is_numeric($_GET['heure_deb']) && is_numeric($_GET['heure_fin']))
 {
	require('infos/connexion.php');
	$tableau_prof = mysql_fetch_assoc(mysql_query('SELECT nom_professeur, prenom FROM gp_professeur WHERE id_professeur = '.$_GET['id_prof']));
	if (mysql_query('UPDATE gp_cours SET nom_professeur = \''.$tableau_prof['nom_professeur'].'\', prenom = \''.$tableau_prof['prenom'].'\', heure_deb = '.$_GET['heure_deb'].', heure_fin = '.$_GET['heure_fin'].', matiere = \''.$_GET['filiere'].'\', type_tp = \''.$_GET['type_tp'].'\', statut = \'ok\' WHERE id_cours = '.$_SESSION['id_cours']))
	{
		?>
		<script type="text/javascript">
			opener.location.reload();
			alert('Reservation effectuée.');self.close();
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
			alert('Problème lors de la reservation');self.close();
		</script>
		<?php
	}
 }
 else
 {
		?>
		<script type="text/javascript">
			alert('Les champs renseignés lors de la reservation ne sont pas tous sous la forme numérique.');self.close();
		</script>
		<?php
 }
}
?>
</body>
</html>