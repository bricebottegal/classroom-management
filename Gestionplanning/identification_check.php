<?php
	$ip = explode('.',$ip);
	$ip = sha1($ip_m1[0].'13'.$ip_m1[1].'12'.$ip_m1[2].'11'.$ip_m1[3]);
	session_name($ip);
	session_start();
	if (!empty($_GET['deconnexion']))
	{
		if ($_GET['deconnexion'] == 1)
		{
			$_SESSION=array();
			session_destroy();
			header('Location: identification.php');
			die();
		}
	}
	
    if (empty($_SESSION['HTTP_USER_AGENT']) or empty($_SESSION['derniere_acces']) or empty($_SESSION['ip']) or empty($_SESSION['nom_professeur']) or empty($_SESSION['id_prof']) or empty($_SESSION['prenom']) or empty($_SESSION['email']) or empty($_SESSION['statut']))
    {
		$_SESSION=array();
		session_destroy();
		header('Location: identification.php');
		die();
    }
	
	if (!file_exists('infos/connexion.php'))
	{
		$_SESSION=array();
		session_destroy();
		header('Location: installation/index.php');
		die();
	}
	
    $sessions_expiration = 3600;
    if (time()-$_SESSION['derniere_acces']>$sessions_expiration)
    {
		$_SESSION=array();
		session_destroy();
		echo 'Temps d\'innactivité trop élevé, veuillez vous ré-identifier';
		die();
    }
	
    if ($_SESSION['HTTP_USER_AGENT'] != sha1($_SERVER['HTTP_USER_AGENT']))
    {
		$_SESSION=array();
		session_destroy();
		header('Location: identification.php');
		die();
    }
	
    if ($_SESSION['ip'] != sha1($_SERVER['REMOTE_ADDR']))
    {
		$_SESSION=array();
		session_destroy();
		header('Location: identification.php');
		die();
    }
    $_SESSION['derniere_acces'] = time();
?> 