<?php
	$ip = explode('.',$ip);
	$ip = sha1($ip_m1[0].'13'.$ip_m1[1].'12'.$ip_m1[2].'11'.$ip_m1[3]);
	session_name($ip);
	session_start();
	$depart = microtime(true);
	if (!file_exists('infos/connexion.php'))
	{
		$_SESSION=array();
		session_destroy();
		header('Location: installation/index.php');
		die();
	}
?>
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
	<title>Gestion du planing</title>
	<?php 
	if (ereg("MSIE", $_SERVER["HTTP_USER_AGENT"])) 
		echo '<link href="styles/defaut_ie.css" rel="stylesheet" title="style" type="text/css" />';
	else
		echo '<link href="styles/defaut.css" rel="stylesheet" title="style" type="text/css" />';
	?>
	<script type="text/javascript" src="javascript/defaut.js"></script>
</head>
<body>
	<table style='width: 100%; text-align: center'>
		<tr>
			<td>
				<form action="#" method="get">
					<select name='id_salle'>
						<?php 
						require('infos/connexion.php');
						$donnees = mysql_query('SELECT id_salle,nom_salle FROM gp_salle ORDER BY id_salle');
						while ($tableau = mysql_fetch_array($donnees))
						{
							if ($_GET['id_salle'] == $tableau['id_salle'])
								echo '<option class=\'en_cours\' value=\''.$tableau['id_salle'].'\' selected=\'selected\'>'.$tableau['nom_salle'].'</option>';
							else
								echo '<option value=\''.$tableau['id_salle'].'\'>'.$tableau['nom_salle'].'</option>';
						}
						?>
					</select>
					<input type='submit' value='Visualiser les cours' />
				</form>
			<br/><br/>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				if (!empty($_GET['id_salle']))
					include('_salle.php');
				?>
			</td>
		</tr>
		<tr>
			<td>
				<br/><br/>
				<div style='margin: auto; height: 15px'><a href='administration/' title='administration'>Administration</a></div>
				<br/>
				<?php 
					$fin = microtime(true);
					$delai = round($fin-$depart, 4);
					if (ereg("MSIE", $_SERVER["HTTP_USER_AGENT"]))
						echo '<div style=\'margin: auto; height: 30px; width: 260px\'>Temps execution : <strong>'.$delai.'</strong> sec <a href=\'http://www.mozilla-europe.org/fr/\' title=\'Obtenir firefox\'>Affichage optimisé pour firefox</a></div>';
					else
						echo '<div style=\'margin: auto; height: 15px; width: 500px\'>Temps execution : <strong>'.$delai.'</strong> sec  -  <a style=\'display: inline;\' href=\'http://www.mozilla-europe.org/fr/\' title=\'Obtenir firefox\'>Affichage optimisé pour firefox</a></div>';
				?>
			</td>
		</tr>
	</table>
</body>
</html>