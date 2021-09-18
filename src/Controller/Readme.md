# Réseau social eduactif 
* Réseau social permettant le traitement des dossiers des étudiants.
# Back-end : 
* PHP 
* SYMFONY 4 (API)
* MySQL
# Fonctionnalités
Les fonctionnalités sont développées par des api.
* Authentification des utilisateurs
  - Utilisation des JWT Tokens (Géneration d'un token qui est valide en 24h)
* Inscription des etudiants 
  - Par email (l'administrateur envoie un email à l'etudiant son code avec un lien afin de s'incrire)
  - Manuellement (l'administrateur lui-meme inscrit l'etudiant puis l'envoie ses informations et la possibilité 
                  de réinitialiser son mot de passe)
* Liste des admins par pays (les administrateurs sont listés en fonctions de leurs pays et avec leurs etudiants
                             inscrits)
# Base de données 
* Entités :
  - Etudiant : (id, code, nom, prenom, pays, ville, email, password, roles)
  - User : (id, nom, prenom, email, pays, password, role) : (Administrasteur et Super Administrateur)