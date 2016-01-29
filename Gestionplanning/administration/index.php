<?php session_start() ?>
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
	<title>Administration</title>
	<link href="../styles/defaut.css" rel="stylesheet" title="style" type="text/css" />
	<script type="text/javascript" src="../javascript/defaut.js"></script>
</head>
<body>
	<table style='width: 100%; text-align: center'>
		<tr>
			<td>
				<form action="#" method="get">
					<select name='menu_administration'>
						<option value='ajouter_membre'>Ajouter un membre</option>
						<option value='gerer_membre'>Gerer les membres</option>
						<option value='ajouter_salle'>Ajouter une salle</option>
						<option value='gerer_salle'>Gerer les salles</option>
						<option value='configuration'>Configuration</option>
					</select>
					<input type='submit' value='valider' />
				</form>
			<br/>
			<br/>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				if (!empty($_GET['menu_administration']))
				{
					switch($_GET['menu_administration'])
					{
						case $_GET['menu_administration'] == 'ajouter_membre':
							include('ajouter_membre.php');
						break;
						case $_GET['menu_administration'] == 'gerer_membre':
							include('gerer_membre.php');
						break;
						case $_GET['menu_administration'] == 'ajouter_salle':
							include('ajouter_salle.php');
						break;
						case $_GET['menu_administration'] == 'gerer_salle':
							include('gerer_salle.php');
						break;
						case $_GET['menu_administration'] == 'configuration':
							include('configuration.php');
						break;
					}
				}
				?>
			</td>
		</tr>
	</table>
	<br/>
	<div style='margin: auto; height: 15px'><a href='../index.php' title='Retour'>Retour au planning</a></div>
	<br/>
	<div style='margin: auto; height: 15px; width: 160px'><a href='super_administration/index.php' title='Super Administration'>Super Administration</a></div>
</body>
</html>