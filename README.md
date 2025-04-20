# Mediatekformation
## Présentation
Ce site, développé avec Symfony 6.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.
La présentation de l'application d'origine se situe dans le readme du dépôt d'origine qui se trouve à l'adresse suivante : 
https://github.com/CNED-SLAM/mediatekformation
J'ai développée la partie back-office, elle contient les nouvelles fonctionnalités globales suivantes :
## Front-office

### L'accueil
Cette page présente le fonctionnement du site et les 2 dernières vidéos mises en ligne.<br>
La partie du haut contient une bannière (logo, nom et phrase présentant le but du site) et le menu permettant d'accéder aux 3 pages principales (Accueil, Formations, Playlists,Catégories).<br>
Le centre contient un texte de présentation avec, entre autres, les liens pour accéder aux 2 autres pages principales.<br>
La partie basse contient les 2 dernières formations mises en ligne. Cliquer sur une image permet d'accéder à la page de présentation de la formation.<br>
Le bas de page contient un lien pour accéder à la page des CGU : ce lien est présent en bas de chaque page excepté la page des CGU.<br>
![image](![image](https://github.com/user-attachments/assets/1e24f6c2-2b2a-467c-8cb8-fef2bc25bc26))



### Les playlists
![image](![image](https://github.com/user-attachments/assets/a07802dd-4b79-4c00-83df-38aeb238e4d3)
)


### Détail d'une playlist
![image](![image](https://github.com/user-attachments/assets/741bedae-ba03-4a64-bde6-f462388e348d)
)

## Back office
### Login

![image](![image](https://github.com/user-attachments/assets/2d2e1b75-d39d-4b41-90e5-189b7f5d77bb)
6)
### Les formations

![image](![image](https://github.com/user-attachments/assets/6a73391b-98b3-4a52-b321-7831e0b68252)
)


### Modifier une formation

![image](![image](https://github.com/user-attachments/assets/6fd3db68-7cb4-44d2-b36d-7db88a1f75c3)
)

### Playlist
![image](![image](https://github.com/user-attachments/assets/24137ffb-55d0-4a37-85d2-16edb8ea555c)
)
### Ajouter une playlist
![image](![image](https://github.com/user-attachments/assets/583895c9-e60a-4c6b-aec2-8ca283cbb4cd)
)

### Catégories 
![image](![image](https://github.com/user-attachments/assets/04ab7c54-fb59-453e-bb91-a0f036590bbe)
)


## Test de l'application en local
http://mediatekformationas.atwebpages.com
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
