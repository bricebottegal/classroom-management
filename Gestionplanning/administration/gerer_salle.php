<table style='margin: auto'>
	<tr>
		<td>
			<?php
			require('../infos/connexion.php');
			if (!empty($_GET['suppression']))
			{
				if (mysql_query('DELETE FROM gp_cours WHERE id_salle = '.$_GET['suppression']) && mysql_query('DELETE FROM gp_salle WHERE id_salle ='.$_GET['suppression']))
				{
					?>
						<script type='text/javascript'>alert('La salle numero '+'<?php echo $_GET['suppression'] ?>'+' a été correctement supprimé');document.location.replace('index.php?menu_administration=gerer_salle');</script>
					<?php
				}
				else
				{
					?>
						<script type='text/javascript'>alert('Problème lors de la suppression de la salle numero '+'<?php echo $_GET['suppression'] ?>');document.location.replace('index.php?menu_administration=gerer_salle');</script>
					<?php
				}
			}
			else
			{
				if (mysql_num_rows(mysql_query('SELECT id_salle FROM gp_salle')))
				{
					$requete = 'SELECT * FROM gp_salle ORDER BY id_salle';
					if (!empty($_GET['tri']))
					{
						switch ($_GET['tri'])
						{
							case $_GET['tri'] == 'date' && $_SESSION['tri'] == 'date':
								$requete = 'SELECT * FROM gp_salle ORDER BY date_creation';
								unset($_SESSION['tri']);
							break;
							case $_GET['tri'] == 'auteur' && $_SESSION['tri'] == 'createur':
								$requete = 'SELECT * FROM gp_salle ORDER BY createur';
								unset($_SESSION['tri']);
							break;
							case $_GET['tri'] == 'date':
								$requete = 'SELECT * FROM gp_salle ORDER BY date_creation DESC';
								$_SESSION['tri'] = 'date';
							break;
							case $_GET['tri'] == 'auteur':
								$requete = 'SELECT * FROM gp_salle ORDER BY createur DESC';
								$_SESSION['tri'] = 'createur';
							break;
						}
					}
					echo '<table class=\'tab_gestion\'>';
					echo '<tr><td class=\'haut_menu\' style=\'width: 100px;\'>Numéro</td><td class=\'haut_menu\' style=\'width: 200px\'><a class=\'tab_gestion\' href=\'index.php?menu_administration=gerer_salle&tri=date\' title=\'Trier par date de creation\'>Date de création</a></td><td class=\'haut_menu\' style=\'width: 150px\'><a class=\'tab_gestion\' href=\'index.php?menu_administration=gerer_salle&tri=auteur\' title=\'Trier par createur \'>Créateur</a></td><td class=\'haut_menu\' style=\'width: 250px\'>Nom de la salle</td><td class=\'haut_menu\' colspan=\'3\'></td></tr>';
					$donnees = mysql_query($requete);
					while ($tableau = mysql_fetch_array($donnees))
					{
						if (mysql_num_rows(mysql_query('SELECT id_salle FROM gp_cours WHERE id_salle = '.$tableau['id_salle'].' AND prenom != \'NULL\'')))
							$confirm = 'Des cours ont étés reservés pour cette salle. Voulez vous supprimer les cours puis supprimer la salle ensuite ?';
						else
							$confirm = 'Êtes-vous sûr de vouloir supprimer cette salle ? (Aucun cours reservé)';
						echo '<tr><td>'.$tableau['id_salle'].'</td><td>'.date('d/m/y', $tableau['date_creation']).'</td><td>'.$tableau['createur'].'</td><td>'.$tableau['nom_salle'].'</td><td style=\'width: 25px\'><a href="javascript:popup_centre(\'voir_salle.popup.php?voir='.$tableau['id_salle'].'\',400,222)" title=\'Voir la salle numero '.$tableau['id_salle'].'\'><img border=\'0\' src=\'../images/b_voir.gif\' alt=\'Voir\'></a></td><td style=\'width: 25px\'><a href="javascript:popup_centre(\'voir_salle.popup.php?modification='.$tableau['id_salle'].'\',400,222)" title=\'Modifier la salle numero '.$tableau['id_salle'].'\'><img border=\'0\' src=\'../images/b_edit.png\' alt=\'Modifier\'></td><td style=\'width: 25px\'><a href=\'index.php?menu_administration=gerer_salle&suppression='.$tableau['id_salle'].'\' title=\'Supprimer la salle numero '.$tableau['id_salle'].'\' onclick="return confirm(\''.$confirm.'\')" /><img border=\'0\' src=\'../images/b_suppr.png\' alt=\'Supprimer\'></a></td></tr>';
					}
					echo '</table>';
				}
				else
				{
					echo 'Aucunne salle n\'a été créée';
					?>
						<script type='text/javascript'>
							alert('Aucunne salle n\'a été créée.');
							document.location.replace('index.php');
						</script>
					<?php
				}				
			}
			?>
		</td>
	</tr>
</table>
