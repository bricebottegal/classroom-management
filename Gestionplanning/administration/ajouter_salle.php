<?php
require('../infos/connexion.php');
if (mysql_num_rows(mysql_query('SELECT id_semaine FROM gp_semaine WHERE id_semaine = 1')))
{
?>
	<form name='ajout_salle' action='#'>
		<table class='voir_salle'>
			<tr>
				<td>Auteur :</td><td><input name='auteur' type='text' /></td>
			</tr>
			<tr>
				<td>Nom de la salle :</td><td><input name='nom_salle' type='text' /></td>
			</tr>
			<tr>
				<td colspan='2' class='haut_menu'>
					<input type='button' value='Enregistrer' onclick="if (document.ajout_salle.auteur.value != '') { if (document.ajout_salle.nom_salle.value != '') { alert('La creation de la salle peut mettre jusqu\'à 20 sec selon la vitesse du serveur.');document.location.replace('index.php?menu_administration=ajouter_salle&auteur='+document.ajout_salle.auteur.value+'&nom_salle='+document.ajout_salle.nom_salle.value); } else alert('Le nom de la salle n\'a pas été saisie.') } else alert('L\'auteur de la salle n\'a pas été saisie.');" />
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
		alert('Les semaines ne sont pas créées. Il faut creer les semaines dans la partie super administration avant de pouvoir creer une salle.');
	</script>
	<?php
}
if (!empty($_GET['nom_salle']) && !empty($_GET['auteur']))
{
	require('../infos/connexion.php');
	if (!mysql_num_rows(mysql_query('SELECT nom_salle FROM gp_salle WHERE nom_salle = \''.$_GET['nom_salle'].'\'')))
	{
		$tableau = mysql_fetch_assoc(mysql_query('SELECT id_salle FROM gp_salle ORDER BY id_salle DESC LIMIT 1'));
		$num_salle = $tableau['id_salle']+1;
		if (mysql_query('INSERT INTO gp_salle values('.$num_salle.', \''.$_GET['nom_salle'].'\', '.time().', \''.$_GET['auteur'].'\')'))
		{
			$tableau_semaine = mysql_fetch_assoc(mysql_query('SELECT count(*) as nb_total FROM gp_semaine'));
			$tableau_cours = mysql_fetch_assoc(mysql_query('SELECT id_cours FROM gp_cours ORDER BY id_cours DESC LIMIT 1'));
			$nb_total_cours = $tableau_cours['id_cours']+1;
			$nb_semaine = 1;
			while ($nb_semaine <= $tableau_semaine['nb_total'])
			{
				$nb_jours = 1;
				while ($nb_jours <= 6)
				{
					$nb_cours = 1;
					while ($nb_cours <= 2)
					{
						mysql_query("INSERT INTO gp_cours(id_cours,id_salle,nom_professeur,prenom,id_semaine,id_jour) VALUES ($nb_total_cours, $num_salle, NULL, NULL, $nb_semaine, $nb_jours)");
						$nb_total_cours++;
						$nb_cours++;
					}
					$nb_jours++;
				}
				$nb_semaine++;
			}
			?>
			<script type="text/javascript">
				alert('La salle a bien été créé sous le numero '+'<?php echo $num_salle ?>'+' ainsi que ses cours.');
				document.location.replace('index.php');
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
				alert('Erreur lors de la creation de la salle.');
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script type="text/javascript">
			alert('Le nom de la salle éxiste déjà, veuillez en choisir un autre');
		</script>
		<?php
	}
}
?>