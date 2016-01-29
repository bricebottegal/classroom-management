<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<title>Installation</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<link href='../styles/defaut.css' rel='stylesheet' title='style' type='text/css' />
<script type="text/javascript" src="../javascript/defaut.js"></script>
<style>
a
{
 background-color: #FFFFCC;
}
</style>
</head>
<body bgcolor='#FFCC00'>
<form id='installation' action='#' method='post' enctype='multipart/form-data'>
  <fieldset class='installation'>
		<legend>Informations relatives à votre base de donnée :</legend>
		<label>Indiquez le lien de votre base de donnée<br/><input type='text' name='lienbdd' size='50'/><br/></label>
		<label>Indiquez le login de connexion à la base de donnée (<a class='effet2' href="javascript:popup_centre('aide_inscription_bdd.htm',400,60)" title='aide'>aide</a>)<br/><input type='text' name='loginbdd' size='50'/><br/></label>
		<label>Indiquez le mot de passe pour accéder à cette base de donnée<br/><input type='password' name='passbdd' size='50'/><br/></label>
		<label>Indiquez le nom de la base de donnée<br/><input type='text' name='nombase' size='50'/><br/><br/></label>
  </fieldset>
  <p><br/><br/></p>
  <fieldset class='installation'>
		<legend>Informations relatives à la partie historique des connexions et administration (<a class='effet2' href="javascript:popup_centre('aide_inscription_partisecur.htm',400,140)" title='aide'>aide</a>) :</legend>
		<label>Saisir le nom d'utilisateur <br/><input type='text' name='nomhtaccess' size='50'/><br/></label>
		<label>Saisir le mot de passe de la partie historique (8 caractères minimum)<br/><input type='password' name='passhtaccess' size='50'/><br/></label>
       <label>Ressaisir le mot de passe<br/><input type='password' name='passhtaccess2' size='50'/><br/><br/></label>
   </fieldset>
  <p><br/><br/></p>
  <fieldset class='installation'>
		<legend>Informations relatives à la partie super administration (<a class='effet2' href="javascript:popup_centre('aide_inscription_partieadmin.htm',400,120)" title='aide'>aide</a>) :</legend>
		<label>Saisir votre identifiant<br/><input type='text' name='login' size='50'/><br/></label>
		<label>Saisir le mot de passe de la partie sécurisée (8 caractères minimum)<br/><input type='password' name='mdp' size='50'/><br/></label>
		<label>Ressaisir le mot de passe<br/><input type='password' name='mdp2' size='50'/><br/><br/></label>
  </fieldset>
  <p style='text-align: center;'><input type='submit' value='Installer' onclick='return verif_installation()' /><input type='reset' value='Effacer' /></p>
</form>
 <?php
 if(!empty($_POST['lienbdd']) && !empty($_POST['loginbdd']) && !empty($_POST['passbdd']) && !empty($_POST['nombase']) && !empty($_POST['nomhtaccess']) && !empty($_POST['passhtaccess']) && !empty($_POST['passhtaccess2']) && !empty($_POST['login']) && !empty($_POST['mdp']) && !empty($_POST['mdp2']))
 {
  if (mysql_connect($_POST['lienbdd'],$_POST['loginbdd'],$_POST['passbdd']))
  {
    if (mysql_select_db($_POST['nombase']))
	{
	  if ($fichier = fopen('../infos/connexion.php','w+'))
	  {
	    // Creation du fichier de connection à la bdd
        $texte = '<?php mysql_connect(\''.$_POST['lienbdd'].'\',\''.$_POST['loginbdd'].'\', \''.$_POST['passbdd'].'\');mysql_select_db(\''.$_POST['nombase'].'\'); ?>';
	    fputs($fichier,$texte);
	    fclose($fichier);
	  
	    // Pour avoir le chemin d'acces à la page
	    $chemin1 = realpath('../infos/cookies/');
	    $chemin2 = realpath('../infos/sessions/');
		
	    // On creer le fichier .htpasswd pour la partie administration et log des connections
	    $fichier = fopen('../infos/cookies/.htpasswd','w+');
		$texte = $_POST['nomhtaccess'].':'.crypt($_POST['passhtaccess']);
	    fputs($fichier,$texte);
	    fclose($fichier);
		
		// On creer le fichier .htpasswd pour la partie super administration
	    $fichier = fopen('../infos/sessions/.htpasswd','w+');
		$texte = $_POST['login'].':'.crypt($_POST['mdp']);
	    fputs($fichier,$texte);
	    fclose($fichier);
		
		// On creer le fichier .htaccess pour les deux dossiers de passwords
	    $fichier = fopen('../infos/sessions/.htaccess','w+');
        $texte = "<Limit GET POST>
        deny from all
        </Limit>";
	    fputs($fichier,$texte);
	    fclose($fichier);

	  	$fichier = fopen('../infos/cookies/.htaccess','w+');
        $texte = "<Limit GET POST>
        deny from all
        </Limit>";
	    fputs($fichier,$texte);
	    fclose($fichier);
		
	    // On creer le fichier .htaccess pour la partie infos
	    $fichier = fopen('../infos/.htaccess','w+');
        $texte = "AuthUserFile $chemin1/.htpasswd
        AuthGroupFile /dev/null
        AuthName 'Logs des connections'
        AuthType Basic
        <Limit GET POST>
        require valid-user
        </Limit> ";
	    fputs($fichier,$texte);
	    fclose($fichier);
	    
		// On creer le fichier .htaccess pour la partie administration	  
	    $fichier = fopen('../administration/.htaccess','w+');
        $texte = "AuthUserFile $chemin1/.htpasswd
        AuthGroupFile /dev/null
        AuthName 'Back0ffice'
        AuthType Basic
        <Limit GET POST>
        require valid-user
        </Limit> ";
	    fputs($fichier,$texte);
	    fclose($fichier);
	  
		// On creer le fichier .htaccess pour la partie super administration   
	    $fichier = fopen('../administration/super_administration/.htaccess','w+');
        $texte = "AuthUserFile $chemin2/.htpasswd
        AuthGroupFile /dev/null
        AuthName 'Super Back0ffice'
        AuthType Basic
        <Limit GET POST>
        require valid-user
        </Limit> ";
	    fputs($fichier,$texte);
	    fclose($fichier);
	  
	    // On creer le fichier logs
        $fichier = fopen('../infos/logs.txt','w');
	    $texte = 'Installation du Script Gestion-Planning v1.0 effectué le '.date('d/m/y')."\n\n";
	    fputs($fichier,$texte);
	    fclose($fichier);
	  
	    // Creation de la table CONFIGURATION
	    $requete = "CREATE TABLE `gp_configuration` (
		  `nb_semaine` bigint(20) NOT NULL default '0',
		  `heure_mat_deb` int(2) NOT NULL default '0',
		  `heure_mat_fin` int(2) NOT NULL default '0',
		  `heure_aprem_deb` int(2) NOT NULL default '0',
		  `heure_aprem_fin` int(2) NOT NULL default '0',
		  `email1` varchar(50) NOT NULL default '',
		  `email2` varchar(50) NOT NULL default '',
		  `email3` varchar(50) NOT NULL default '',
		  `email4` varchar(50) NOT NULL default '',
		  `email5` varchar(50) NOT NULL default '',
		  PRIMARY KEY  (`nb_semaine`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	    // Creation de la table PROFESSEUR
	    $requete1 = "CREATE TABLE `gp_professeur` 
		(
		`id_professeur` bigint(20) NOT NULL default '0',
		`nom_professeur` varchar(25) default NULL,
		`mdp` varchar(20) NOT NULL default '',
		`prenom` varchar(25) default NULL,
		`email` varchar(50) NOT NULL default '',
		`date_inscription` bigint(20) NOT NULL default '0',
		`statut` varchar(20) NOT NULL default '',
		 PRIMARY KEY  (`id_professeur`),
		 KEY `nom_professeur` (`nom_professeur`),
		 KEY `prenom` (`prenom`),
		 KEY `email` (`email`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		
		// Creation de la table SALLE
		$requete2 = "CREATE TABLE `gp_salle` 
		(
		`id_salle` bigint(20) NOT NULL default '0',
		`nom_salle` varchar(255) NOT NULL default '',
		`date_creation` bigint(20) NOT NULL default '0',
		`createur` varchar(25) NOT NULL default '',
		  PRIMARY KEY  (`id_salle`),
		  KEY `nom_salle` (`nom_salle`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";

		// Creation de la table SEMAINE
		$requete3 = "CREATE TABLE `gp_semaine` 
		(
		`id_semaine` bigint(20) NOT NULL default '0',
		`timestamp_deb` bigint(20) NOT NULL default '0',
		`timestamp_fin` bigint(20) NOT NULL default '0',
		  PRIMARY KEY  (`id_semaine`),
		  KEY `timestamp_deb` (`timestamp_deb`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";
			
		// Creation de la table COURS
		$requete4 = "CREATE TABLE `gp_cours` 
		(
		  `id_cours` bigint(20) NOT NULL default '0',
		  `id_salle` bigint(20) default '0',
		  `nom_professeur` varchar(25) default NULL,
		  `prenom` varchar(25) default NULL,
		  `id_semaine` bigint(20) NOT NULL default '0',
		  `id_jour` bigint(20) NOT NULL default '0',
		  `heure_deb` int(11) NOT NULL default '0',
		  `heure_fin` int(11) NOT NULL default '0',
		  `matiere` varchar(100) default NULL,
		  `type_tp` varchar(50) default NULL,
		  `statut` varchar(10) NOT NULL default 'vide',
		  PRIMARY KEY  (`id_cours`),
		  KEY `id_salle` (`id_salle`),
		  KEY `id_semaine` (`id_semaine`),
		  KEY `nom_professeur` (`nom_professeur`),
		  KEY `prenom` (`prenom`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		
		$requete5 = "ALTER TABLE `gp_cours`
		  ADD CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `gp_salle` (`id_salle`),
		  ADD CONSTRAINT `cours_ibfk_2` FOREIGN KEY (`nom_professeur`) REFERENCES `gp_professeur` (`nom_professeur`),
		  ADD CONSTRAINT `cours_ibfk_3` FOREIGN KEY (`prenom`) REFERENCES `gp_professeur` (`prenom`),
		  ADD CONSTRAINT `cours_ibfk_4` FOREIGN KEY (`id_semaine`) REFERENCES `gp_semaine` (`id_semaine`)";
		
		$requete6 = "INSERT INTO `gp_configuration` ( `nb_semaine` , `heure_mat_deb` , `heure_mat_fin` , `heure_aprem_deb` , `heure_aprem_fin`) VALUES ('6', '8', '12', '13', '20')";
	  
	  if (file_exists('../infos/connexion.php') AND file_exists('../infos/.htaccess') AND file_exists('../administration/.htaccess') AND file_exists('../administration/super_administration/.htaccess') AND file_exists('../infos/sessions/.htpasswd') AND file_exists('../infos/cookies/.htpasswd') AND file_exists('../infos/logs.txt'))
	  {
	   if (mysql_query($requete) && mysql_query($requete6) && mysql_query($requete3) && mysql_query($requete1) && mysql_query($requete2) && mysql_query($requete4) && mysql_query($requete5))
	   {
		  ?>
		  <script type='text/javascript'>
			alert('Installation Reussi.');
			alert('ATTENTION ! il faut maintenant supprimer le dossier nommé \'installation\'.');
			document.location.replace('../index.php');
		  </script>
		  <?php
		}
	    else
		{
		  ?>
		  <script type='text/javascript'>alert('Problème lors de l\'installation.');</script>
		  <?php
		}
	  }
	 }
	 else
	 // Impossible de CHMODER le répertoire
	 {
	   ?>
	   <script type='text/javascript'>alert('Safe mode détecté, Il faut que vous mettiez le répertoire infos en CHMOD 777.');</script>
	   <?php
	 }
	}
	else
	{
	  ?>
	  <script type='text/javascript'>alert('Le nom de la base n\'est pas correct.');</script>
	  <?php
	}
  }
  else
  {
    ?>
	<script type='text/javascript'>alert('Impossible de se connecter à la base de donnée.');</script>
	<?php
  }
 }
 ?>
 
 
