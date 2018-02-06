|----------|
|README.txt|
|----------|

Ce fichier README.txt :

 I) liste les informations liées aux machines / adresses IP utilisées pour la
   version du projet déjà déployée dans le LaboVision.

 II) explique comment déployer le projet.

 
Remarque : la documentation utilisateur pour le site "Monitoring d'activité" se trouve
dans le répertoire "User-Doc".
 
|-----------------------------------|
|I) Machines / adresses IP utilisées|
|-----------------------------------|

Concernant les VM :

 -	Pour ce projet, 2 VM ont été mises à disposition :

	Caractéristiques :	Ubuntu 16.04, 10GB d'espace disque, 2048 Mo de RAM
	machine 1 : 192.168.199.1 - nom de la machine : m1-morpou17-1
	machine 2 : 192.168.199.2 - nom de la machine : m1-morpou17-2
	POUR LES 2 MACHINES, root : morpou

	Remarque : Seule la machine 1 a été utilisée.

	
Concernant le site "Monitoring d'activité" :
	
 -	Site hébergé sur la machine n°1.
 -	Site accessible à l'adresse 192.168.199.1/monitoring_activite/

Concernant la BDD (base de données) :
	
 -	BDD hébergée sur la machine n°1.
 -  Utilisateur créé spécifiquement pour la BDD du projet :
    nom d'utilisateur : user_ma
    mot de passe : morpou

 
Concernant les programmes Qt :

 -	Il y a 4 programmes Qt (codes sources dans le répertoire "Programmes-Qt") :
 
		- BruitLabo : détecte les anomalies sonores et envoie les données via
		  WebSocket (port 4420).
		- EnregTV : détecte une activité de visionnage TV et envoie les données via
		  WebSocket (port 4430).
		- DetectZone : détecte les déplacements dans le LaboVision et envoie les
		  données via WebSocket (port 4440).
		- WSLitener : reçoie ces données WebSocket et les communiquent aux fichiers
		  PHP du site pour l'insertion en BDD.

 -	Pour "BruitLabo", le PC présent derrière le LaboVision a été utilisé.
	Un microphone doit être branché à ce PC (déjà le cas). Le programme à lancer
	se trouve sur le bureau du PC répertoire "BruitLabo".
	L'adresse IP de ce PC est : 192.168.197.52
	
 -	"EnregTV", "DetectZone" et "WSLitener" sont déployés sur la machine n°1 dans le répertoire :
	"/root/ProgMonitoringActivite/". Un crontab exécute le script "run.sh" toutes les minutes.
	Ce script (présent dans le même répertoire ci-dessus) démarre les programmes s'ils ne sont
	pas déjà démarrés.
 
 -	Pour "EnregTV" et "DetectZone", la caméra utilisée est celle au-dessus de l'écran placé
	directement à gauche en rentrant dans la pièce. Accès à son flux vidéo :
	http://192.168.197.21:80/mjpg/video.mjpg
	
	
REMARQUE : Dans le dossier de rendu, le répertoire "Production" contient :
    1)   un dossier "ProgMonitoringActivite" qui contient :
        -   les programmes déployés sur la VM n°1 Linux
        -   un script "run.sh" qui lance ces programmes (s'ils ne sont pas déjà lancés) avec
            les bons paramètres. Le programme "WSListener" prend pour paramètres les adresses IP
            des 3 autres programmes de détection (voir la ligne en commentaire dans ce script).
    2)  un dossier "BruitLabo" du programme pour détecter les bruits du LAboVision.
        Ce programme a été déployé sur le PC présent derrière le LaboVision (PC windows).

	
|-------------------------|
|II) Déployement du projet|
|-------------------------|

 1)	Créer une BDD MySQL avec comme nom de BDD : monitoring_activite.
 
 2)	Une fois connectée à la BDD, créer l'ensemble des tables grâce au fichier
	"BDD/monitoring_activite_bdd_init.sql" présent dans ce dossier de rendu.

 4)	Copier-coller le répertoire "monitoring_activite" présent dans ce dossier de rendu
	sur votre serveur web.
	
 5)	Modifier le fichier "monitoring_activite/utils/bdd_connection.php" pour modifier les
	constantes de connexion afin que le site accède à la BDD créée à l'étape n°1.
	
 6)	OPTIONNEL : connecté à la BDD, importer le fichier "BDD/INSERT-example.sql"
	présent dans ce dossier de rendu afin d'avoir des données à tester (penser à mettre
	ces données à la date du jour pour voir les informations s'afficher sur la page
	d'accueil du monitoring).
	
 7) Si nécessaire, penser à modifier :
	
		- l'adresse de la caméra dans les fichiers "zonetracker.cpp" (programme DetectZone)
		  et "tvtracker.cpp" (programme EnregTV) dans la méthode tracking().
		  
		- l'adresse IP fournie à l'initialisation du membre "m_network" dans le constructeur
		  du fichier "listeconfanomaliesonore.cpp" (programme BruitLabo). L'adresse IP doit
		  être celle de la machine où ce trouve le site déployé à l'étape n°4.
		  
		- l'adresse IP fournie à l'initialisation du membre "m_network" dans le constructeur
		  du fichier "detect.cpp" (programme WSLitener). L'adresse IP doit être celle de la
		  machine où ce trouve le site déployé à l'étape n°4.
		  
 8)	Compiler les 4 programmes sous Qt et les placer sur les machines désirées.
 
 8-bis) Si vous ne souhaitez pas passer par l'étape de compilation (parce qu'aucune modification
    doit être apportée), relire la remarque de la fin de la partie I) de ce fichier README.txt
    pour savoir où récupérer les programmes déjà déployés en production.
    
    ATTENTION : si vous déployez les programmes sur des machines différentes que celle de la verison
    actuellement en production, pensez à modifier le fichier "run.sh" pour le lancement du programme
    "WSListener" (ces adresses correspondent aux machines hébèrgeant les programmes. Adresses utilisées
    pour les communications WebSockets).
