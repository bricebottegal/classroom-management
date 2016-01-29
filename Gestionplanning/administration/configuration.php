<?php
	require('../infos/connexion.php');
	$tableau = mysql_fetch_assoc(mysql_query('SELECT * FROM gp_configuration'));
	if (!empty($_GET['nb_semaine']) && !empty($_GET['heure_mat_deb']) && !empty($_GET['heure_mat_fin']) && !empty($_GET['heure_aprem_deb']) && !empty($_GET['heure_aprem_fin']))
	{
		if (is_numeric($_GET['nb_semaine']) && is_numeric($_GET['heure_mat_deb']) && is_numeric($_GET['heure_mat_fin']) && is_numeric($_GET['heure_aprem_deb']) && is_numeric($_GET['heure_aprem_fin']))
		{
			$requete = 'UPDATE gp_configuration SET nb_semaine = '.$_GET['nb_semaine'].', heure_mat_deb = '.$_GET['heure_mat_deb'].', heure_mat_fin = '.$_GET['heure_mat_fin'].', heure_aprem_deb = '.$_GET['heure_aprem_deb'].', heure_aprem_fin = '.$_GET['heure_aprem_fin'];
			for ($i=1;$i<=5;$i++)
			{
				if (!empty($_GET['email'.$i]))				 
				{
					if (preg_match("!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$!", $_GET['email'.$i]))
						$requete .= ', email'.$i.' = \''.$_GET['email'.$i].'\'';
				}
				else
					$requete .= ', email'.$i.' = \'\'';
			}
			require('../infos/connexion.php');
			if (mysql_query($requete))
			{
				?>
				<script type="text/javascript">
					alert('Configuration modifié.');
				</script>
				<?php
			}
			else
			{
				?>
				<script type="text/javascript">
					alert('Problème lors de la modification de la configuration.');document.location.replace('index.php');
				</script>
				<?php
			}
		}
		else
		{
			?>
			<script type="text/javascript">
				alert('Le nombre de semaine doit etre sous la forme numérique.');document.location.replace('index.php');
			</script>
			<?php
		}
	}
	else
	{
		?>
		<form name='config' id='config' action="#" method="get">
			<table class='voir_salle'>
					<tr>
						<td>Nombre de semaine affichée(s) au démarrage :</td><td><input type='text' name='nb_semaine' value='<?php echo $tableau['nb_semaine'] ?>' /></td>
					</tr>
					<tr>
						<td>Heure de début des cours de la matinée :</td><td><input type='text' name='heure_mat_deb' value='<?php echo $tableau['heure_mat_deb'] ?>' /></td>
					</tr>
					<tr>
						<td>Heure de fin des cours de la matinée :</td><td><input type='text' name='heure_mat_fin' value='<?php echo $tableau['heure_mat_fin'] ?>' /></td>
					</tr>
					<tr>
						<td>Heure de début des cours de l'après midi :</td><td><input type='text' name='heure_aprem_deb' value='<?php echo $tableau['heure_aprem_deb'] ?>' /></td>
					</tr>
					<tr>
						<td>Heure de fin des cours de l'après midi :</td><td><input type='text' name='heure_aprem_fin' value='<?php echo $tableau['heure_aprem_fin'] ?>' /></td>
					</tr>
					<tr>
						<td rowspan='6'>Email pour demande de confirmation :<br/>(laissez vide si vous ne voulez recevoir aucun email)</td>
					</tr>
					<tr>
						<td><input type='text' name='email1' value='<?php echo $tableau['email1'] ?>' /></td>
					</tr>
					<tr>
						<td><input type='text' name='email2' value='<?php echo $tableau['email2'] ?>' /></td>
					</tr>
					<tr>
						<td><input type='text' name='email3' value='<?php echo $tableau['email3'] ?>' /></td>
					</tr>
					<tr>
						<td><input type='text' name='email4' value='<?php echo $tableau['email4'] ?>' /></td>
					</tr>
					<tr>
						<td><input type='text' name='email5' value='<?php echo $tableau['email5'] ?>' /></td>
					</tr>
					<tr>
						<td colspan='2' class='haut_menu'>
							<input type='button' value='Enregistrer' onclick="return configuration()" />
						</td>
					</tr>
			</table>
		</form>
	<?php } ?>