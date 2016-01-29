<?php
if (!empty($_GET['id_salle']))
{
	if (is_numeric($_GET['id_salle']))
	{
		require('infos/connexion.php');
		if (mysql_num_rows(mysql_query('SELECT id_salle FROM gp_salle WHERE id_salle = '.$_GET['id_salle'])))
		{
			$jour_aujourdhui = date('w',time());
			$timestamp = strtotime(date('d F Y',time()));
			if ($jour_aujourdhui != 1)
			{
				switch ($jour_aujourdhui)
				{
					case $jour_aujourdhui == 0:
						$timestamp -= 518400;
					break;
					case $jour_aujourdhui == 2:
						$timestamp -= 86400;
					break;
					case $jour_aujourdhui == 3:
						$timestamp -= 172800;
					break;
					case $jour_aujourdhui == 4:
						$timestamp -= 259200;
					break;
					case $jour_aujourdhui == 5:
						$timestamp -= 345600;
					break;
					case $jour_aujourdhui == 6:
						$timestamp -= 432000;
					break;
				}
			}
			// Nombre maximum de semaine possible à afficher
				$annee = date('Y',$timestamp);
				switch ($annee)
				{
					case $annee == 2006:
						$heure_ete = strtotime('26 March 2006');
						$heure_hiver = strtotime('30 October 2006');
					break;
					case $annee == 2007:
						$heure_ete = strtotime('25 March 2007');
						$heure_hiver = strtotime('29 October 2007');
					break;
					case $annee == 2008:
						$heure_ete = strtotime('30 March 2008');
						$heure_hiver = strtotime('26 October 2008');
					break;
					case $annee == 2009:
						$heure_ete = strtotime('29 March 2009');
						$heure_hiver = strtotime('26 October 2009');
					break;
					case $annee == 2010:
						$heure_ete = strtotime('28 March 2010');
						$heure_hiver = strtotime('01 November 2010');
					break;
					case $annee == 2011:
						$heure_ete = strtotime('27 March 2011');
						$heure_hiver = strtotime('31 October 2011');
					break;
				}
				// heure été ou hiver
				if ($timestamp >= $heure_ete && $timestamp < $heure_hiver)
					$timestamp += 3600;
				$tableau_id_semaine = mysql_fetch_assoc(mysql_query('SELECT id_semaine FROM gp_semaine WHERE timestamp_deb = '.$timestamp));
				$tableau_der_sem = mysql_fetch_assoc(mysql_query('SELECT id_semaine FROM gp_semaine ORDER BY id_semaine DESC LIMIT 1'));
				$nb_semaine_max = ($tableau_der_sem['id_semaine']-$tableau_id_semaine['id_semaine']);
			if (!empty($_GET['nb_semaine']))
			{
				if (is_numeric($_GET['nb_semaine']))
					$nb_semaine = $_GET['nb_semaine'];
			}
			else
			{
				$tableau = mysql_fetch_assoc(mysql_query('SELECT nb_semaine FROM gp_configuration'));
				$nb_semaine = $tableau['nb_semaine'];
			}
			// Si le nombre maximum est depassé, on le remet au maximum
				if ($nb_semaine > $nb_semaine_max)
					$nb_semaine = $nb_semaine_max;
			$non_trouve = 0;
			for ($deb=1;$deb<=$nb_semaine;$deb++)
			{
				if (!${'semaine_' . $deb} = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_semaine WHERE timestamp_deb = '.$timestamp)))
				{
				 $tableau = mysql_fetch_assoc(mysql_query('SELECT timestamp_deb FROM gp_semaine WHERE id_semaine=1'));
				 ${'semaine_' . $deb} = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_semaine WHERE timestamp_deb = '.$tableau['timestamp_deb']));
				 $timestamp = $tableau['timestamp_deb'];
				 $non_trouve = 1;
				}
				else
					${'semaine_' . $deb} = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_semaine WHERE timestamp_deb = '.$timestamp));
				${'donnees_' . $deb} = mysql_query('SELECT * FROM gp_cours WHERE id_semaine = '.${'semaine_' . $deb}['id_semaine'].' AND id_salle = '.$_GET['id_salle'].' ORDER BY id_cours');
				$timestamp += 604800;
			}
			$i = $deb;
			?>
			<form name='nb_semaine' action='#'>
				<table style='margin: auto'>
					<tr>
						<td>Nombre de semaine à afficher :</td>
						<td>
							<?php
								if ($non_trouve)
								{
									$tableau = mysql_fetch_assoc(mysql_query('SELECT id_semaine FROM gp_semaine ORDER BY id_semaine DESC LIMIT 1'));
									$nb_semaine = $tableau['id_semaine'];
								}
								else
									$nb_semaine = $nb_semaine_max;
								echo '<select name=\'nb_semaine\'>';
									for ($a=1;$a<=$nb_semaine;$a++)
										echo '<option value=\''.$a.'\'>'.$a.'</option>';
								echo '</select>';
							?>
						</td>
						<td>
							<input type='button' value='valider' onclick="document.location.replace('index.php?id_salle='+'<?php echo $_GET['id_salle'] ?>'+'&nb_semaine='+document.nb_semaine.nb_semaine.value)" />
						</td>
					</tr>
				</table>
			</form>
			<br/><br/>
			<?php
			if (!empty($_SESSION['statut']))
				echo '<table style=\'margin: auto;  background-color: #FFFFCC; width: 250px; border-collapse: collapse;\'><tr><td style=\'border: 1px #000000 dashed\'><a href="javascript:popup_centre(\'mes_informations.php?id_salle='.$_GET['id_salle'].'\',480,290)" title=\'Mes informations\'>Mes informations</a></td><td style=\'border: 1px #000000 dashed\'><a href="javascript:popup_centre(\'identification.php?deconnexion=1\',300,160)" title=\'Se deconnecter\'>Deconnection</a></td></tr></table><br/><br/>';
			else 
				echo '<div style=\'margin: auto; height: 15px\'><a href="javascript:popup_centre(\'identification.php\',300,160)" title=\'Se connecter\'>Connection</a></div><br/><br/>';
			?>
			<table class='conteneur'>
				<tr>
					<td>
						<table class='jour'>
							<tr>
								<td colspan='2' class='annee'>
									<?php 
										$tableau = mysql_fetch_assoc(mysql_query('SELECT nom_salle FROM gp_salle WHERE id_salle = '.$_GET['id_salle']));
										echo '<strong>'.$tableau['nom_salle'].'</strong>';
									?>
								</td>
							</tr>
							<tr>
							 <td rowspan='3' class='jour'>Lundi</td></tr>
							  <tr><td class='matin'>MATIN</td></tr>
							  <tr><td class='aprem'>APREM</td></tr>
							</tr>
							<tr>
							  <td rowspan='3' class='jour'>Mardi</td></tr>
							  <tr><td class='matin'>MATIN</td></tr>
							  <tr><td class='aprem'>APREM</td></tr>
							 </tr>
							 <tr>
								<td rowspan='3' class='jour'>Mercredi</td></tr>
								<tr><td class='matin'>MATIN</td></tr>
								<tr><td class='aprem'>APREM</td></tr>
							 </tr>
							 <tr>
							  <td rowspan='3' class='jour'>Jeudi</td></tr>
							  <tr><td class='matin'>MATIN</td></tr>
							  <tr><td class='aprem'>APREM</td></tr>
							</tr>
							<tr>
							  <td rowspan='3' class='jour'>Vendredi</td></tr>
							  <tr><td class='matin'>MATIN</td></tr>
							  <tr><td class='aprem'>APREM</td></tr>
							 </tr>
							 <tr>
							   <td rowspan='3' class='jour'>Samedi</td></tr>
							   <tr><td class='matin'>MATIN</td></tr>
							   <tr><td class='aprem'>APREM</td></tr>
							  </tr>
						</table>
					</td>
					<?php
					for ($deb=1;$deb<$i;$deb++)
					{
						echo '<td style=\'vertical-align: top\'>';
							echo '<div>';
							echo '<br/><br/><strong>'.date('d/m/y',${'semaine_' . $deb}['timestamp_deb']).'</strong> au <strong>'.date('d/m/y',${'semaine_' . $deb}['timestamp_fin']).'</strong>';
							echo '</div>';
							while ($tableau = mysql_fetch_assoc(${'donnees_'.$deb}))
							{
								// Si il y a un cours
									$lien = '';
									if ($tableau['statut'] != 'vide')
									{
										// Si c'est un admin
											if (!empty($_SESSION['statut']))
											{
												if ($_SESSION['statut'] == 'admin')
												{
													if ($tableau['statut'] == 'attente')
														$lien = '<a href="javascript:popup_centre(\'voir_reservation.popup.php?id_cours='.$tableau['id_cours'].'\',600,440)" title=\'Voir la fiche du cours numero '.$tableau['id_cours'].'\'>';
													else
														$lien = '<a href="javascript:popup_centre(\'voir_reservation.popup.php?id_cours='.$tableau['id_cours'].'\',400,315)" title=\'Voir la fiche du cours numero '.$tableau['id_cours'].'\'>';
												}
											}
										// Couleur de fond
											if ($tableau['statut'] == 'ok')
											{
												if ($tableau['heure_fin'] <= 12)
													echo '<div style=\'background-color: #CADBFF\'>'.$lien;
												else
													echo '<div style=\'background-color: #A6B8FF\'>'.$lien;
											}
											else
												echo '<div style=\'background-color: #FFFFA6\'>'.$lien;
										// Contenu
											echo '<br/>'.$tableau['matiere'].'<br/>'.$tableau['type_tp'].'<br/>'.strtoupper($tableau['nom_professeur']).' '.$tableau['prenom'].'<br/> De '.$tableau['heure_deb'].'h à '.$tableau['heure_fin'].'h';
											if (!empty($lien))
												echo '</a></div>';
											else
												echo '</div>';
									}
								// Aucun cours
									else
									{
										// Si c'est un admin
											if (!empty($_SESSION['statut']))
											{
												if ($_SESSION['statut'] == 'admin')
													$lien = '<a class=\'libre\' href="javascript:popup_centre(\'ajouter_reservation.popup.php?id_cours='.$tableau['id_cours'].'\',350,275)" title=\'Ajouter un cours ici\'>';
												else
													$lien = '<a class=\'libre\' href="javascript:popup_centre(\'reservation.php?id_cours='.$tableau['id_cours'].'\',340,240)" title=\'Demande de reservation\'>';
											}
										else
											$lien = '<a class=\'libre\' href="javascript:popup_centre(\'identification.php?id_salle='.$tableau['id_salle'].'\',300,160)" title=\'Demande de reservation\'>';
										echo '<div style=\'background-color: #33CC99\'>'.$lien.'<br/><br/>Aucun cours</a></div>';
									}
							}
						echo '</td>';
					}
				echo '</tr>';
			echo '</table>';
		}
		else
		{
			?>
				<script type="text/javascript">alert('La salle spécifiée n\'éxiste pas');</script>
			<?php
		}
	}
	else
	{
		?>
			<script type="text/javascript">alert('La numero de la salle n\'est pas valide');</script>
		<?php
	}
}
else
{
	?>
		<script type="text/javascript">alert('Vous devez spécifier un numero de salle avant de consulter son planning');</script>
	<?php
}
?>