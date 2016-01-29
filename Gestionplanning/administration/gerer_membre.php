<table style='margin: auto'>
	<tr>
		<td>
			<?php
			require('../infos/connexion.php');
			if (!empty($_GET['suppression']))
			{
				$tableau = mysql_fetch_assoc(mysql_query('SELECT nom_professeur FROM gp_professeur WHERE id_professeur = '.$_GET['suppression']));
				if (mysql_query('UPDATE gp_cours SET nom_professeur = NULL, prenom = NULL, heure_deb = 0, heure_fin = 0, matiere = NULL, type_tp = NULL, statut = \'vide\' WHERE nom_professeur = \''.$tableau['nom_professeur'].'\'') && mysql_query('DELETE FROM gp_professeur WHERE id_professeur ='.$_GET['suppression']))
				{
					?>
						<script type='text/javascript'>alert('Le professeur numero '+'<?php echo $_GET['suppression'] ?>'+' a été correctement supprimé');document.location.replace('index.php?menu_administration=gerer_membre');</script>
					<?php
				}
				else
				{
					?>
						<script type='text/javascript'>alert('Problème lors de la suppression du professeur numero '+'<?php echo $_GET['suppression'] ?>');document.location.replace('index.php?menu_administration=gerer_membre');</script>
					<?php
				}
			}
			else
			{
				if (mysql_num_rows(mysql_query('SELECT id_professeur FROM gp_professeur')))
				{
					$requete = 'SELECT * FROM gp_professeur ORDER BY id_professeur';
					if (!empty($_GET['tri']))
					{
						switch ($_GET['tri'])
						{
							case $_GET['tri'] == 'statut' && $_SESSION['tri'] == 'statut':
								$requete = 'SELECT * FROM gp_professeur ORDER BY statut';
								unset($_SESSION['tri']);
							break;
							case $_GET['tri'] == 'date' && $_SESSION['tri'] == 'date':
								$requete = 'SELECT * FROM gp_professeur ORDER BY date_inscription';
								unset($_SESSION['tri']);
							break;
							case $_GET['tri'] == 'statut':
								$requete = 'SELECT * FROM gp_professeur ORDER BY statut DESC';
								$_SESSION['tri'] = 'statut';
							break;
							case $_GET['tri'] == 'date':
								$requete = 'SELECT * FROM gp_professeur ORDER BY date_inscription DESC';
								$_SESSION['tri'] = 'date';
							break;
						}
					}
					echo '<table class=\'tab_gestion\'>';
					echo '<tr><td class=\'haut_menu\' style=\'width: 80px;\'>Numéro</td><td class=\'haut_menu\' style=\'width: 200px\'>Nom</td><td class=\'haut_menu\' style=\'width: 150px\'>Prenom</a></td><td class=\'haut_menu\' style=\'width: 250px\'><a class=\'tab_gestion\' href=\'index.php?menu_administration=gerer_membre&tri=date\' title=\'Trier par date d\'inscription\'>Date d\'inscription</a></td><td class=\'haut_menu\' style=\'width: 250px\'>Email</td><td class=\'haut_menu\' style=\'width: 250px\'><a class=\'tab_gestion\' href=\'index.php?menu_administration=gerer_membre&tri=statut\' title=\'Trier par statut \'>Statut</a></td><td class=\'haut_menu\' colspan=\'3\'></td></tr>';
					$donnees = mysql_query($requete);
					while ($tableau = mysql_fetch_array($donnees))
					{
						if ($tableau['statut'] == 'professeur')
						{
							if (mysql_num_rows(mysql_query('SELECT nom_professeur FROM gp_cours WHERE nom_professeur = \''.$tableau['nom_professeur'].'\'')))
								$confirm = 'Ce professeur a des cours reservés sous son nom. Voulez vous supprimer ces cours puis supprimer le professeur ensuite ?';
							else
								$confirm = 'Êtes-vous sûr de vouloir supprimer ce professeur ? (Aucun cours reservé)';
						}
						else
							$confirm = 'Êtes-vous sûr de vouloir supprimer cet administrateur ?';
						echo '<tr><td>'.$tableau['id_professeur'].'</td><td>'.$tableau['nom_professeur'].'</td><td>'.$tableau['prenom'].'</td><td>'.date('d/m/Y',$tableau['date_inscription']).'</td><td>'.$tableau['email'].'</td><td>'.$tableau['statut'].'</td><td style=\'width: 25px\'><a href="javascript:popup_centre(\'voir_membre.popup.php?voir='.$tableau['id_professeur'].'\',340,255)" title=\'Voir le professeur numero '.$tableau['id_professeur'].'\'><img border=\'0\' src=\'../images/b_voir.gif\' alt=\'Voir\'></a></td><td style=\'width: 25px\'><a href="javascript:popup_centre(\'voir_membre.popup.php?modification='.$tableau['id_professeur'].'\',500,318)" title=\'Modifier le professeur numero '.$tableau['id_professeur'].'\'><img border=\'0\' src=\'../images/b_edit.png\' alt=\'Modifier\'></td><td style=\'width: 25px\'><a href=\'index.php?menu_administration=gerer_membre&suppression='.$tableau['id_professeur'].'\' title=\'Supprimer le professeur numero '.$tableau['id_professeur'].'\' onclick="return confirm(\''.$confirm.'\')" /><img border=\'0\' src=\'../images/b_suppr.png\' alt=\'Supprimer\'></a></td></tr>';
					}
					echo '</table>';
				}
				else
				{
					echo 'Aucun membres n\'est inscrit';
					?>
						<script type='text/javascript'>
							alert('Aucun membre n\'est enregistré dans la base de donnée');
							document.location.replace('index.php');
						</script>
					<?php
				}
			}
			?>
		</td>
	</tr>
</table>
