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
	<title></title>
	<link href="styles/defaut.css" rel="stylesheet" title="style" type="text/css" />
	<script type="text/javascript" src="javascript/defaut.js"></script>
</head>
<body>
	<?php
	if (!empty($_GET['id_cours']))
	{
	 require('infos/connexion.php');
	 if (mysql_num_rows(mysql_query('SELECT id_cours FROM gp_cours WHERE id_cours = '.$_GET['id_cours'].' AND prenom != \'NULL\'')))
	 {
	  $tableau_cours = mysql_fetch_assoc(mysql_query('SELECT id_jour FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	  $tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT id_semaine FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	  $tableau = mysql_fetch_assoc(mysql_query('SELECT id_cours FROM gp_cours WHERE id_jour = '.$tableau_cours['id_jour'].' AND id_semaine = '.$tableau_semaine['id_semaine'].' AND id_cours != '.$_GET['id_cours']));
	  if ($tableau['id_cours'] > $_GET['id_cours'])
		 $moment = 'matin';
	  else
		 $moment = 'aprem';
	  $_SESSION['id_cours'] = $_GET['id_cours'];
	  ?>
	  <form name='modif_cours' id='modif_cours' action='#'>
		<table class='voir_salle'>
			<?php
				$tableau = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
			    $tableau_cfg =  mysql_fetch_assoc(mysql_query('SELECT * FROM gp_configuration'));
			?>
			<tr>
				<td>Numero du cours :</td><td><?php echo $tableau['id_cours'] ?></td>
			</tr>
			<tr>
				<td>Salle :</td>
				<td>
					<?php
						$tableau_salle = mysql_fetch_assoc(mysql_query('SELECT nom_salle FROM gp_salle WHERE id_salle = '.$tableau['id_salle']));
						echo $tableau_salle['nom_salle'];
					?>
				</td>
			</tr>
			<tr>
				<td>Professeur :</td>
				<td>
					<?php
					if ($tableau['statut'] == 'attente')
						echo strtoupper($tableau['nom_professeur']).' '.$tableau['prenom'];
					else
					{
						echo '<select name=\'id_professeur\'>';
						$donnees = mysql_query('SELECT id_professeur, nom_professeur, prenom FROM gp_professeur WHERE statut = \'professeur\'');
						while ($tableau_prof = mysql_fetch_assoc($donnees))
						{
							if ($tableau_prof['nom_professeur'] == $tableau['nom_professeur'])
								echo '<option class=\'en_cours\' value=\''.$tableau_prof['id_professeur'].'\' selected=\'selected\'>'.strtoupper($tableau_prof['nom_professeur']).' '.$tableau_prof['prenom'].'</option>';
							else
								echo '<option value=\''.$tableau_prof['id_professeur'].'\'>'.strtoupper($tableau_prof['nom_professeur']).' '.$tableau_prof['prenom'].'</option>';
						}
						echo '</select>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Date :</td>
				<td>
					<?php
					$tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT timestamp_deb, timestamp_fin FROM gp_semaine WHERE id_semaine = '.$tableau['id_semaine']));
					$timestamp = $tableau_semaine['timestamp_deb'];
					switch ($tableau['id_jour'])
					{
						case $tableau['id_jour'] == 1:
							$jour = 'Lundi';
						break;
						case $tableau['id_jour'] == 2:
							$jour = 'Mardi';
							$timestamp += 86400;
						break;
						case $tableau['id_jour'] == 3:
							$jour = 'Mercredi';
							$timestamp += 172800;
						break;
						case $tableau['id_jour'] == 4:
							$jour = 'Jeudi';
							$timestamp += 259200;
						break;
						case $tableau['id_jour'] == 5:
							$jour = 'Vendredi';
							$timestamp += 345600;
						break;
						case $tableau['id_jour'] == 6:
							$jour = 'Samedi';
							$timestamp += 432000;
						break;
					}
					$numero_jour = date('d', $timestamp);
					$mois_eng = date('F', $timestamp);
					switch ($mois_eng)
					{
						case $mois_eng == 'January':
							$mois = 'Janvier';
						break;
						case $mois_eng == 'February':
							$mois = 'Fevrier';
						break;
						case $mois_eng == 'March':
							$mois = 'Mars';
						break;
						case $mois_eng == 'April':
							$mois = 'Avril';
						break;
						case $mois_eng == 'May':
							$mois = 'Mai';
						break;
						case $mois_eng == 'June':
							$mois = 'Juin';
						break;
						case $mois_eng == 'July':
							$mois = 'Juillet';
						break;
						case $mois_eng == 'August':
							$mois = 'Août';
						break;
						case $mois_eng == 'September':
							$mois = 'Septembre';
						break;
						case $mois_eng == 'October':
							$mois = 'Octobre';
						break;
						case $mois_eng == 'November':
							$mois = 'Novembre';
						break;
						case $mois_eng == 'December':
							$mois = 'Decembre';
						break;
					}
					echo $jour.' '.$numero_jour.' '.$mois.' '.date('Y', $timestamp);
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Heure de début :</td>
				<td>	
					<?php
					if ($tableau['statut'] == 'attente')
						echo $tableau['heure_deb'].'h';
					else
					{
						echo '<select name=\'heure_deb\'>';
						if ($moment == 'matin')
						{
							for ($i=$tableau_cfg['heure_mat_deb'];$i<=$tableau_cfg['heure_mat_fin']-1;$i++)
							{
								if ($i == $tableau['heure_deb'])
									echo '<option value=\''.$i.'\' selected=\'selected\' class=\'en_cours\'>'.$i.'</option>';
								else
									echo '<option value=\''.$i.'\'>'.$i.'</option>';
							}
						}
						else
						{
							for ($i=$tableau_cfg['heure_aprem_deb'];$i<=$tableau_cfg['heure_aprem_fin']-1;$i++)
							{
								if ($i == $tableau['heure_deb'])
									echo '<option value=\''.$i.'\' selected=\'selected\' class=\'en_cours\'>'.$i.'</option>';
								else
									echo '<option value=\''.$i.'\'>'.$i.'</option>';
							}
						}
						echo '</select>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Heure de fin :</td>
				<td>
					<?php
					if ($tableau['statut'] == 'attente')
						echo $tableau['heure_fin'].'h';
					else
					{
						echo '<select name=\'heure_fin\'>';
						if ($moment == 'matin')
						{
							for ($i=$tableau_cfg['heure_mat_deb']+1;$i<=$tableau_cfg['heure_mat_fin'];$i++)
							{
								if ($i == $tableau['heure_fin'])
									echo '<option value=\''.$i.'\' selected=\'selected\' class=\'en_cours\'>'.$i.'</option>';
								else
									echo '<option value=\''.$i.'\'>'.$i.'</option>';
							}
						}
						else
						{
							for ($i=$tableau_cfg['heure_aprem_deb']+1;$i<=$tableau_cfg['heure_aprem_fin'];$i++)
							{
								if ($i == $tableau['heure_fin'])
									echo '<option value=\''.$i.'\' selected=\'selected\' class=\'en_cours\'>'.$i.'</option>';
								else
									echo '<option value=\''.$i.'\'>'.$i.'</option>';
							}
						}
						echo '</select>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Filière :</td>
				<td>
					<?php
						if ($tableau['statut'] == 'attente')
							echo $tableau['matiere'];
						else
							echo '<input type=\'text\' name=\'filiere\' value=\''.$tableau['matiere'].'\' />';
					?>
				</td>
			</tr>
			<tr>
				<td>Type de tp :</td>
				<td>
					<?php
					if ($tableau['statut'] == 'attente')
						echo $tableau['type_tp'];
					else
						echo '<input type=\'text\' name=\'type_tp\' value=\''.$tableau['type_tp'].'\' />';
					?>
				</td>
			</tr>
			<?php
			if ($tableau['statut'] == 'attente')
			{
			?>
			<tr>
				<td>Si il y a refus, veuillez en entrer ici la raison :</td>
				<td>
					<textarea name='raison_refus' rows='6' cols='30'>Non communiquée</textarea>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan='2' class='haut_menu'>
					<?php
					if ($tableau['statut'] == 'attente')
					{
					?>
						<input type='button' value='Accepter' onclick="if (confirm('Êtes-vous sûr de vouloir accepter ce cours ?')) document.location.replace('voir_reservation.popup.php?accepter='+'<?php echo $tableau['id_cours'] ?>')" />
						<input type='button' value='Refuser' onclick="if (confirm('Êtes-vous sûr de vouloir refuser ce cours ?')) document.location.replace('voir_reservation.popup.php?suppression='+'<?php echo $tableau['id_cours'] ?>'+'&refus=1&raison_refus='+document.modif_cours.raison_refus.value)" />
					<?php
					}
					else
					{
					?>
						<input type='submit' value='Modifier' onclick='return verif_modif_cours()' />
						<input type='button' value='Supprimer' onclick="if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) document.location.replace('voir_reservation.popup.php?suppression='+'<?php echo $tableau['id_cours'] ?>')" />
					<?php
					}
					?>
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
			alert('Ce cours n\'existe pas.');self.close();
		</script>
		<?php
	 }
	}
	if (!empty($_GET['id_professeur']) && !empty($_GET['heure_deb']) && !empty($_GET['heure_fin']) && !empty($_GET['filiere']) && !empty($_GET['type_tp']))
	{
		require('infos/connexion.php');
		if (is_numeric($_GET['id_professeur']) && is_numeric($_GET['heure_deb']) && is_numeric($_GET['heure_fin']))
		{
			$tableau_prof = mysql_fetch_assoc(mysql_query('SELECT nom_professeur, prenom FROM gp_professeur WHERE id_professeur = '.$_GET['id_professeur']));
			if (mysql_query('UPDATE gp_cours SET nom_professeur = \''.$tableau_prof['nom_professeur'].'\', prenom = \''.$tableau_prof['prenom'].'\', heure_deb = '.$_GET['heure_deb'].', heure_fin = '.$_GET['heure_fin'].', matiere = \''.$_GET['filiere'].'\', type_tp = \''.$_GET['type_tp'].'\' WHERE id_cours = '.$_SESSION['id_cours']))
			{
				?>
				<script type='text/javascript'>
					opener.location.reload();
					alert('Cours numero '+'<?php echo $_SESSION['id_cours'] ?>'+' modifié.');self.close();
				</script>
				<?php
			}
			else
			{
				?>
				<script type='text/javascript'>
					alert('Problème lors de la modification du cours numéro '+'<?php echo $_SESSION['id_cours'] ?>'+'.');self.close();
				</script>
				<?php
			}
		}
		else
		{
			?>
			<script type='text/javascript'>
				alert('Les champs renseignés lors de la modification ne sont pas tous sous la forme numérique.');self.close();
			</script>
			<?php
		}
	}
	if (!empty($_GET['suppression']))
	{
	 require('infos/connexion.php');
	 if (mysql_num_rows(mysql_query('SELECT id_cours FROM gp_cours WHERE id_cours = '.$_GET['suppression'].' AND prenom != \'NULL\'')))
	 {
		if (!empty($_GET['refus']))
		{
			$tableau_cours = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_cours WHERE id_cours = '.$_GET['suppression']));
			$tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT timestamp_deb, timestamp_fin FROM gp_semaine WHERE id_semaine = '.$tableau_cours['id_semaine']));
			$tableau_salle = mysql_fetch_assoc(mysql_query('SELECT nom_salle FROM gp_salle WHERE id_salle = '.$tableau_cours['id_salle']));
			$tableau_prof = mysql_fetch_assoc(mysql_query('SELECT email FROM gp_professeur WHERE nom_professeur = \''.$tableau_cours['nom_professeur'].'\''));
			$timestamp = strtotime(date('d F Y',$tableau_semaine['timestamp_deb']));
			switch ($tableau_cours['id_jour'])
			{
				case $tableau_cours['id_jour'] == 1:
					$jour = 'Lundi';
				break;
				case $tableau_cours['id_jour'] == 2:
					$jour = 'Mardi';
					$timestamp += 86400;
				break;
				case $tableau_cours['id_jour'] == 3:
					$jour = 'Mercredi';
					$timestamp += 172800;
				break;
				case $tableau_cours['id_jour'] == 4:
					$jour = 'Jeudi';
					$timestamp += 259200;
				break;
				case $tableau_cours['id_jour'] == 5:
					$jour = 'Vendredi';
					$timestamp += 345600;
				break;
				case $tableau_cours['id_jour'] == 6:
					$jour = 'Samedi';
					$timestamp += 432000;
				break;
			}
			$numero_jour = date('d', $timestamp);
			$mois_eng = date('F', $timestamp);
			switch ($mois_eng)
			{
				case $mois_eng == 'January':
					$mois = 'Janvier';
				break;
				case $mois_eng == 'February':
					$mois = 'Fevrier';
				break;
				case $mois_eng == 'March':
					$mois = 'Mars';
				break;
				case $mois_eng == 'April':
					$mois = 'Avril';
				break;
				case $mois_eng == 'May':
					$mois = 'Mai';
				break;
				case $mois_eng == 'June':
					$mois = 'Juin';
				break;	
				case $mois_eng == 'July':
					$mois = 'Juillet';
				break;
				case $mois_eng == 'August':
					$mois = 'Août';
				break;
				case $mois_eng == 'September':
					$mois = 'Septembre';
				break;
				case $mois_eng == 'October':
					$mois = 'Octobre';
				break;
				case $mois_eng == 'November':
					$mois = 'Novembre';
				break;
				case $mois_eng == 'December':
					$mois = 'Decembre';
				break;
			}
			$date = $jour.' '.$numero_jour.' '.$mois.' '.date('Y', $timestamp);
			$objet = 'REFUS de la reservation pour le cours de '.$tableau_cours['matiere'].' '.$tableau_cours['type_tp'].' du '.$date;
			$headers = 'From: "GESTION PLANNING" <no-reply@univ-lehavre.fr>'."\n";
			$headers .= 'Reply-To: vincent.loisel@univ-lehavre.fr'."\n";
			$headers .= 'Content-Type: text/html; charset="iso-8859-1"'."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit';
			$message = '<html><head><title>Refus de reservation</title></head><body><p>Demande de reservation refusé le <strong>'.date('d/m/y à H\hi\m\i\n').'</strong><br/><br/>';					
			$message .= 'Salle : <strong>'.$tableau_salle['nom_salle'].'</strong><br/>';
			$message .= 'Semaine : <strong>'.date('d/m/y',$tableau_semaine['timestamp_deb']).'</strong> au <strong>'.date('d/m/y',$tableau_semaine['timestamp_fin']).'</strong><br/>';
			$message .= 'Jour : <strong>'.$jour.'</strong><br/>';
			$message .= 'Heure de début du cours : <strong>'.$tableau_cours['heure_deb'].'</strong><br/>';
			$message .= 'Heure de fin du cours : <strong>'.$tableau_cours['heure_fin'].'</strong><br/>';
			$message .= 'Filière : <strong>'.$tableau_cours['matiere'].'</strong><br/>';
			$message .= 'Type de TP : <strong>'.$tableau_cours['type_tp'].'</strong><br/>';
			$message .= 'Raison du refus : <strong>'.nl2br($_GET['raison_refus']).'</strong>';
			$message .= '</p></body></html>';
			if (mysql_query('UPDATE gp_cours SET nom_professeur = NULL, prenom = NULL, heure_deb = 0, heure_fin = 0, matiere = NULL, type_tp = NULL, statut = \'vide\' WHERE id_cours = '.$_GET['suppression']))
			{
				?>
				<script type='text/javascript'>
					opener.location.reload();
				</script>
				<?php
				if (mail($tableau_prof['email'],$objet,$message,$headers))
				{
					?>
					<script type='text/javascript'>
						alert('Reservation refusé et email de refus envoyé au professeur.');self.close();
					</script>
					<?php
				}
				else
				{
					?>
					<script type='text/javascript'>
						alert('Refus de la reservation prise en compte mais l\'envois de l\'email a echoué');self.close();
					</script>
					<?php
				}
			}
			else
			{
			?>
			<script type='text/javascript'>
				alert('Problème lors du refus de la reservation.');self.close();
			</script>
			<?php
			}
		}
		else
		{
			if (mysql_query('UPDATE gp_cours SET nom_professeur = NULL, prenom = NULL, heure_deb = 0, heure_fin = 0, matiere = NULL, type_tp = NULL, statut = \'vide\' WHERE id_cours = '.$_GET['suppression']))
			{
				?>
				<script type='text/javascript'>
					opener.location.reload();
					alert('Suppression effectuée.');self.close();
				</script>
				<?php

			}
			else
			{
				?>
				<script type='text/javascript'>
					alert('Problème lors de la suppression.');self.close();
				</script>
				<?php
			}
		}
	 }
	 else
	 {
		?>
		<script type='text/javascript'>
			alert('Ce cours n\'existe pas.');self.close();
		</script>
		<?php
	 }
	}
	if (!empty($_GET['accepter']))
	{
		require('infos/connexion.php');
		if (mysql_num_rows(mysql_query('SELECT id_cours FROM gp_cours WHERE id_cours = '.$_GET['accepter'].' AND prenom != \'NULL\'')))
		{
			$tableau_cours = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_cours WHERE id_cours = '.$_GET['accepter']));
			$tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT timestamp_deb, timestamp_fin FROM gp_semaine WHERE id_semaine = '.$tableau_cours['id_semaine']));
			$tableau_salle = mysql_fetch_assoc(mysql_query('SELECT nom_salle FROM gp_salle WHERE id_salle = '.$tableau_cours['id_salle']));
			$tableau_prof = mysql_fetch_assoc(mysql_query('SELECT email FROM gp_professeur WHERE nom_professeur = \''.$tableau_cours['nom_professeur'].'\''));
			$timestamp = strtotime(date('d F Y',$tableau_semaine['timestamp_deb']));
			switch ($tableau_cours['id_jour'])
			{
				case $tableau_cours['id_jour'] == 1:
					$jour = 'Lundi';
				break;
				case $tableau_cours['id_jour'] == 2:
					$jour = 'Mardi';
					$timestamp += 86400;
				break;
				case $tableau_cours['id_jour'] == 3:
					$jour = 'Mercredi';
					$timestamp += 172800;
				break;
				case $tableau_cours['id_jour'] == 4:
					$jour = 'Jeudi';
					$timestamp += 259200;
				break;
				case $tableau_cours['id_jour'] == 5:
					$jour = 'Vendredi';
					$timestamp += 345600;
				break;
				case $tableau_cours['id_jour'] == 6:
					$jour = 'Samedi';
					$timestamp += 432000;
				break;
			}
			$numero_jour = date('d', $timestamp);
			$mois_eng = date('F', $timestamp);
			switch ($mois_eng)
			{
				case $mois_eng == 'January':
					$mois = 'Janvier';
				break;
				case $mois_eng == 'February':
					$mois = 'Fevrier';
				break;
				case $mois_eng == 'March':
					$mois = 'Mars';
				break;
				case $mois_eng == 'April':
					$mois = 'Avril';
				break;
				case $mois_eng == 'May':
					$mois = 'Mai';
				break;
				case $mois_eng == 'June':
					$mois = 'Juin';
				break;	
				case $mois_eng == 'July':
					$mois = 'Juillet';
				break;
				case $mois_eng == 'August':
					$mois = 'Août';
				break;
				case $mois_eng == 'September':
					$mois = 'Septembre';
				break;
				case $mois_eng == 'October':
					$mois = 'Octobre';
				break;
				case $mois_eng == 'November':
					$mois = 'Novembre';
				break;
				case $mois_eng == 'December':
					$mois = 'Decembre';
				break;
			}
			$date = $jour.' '.$numero_jour.' '.$mois.' '.date('Y', $timestamp);
			$objet = 'ACCEPTATION de la reservation pour le cours de '.$tableau_cours['matiere'].' '.$tableau_cours['type_tp'].' du '.$date;
			$headers = 'From: "GESTION PLANNING" <no-reply@univ-lehavre.fr>'."\n";
			$headers .= 'Reply-To: vincent.loisel@univ-lehavre.fr'."\n";
			$headers .= 'Content-Type: text/html; charset="iso-8859-1"'."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit';
			$message = '<html><head><title>Refus de reservation</title></head><body><p>Demande de reservation accepté le <strong>'.date('d/m/y à H\hi\m\i\n').'</strong><br/><br/>';					
			$message .= 'Salle : <strong>'.$tableau_salle['nom_salle'].'</strong><br/>';
			$message .= 'Semaine : <strong>'.date('d/m/y',$tableau_semaine['timestamp_deb']).'</strong> au <strong>'.date('d/m/y',$tableau_semaine['timestamp_fin']).'</strong><br/>';
			$message .= 'Jour : <strong>'.$jour.'</strong><br/>';
			$message .= 'Heure de début du cours : <strong>'.$tableau_cours['heure_deb'].'</strong><br/>';
			$message .= 'Heure de fin du cours : <strong>'.$tableau_cours['heure_fin'].'</strong><br/>';
			$message .= 'Filière : <strong>'.$tableau_cours['matiere'].'</strong><br/>';
			$message .= 'Type de TP : <strong>'.$tableau_cours['type_tp'].'</strong><br/>';
			$message .= '</p></body></html>';
			if (mysql_query('UPDATE gp_cours SET statut = \'ok\' WHERE id_cours = '.$_GET['accepter']))
			{
				?>
				<script type='text/javascript'>
					opener.location.reload();
				</script>
				<?php
				if (mail($tableau_prof['email'],$objet,$message,$headers))
				{
					?>
					<script type='text/javascript'>
						alert('Reservation acceptée et email d\'acceptation envoyé au professeur.');self.close();
					</script>
					<?php
				}
				else
				{
					?>
					<script type='text/javascript'>
						alert('Reservation acceptée mais l\'envois de l\'email a echoué');self.close();
					</script>
					<?php
				}
			}
			else
			{
				?>
				<script type='text/javascript'>
					alert('Problème lors de l\'acceptation de la reservation.');self.close();
				</script>
				<?php
			}
		}
		else
		{
			?>
			<script type='text/javascript'>
				alert('Ce cours n\'existe pas.');self.close();
			</script>
			<?php
		}
	}
	?>
</body>
</html>