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
	<title>Reservation de cours</title>
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
	 $tableau_cours = mysql_fetch_assoc(mysql_query('SELECT id_jour FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	 $tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT id_semaine FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	 $tableau_salle = mysql_fetch_assoc(mysql_query('SELECT id_salle FROM gp_cours WHERE id_cours = '.$_GET['id_cours']));
	 $tableau_cfg =  mysql_fetch_assoc(mysql_query('SELECT * FROM gp_configuration'));
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
					<td class='haut_menu' colspan='2' style='border-bottom: 1px solid; height: 40px'>Demande de reservation de cours</td>
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
					<td>Filière :</td>
					<td><input type='text' name='filiere'/></td>
				</tr>
				<tr>
					<td>Type TP :</td>
					<td><input type='text' name='type_tp'/></td>
				</tr>
				<tr>
					<td class='haut_menu' colspan='2' style='border-top: 1px solid; height: 40px'>
						<input type='submit' value='Reserver' onclick='return verif_modif_cours();' />
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
if (!empty($_GET['filiere']) && !empty($_GET['type_tp']) && !empty($_GET['heure_deb']) && !empty($_GET['heure_fin']))
{
	if (is_numeric($_GET['heure_deb']) && is_numeric($_GET['heure_fin']))
	{
		require('infos/connexion.php');
		$tableau_cours = mysql_fetch_assoc(mysql_query('SELECT id_salle, id_semaine, id_jour FROM gp_cours WHERE id_cours = '.$_SESSION['id_cours']));
		$tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT timestamp_deb, timestamp_fin FROM gp_semaine WHERE id_semaine = '.$tableau_cours['id_semaine']));
		$tableau_salle = mysql_fetch_assoc(mysql_query('SELECT nom_salle FROM gp_salle WHERE id_salle = '.$tableau_cours['id_salle']));
		$tableau_cfg =  mysql_fetch_assoc(mysql_query('SELECT * FROM gp_configuration'));
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
		$objet = 'Demande de RESERVATION pour : '.$_SESSION['nom_professeur'].' '.$_SESSION['prenom'].' le '.$date;
		$headers = 'From: "GESTION PLANNING" <no-reply@univ-lehavre.fr>'."\n";
		$headers .= 'Reply-To: '.$_SESSION['email']."\n";
		$headers .= 'Content-Type: text/html; charset="iso-8859-1"'."\n";
		$headers .= 'Content-Transfer-Encoding: 8bit';
		$message = '<html><head><title>Demande de reservation</title></head><body><p>Demande de reservation effectué le <strong>'.date('d/m/y à H\hi\m\i\n').'</strong><br/><br/>';
		$message .= 'Professeur : <strong>'.$_SESSION['nom_professeur'].' '.$_SESSION['prenom'].'</strong><br/>';
		$message .= 'Salle : <strong>'.$tableau_salle['nom_salle'].'</strong><br/>';
		$message .= 'Semaine : <strong>'.date('d/m/y',$tableau_semaine['timestamp_deb']).'</strong> au <strong>'.date('d/m/y',$tableau_semaine['timestamp_fin']).'</strong><br/>';
		$message .= 'Jour : <strong>'.$jour.'</strong><br/>';
		$message .= 'Heure de début du cours : <strong>'.$_GET['heure_deb'].'</strong><br/>';
		$message .= 'Heure de fin du cours : <strong>'.$_GET['heure_fin'].'</strong><br/>';
		$message .= 'Filière : <strong>'.$_GET['filiere'].'</strong><br/>';
		$message .= 'Type de TP : <strong>'.$_GET['type_tp'].'</strong><br/>';
		$message .= '<br/><br/><a href=\'http://172.17.20.181/index.php?id_salle='.$tableau_cours['id_salle'].'\'>Cliquez-ici pour accepter ou refuser le cours</a><br/><br/>';
		$message .= '</p></body></html>';
		if (mysql_query('UPDATE gp_cours SET nom_professeur = \''.$_SESSION['nom_professeur'].'\', prenom = \''.$_SESSION['prenom'].'\', heure_deb = '.$_GET['heure_deb'].', heure_fin = '.$_GET['heure_fin'].', matiere = \''.$_GET['filiere'].'\', type_tp = \''.$_GET['type_tp'].'\', statut = \'attente\' WHERE id_cours = '.$_SESSION['id_cours']))
		{
			?>
			<script type="text/javascript">
				opener.location.reload();
			</script>
			<?php
			$compteur = 0;
			$mail = 0;
			$mail_echoue = 0;
			for ($i=1;$i<=5;$i++)
			{
			 if (!empty($tableau_cfg['email'.$i]))
			 {
				$compteur++;
				if (mail($tableau_cfg['email'.$i],$objet,$message,$headers))
					$mail++;
				else
					$mail_echoue++;
			 }
			}
			if ($compteur)
			{
				if ($mail)
				{
					?>
					<script type='text/javascript'>
						alert('Demande de reservation effectuée et email(s) de demande d\'acception envoyé(s).');self.close();
					</script>
					<?php
				}
				elseif ($mail_echoue)
				{
					?>
					<script type='text/javascript'>
						alert('Demande de reservation effectuée mais l\'envois d\'email(s) de demande d\'acception a échoué.');self.close();
					</script>
					<?php
				}

			}
			else
			{
				?>
				<script type='text/javascript'>
					alert('Demande de reservation effectuée.');self.close();
				</script>
				<?php
			}
		}
		else
		{
			?>
			<script type='text/javascript'>
				alert('Problème lors de la demande de reservation.');self.close();
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script type='text/javascript'>
			alert('Les champs renseignés lors de la reservation ne sont pas tous sous la forme numérique.');self.close();
		</script>
		<?php
	}
}
?>
</body>
</html>