function verif_installation()
{
 if (document.forms['installation'].lienbdd.value == '')
 {
  alert("Vous n'avez pas saisie le lien vers votre base de donnée");
  return false;
 }
 if (document.forms['installation'].loginbdd.value == '')
 {
  alert("Vous n'avez pas saisie le login de connection à la base de donnée");
  return false;
 }
 if (document.forms['installation'].nombase.value == '')
 {
  alert("Vous n'avez pas saisie le nom de votre base de donnée");
  return false;
 }
 if (document.forms['installation'].passbdd.value == '')
 {
  alert("Vous n'avez pas saisie le pass de connexion à la base de donnée");
  return false;
 }
 return true;
}

function verif_connexion()
{
 if (document.forms['identification'].nom_util.value == '')
  {
   alert("Vous n'avez pas saisie votre nom d'utilisateur");
   return false;
  }
 if (document.forms['identification'].mdp.value == '')
  {
   alert("Vous n'avez pas saisie votre mot de passe");
   return false;
  }
  document.forms['identification'].submit();
}

function popup_centre(lien,largeur,hauteur)
{
 var millieu_h = (screen.height/2) - (hauteur/2);
 var millieu_l = (screen.width/2) - (largeur/2);
 var config = 'top='+millieu_h+', left='+millieu_l+', status=no, scrollbars=no, resizable=no, width='+largeur+', height='+hauteur;
 window.open(lien,'',config);
}

function verif_modif()
{
  if (document.forms['modif_salle'].auteur.value == '')
  {
   alert('Vous n\'avez pas saisie l\'auteur de la salle');
   return false;
  }
 if (document.forms['modif_salle'].nom_salle.value == '')
  {
   alert('Vous n\'avez pas saisie le nom de la salle');
   return false;
  }
  return true;
}

function verif_modif_cours()
{
  if (document.forms['modif_cours'].filiere.value == '')
  {
   alert('Vous n\'avez pas saisie la filière');
   return false;
  }
 if (document.forms['modif_cours'].type_tp.value == '')
  {
   alert('Vous n\'avez pas saisie le type de TP');
   return false;
  }
  if (Number(document.forms['modif_cours'].heure_deb.value) == Number(document.forms['modif_cours'].heure_fin.value))
  {
   alert('Le cours ne peut pas avoir une heure de début et une heure de fin similaire.');
   return false;
  }
  if (Number(document.forms['modif_cours'].heure_deb.value) > Number(document.forms['modif_cours'].heure_fin.value))
  {
   alert('Le cours ne peut pas avoir une heure de début supérieur à l\'heure de fin.');
   return false;
  }
  return true;
}

function verif_ajout_membre()
{
  if (document.forms['ajout_prof'].nom.value == '')
  {
   alert('Vous n\'avez pas saisie le nom du professeur');
   return false;
  }
  if (document.forms['ajout_prof'].prenom.value == '')
  {
   alert('Vous n\'avez pas saisie le prenom du professeur');
   return false;
  }
  if (document.forms['ajout_prof'].mdp1.value == '')
  {
   alert('Vous n\'avez pas saisie le premier mot de passe');
   return false;
  }
  if (document.forms['ajout_prof'].mdp2.value == '')
  {
   alert('Vous n\'avez pas saisie le second mot de passe');
   return false;
  }
  if (document.forms['ajout_prof'].mdp1.value != document.forms['ajout_prof'].mdp2.value)
  {
   alert('Les deux mot de passes ne sont pas identiques');
   return false;
  }
  if (document.forms['ajout_prof'].email.value == '')
  {
   alert('Vous n\'avez pas saisie votre email');
   return false;
  }
  return true;
}

function verif_modif_membre()
{
  if (document.forms['ajout_prof'].nom.value == '')
  {
   alert('Vous n\'avez pas saisie le nom du professeur');
   return false;
  }
  if (document.forms['ajout_prof'].prenom.value == '')
  {
   alert('Vous n\'avez pas saisie le prenom du professeur');
   return false;
  }
  if (document.forms['ajout_prof'].mdp1.value != document.forms['ajout_prof'].mdp2.value)
  {
	alert('Les deux mot de passes ne sont pas identiques');
	return false;
  }
  if (document.forms['ajout_prof'].email.value == '')
  {
   alert('Vous n\'avez pas saisie votre email');
   return false;
  }
  return true;
}

function configuration()
{
  if (document.config.nb_semaine.value == '')
  {
   alert('Vous n\'avez pas saisie le nombre de semaine à afficher au démarrage.');
   return false;
  }
  if (document.config.heure_mat_deb.value == '')
  {
   alert('Vous n\'avez pas saisie l\'heure de début des cours de la matinée.');
   return false;
  }
  if (document.config.heure_mat_fin.value == '')
  {
   alert('Vous n\'avez pas saisie l\'heure de fin des cours des la matinée.');
   return false;
  }
  if (document.config.heure_aprem_deb.value == '')
  {
   alert('Vous n\'avez pas saisie l\'heure de début des cours de l\'après midi.');
   return false;
  }
  if (document.config.heure_aprem_fin.value == '')
  {
   alert('Vous n\'avez pas saisie l\'heure de fin des cours de l\'après midi.');
   return false;
  }
  if (Number(document.config.heure_mat_deb.value) > Number(document.config.heure_mat_fin.value))
  {
   alert('L\'heure de début des cours de la matinée ne peut pas etre supérieur à l\'heure de fin.');
   return false;
  }
  if (Number(document.config.heure_aprem_deb.value) > Number(document.config.heure_aprem_fin.value))
  {
   alert('L\'heure de début des cours de l\'après midi ne peut pas etre supérieur à l\'heure de fin.');
   return false;
  }
  if (Number(document.config.heure_mat_fin.value) > 12)
  {
   alert('L\'heure de fin des cours de la matinée ne peut pas etre supérieur à 12.');
   return false;
  }
  if (Number(document.config.heure_mat_deb.value) == Number(document.config.heure_mat_fin.value))
  {
   alert('L\'heure de début des cours de la matinée ne peut pas etre égale à l\'heure de fin.');
   return false;
  }
  if (Number(document.config.heure_aprem_deb.value) == Number(document.config.heure_aprem_fin.value))
  {
   alert('L\'heure de début des cours de l\'après midi ne peut pas etre égale à l\'heure de fin.');
   return false;
  }
  if (Number(document.config.heure_mat_fin.value) > Number(document.config.heure_aprem_deb.value))
  {
   alert('L\'heure de fin des cours de la matinée ne peut pas être supérieur à l\'heure de début des cours de l\'après midi.');
   return false;
  }
  if (Number(document.config.heure_aprem_fin.value) > 24)
  {
   alert('L\'heure de fin des cours de l\'après midi ne peut pas etre supérieur à 24.');
   return false;
  }
  document.location.replace('index.php?menu_administration=configuration&nb_semaine='+document.config.nb_semaine.value+'&heure_mat_deb='+document.config.heure_mat_deb.value+'&heure_mat_fin='+document.config.heure_mat_fin.value+'&heure_aprem_deb='+document.config.heure_aprem_deb.value+'&heure_aprem_fin='+document.config.heure_aprem_fin.value+'&email1='+document.config.email1.value+'&email2='+document.config.email2.value+'&email3='+document.config.email3.value+'&email4='+document.config.email4.value+'&email5='+document.config.email5.value);
}

