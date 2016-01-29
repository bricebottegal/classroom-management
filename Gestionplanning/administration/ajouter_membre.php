<form id='ajout_prof' method='post' enctype='multipart/form-data'>
	<table class='voir_salle'>
		<tr>
			<td>Nom :</td><td><input name='nom' type='text' /></td>
		</tr>
		<tr>
			<td>Prenom :</td><td><input name='prenom' type='text' /></td>
		</tr>
		<tr>
			<td>Mot de passe :</td><td><input name='mdp1' type='password' /></td>
		</tr>
		<tr>
			<td>Entrez à nouveau le mot de passe :</td><td><input name='mdp2' type='password' /></td>
		</tr>
		<tr>
			<td>Email :</td><td><input name='email' type='text' /></td>
		</tr>
		<tr>
			<td>Statut :</td>
			<td>
				<select name='statut'>
					<option value='professeur'>Professeur</option>
					<option value='admin'>Administrateur</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan='2' class='haut_menu'>
				<input type='submit' value='Enregistrer' onclick='return verif_ajout_membre()' />
			</td>
		</tr>
	</table>
</form>
<?php
if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2']) && !empty($_POST['email']))
{
	if (preg_match("!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$!", $_POST['email']))
	{
		if ($_POST['mdp1'] == $_POST['mdp2'])
		{
			require('../infos/connexion.php');
			if (!mysql_num_rows(mysql_query('SELECT nom_professeur FROM gp_professeur WHERE nom_professeur = \''.$_POST['nom'].'\'')))
			{
				$tableau = mysql_fetch_assoc(mysql_query('SELECT id_professeur FROM gp_professeur ORDER BY id_professeur DESC LIMIT 1'));
				$id_professeur = $tableau['id_professeur']+1;
				if (mysql_query('INSERT INTO gp_professeur values('.$id_professeur.', \''.$_POST['nom'].'\', \''.$_POST['mdp1'].'\', \''.$_POST['prenom'].'\', \''.$_POST['email'].'\', '.time().', \''.$_POST['statut'].'\')'))
				{
					?>
					<script type="text/javascript">
						alert('<?php echo $_POST['nom'].' '.$_POST['prenom'] ?>'+' a bien été enregistré sous le numero '+'<?php echo $id_professeur ?>');
						document.location.replace('index.php');
					</script>
					<?php
				}
				else
				{
					?>
					<script type="text/javascript">
						alert('Erreur lors de l\'enregistrement du professeur.');
					</script>
					<?php
				}
			}
			else
			{
				?>
				<script type="text/javascript">
					alert('Ce nom de professeur est déjà utilisé, veuillez en choisir un autre');
				</script>
				<?php
			}
		}
		else
			echo 'Les deux mot de passes sont différents';
	}
	else
	{
		?>
		<script type="text/javascript">
			alert('L\'adresse email est invalide.');
		</script>
		<?php
	}
}
?>