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
	<title>Enregistrement des dates d'une nouvelle année</title>
	<link href="../../styles/defaut.css" rel="stylesheet" title="style" type="text/css" />
</head>
<body>
<table class='voir_salle'>
	<form action="#" method="get">
		<tr>
			<td>Quel est le numero du jour et le mois du début de l'année scolaire <?php $annee = date('Y')+1; echo date('Y').'/'.$annee ?> ?</td>
			<td>
				<select name='jour_deb'>
				<?php
					for ($i=1;$i<=31;$i++)
						echo '<option value='.$i.'>'.$i.'</option>';
				?>
				</select>
			</td>
			<td>
				<select name='mois_deb'>
					<option value='January'>Janvier</option>
					<option value='February'>Fevrier</option>
					<option value='March'>Mars</option>
					<option value='April'>Avril</option>
					<option value='May'>Mai</option>
					<option value='June'>Juin</option>
					<option value='July'>Juillet</option>
					<option value='August'>août</option>
					<option value='September'>Septembre</option>
					<option value='October'>Octobre</option>
					<option value='November'>Novembre</option>
					<option value='December'>Decembre</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Quel est le numero du jour et le mois de fin l'année scolaire <?php $annee = date('Y')+1; echo date('Y').'/'.$annee ?> ?</td>
			<td>
				<select name='jour_fin'>
				<?php
					for ($i=1;$i<=31;$i++)
						echo '<option value='.$i.'>'.$i.'</option>';
				?>
				</select>
			</td>
			<td>
				<select name='mois_fin'>
					<option value='January'>Janvier</option>
					<option value='February'>Fevrier</option>
					<option value='March'>Mars</option>
					<option value='April'>Avril</option>
					<option value='May'>Mai</option>
					<option value='June'>Juin</option>
					<option value='July'>Juillet</option>
					<option value='August'>août</option>
					<option value='September'>Septembre</option>
					<option value='October'>Octobre</option>
					<option value='November'>Novembre</option>
					<option value='December'>Decembre</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='haut_menu' colspan='3'><input type='submit' value='Envoyer' onclick="return confirm('Êtes vous sûr de vouloir enregistrer les nouvelles semaines ?')"/></td>
		</tr>
	</form>
</table>
<br/>
<br/>
<?php
if (!empty($_GET['jour_deb']) && !empty($_GET['mois_deb']) && !empty($_GET['jour_fin']) && !empty($_GET['mois_fin']))
{
	// Positionne sur le lundi du début des cours
		$tsp_jour_deb_annee = strtotime($_GET['jour_deb'].' '.$_GET['mois_deb'].' '.date('Y'));
		$jour_deb_annee = date('w',$tsp_jour_deb_annee);
		if ($jour_deb_annee != 1)
		{
			switch ($jour_deb_annee)
			{
				case $jour_deb_annee == 0:
					$tsp_jour_deb_annee += 86400;
				break;
				case $jour_deb_annee == 2:
					$tsp_jour_deb_annee -= 86400;
				break;
				case $jour_deb_annee == 3:
					$tsp_jour_deb_annee -= 172800;
				break;
				case $jour_deb_annee == 4:
					$tsp_jour_deb_annee -= 259200;
				break;
				case $jour_deb_annee == 5:
					$tsp_jour_deb_annee -= 345600;
				break;
				case $jour_deb_annee == 6:
					$tsp_jour_deb_annee -= 432000;
				break;
			}
		}
	// Positionne sur le samedi de la fin des cours
		$annee = date('Y')+1;
		$tsp_jour_fin_annee = strtotime($_GET['jour_fin'].' '.$_GET['mois_fin'].' '.$annee);
		$jour_fin_annee = date('w',$tsp_jour_fin_annee);
		if ($jour_fin_annee  != 6)
		{
			switch ($jour_fin_annee)
			{
				case $jour_fin_annee == 0:
					$tsp_jour_fin_annee -= 86400;
				break;
				case $jour_fin_annee == 1:
					$tsp_jour_fin_annee += 432000;
				break;
				case $jour_fin_annee == 2:
					$tsp_jour_fin_annee += 345600;
				break;
				case $jour_fin_annee == 3:
					$tsp_jour_fin_annee += 259200;
				break;
				case $jour_fin_annee == 4:
					$tsp_jour_fin_annee += 172800;
				break;
				case $jour_fin_annee == 5:
					$tsp_jour_fin_annee += 86400;
				break;
			}
		}
	// heure été ou heure d'hiver pour le jour du début de l'année	
		$annee = date('Y',$tsp_jour_deb_annee);
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
		if ($tsp_jour_deb_annee >= $heure_ete && $tsp_jour_deb_annee < $heure_hiver)
			$tsp_jour_deb_annee += 3600;
	// heure été ou heure d'hiver pour le jour de fin de l'année	
		$annee = date('Y',$tsp_jour_fin_annee);
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
		if ($tsp_jour_fin_annee >= $heure_ete && $tsp_jour_fin_annee < $heure_hiver)
			$tsp_jour_fin_annee += 3600;
	$nb_semaine = 1;
	require('../../infos/connexion.php');
	mysql_query('DELETE FROM gp_cours');
    mysql_query('DELETE FROM gp_semaine');
	$timestamp_fin = '';
	while ($timestamp_fin != $tsp_jour_fin_annee)
	{
		$timestamp_fin = $tsp_jour_deb_annee + 432000;
		mysql_query("INSERT INTO gp_semaine values($nb_semaine, $tsp_jour_deb_annee, $timestamp_fin)");
		$tsp_jour_deb_annee = $timestamp_fin + 172800;
		$nb_semaine++;
	}
	?>
		<script type='text/javascript'>
			alert('Toutes les semaines de l\'année ont été correctement créées.');document.location.replace('../index.php');
		</script>
	<?php
}
?>
<div style='margin: auto; height: 15px'><a href='../index.php' title='administration'>Administration</a></div>
</body>
</html>