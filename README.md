# MVCR

Tout mon routage se fait dans le fichier 
- app/config/Config.php
Dans cette classe je définie une constante ROUTES qui est un tableau de couple:
  Le path (qui correspond à l'url)
  Le controller (qui correspond à la méthode qui doit être exécutée quand l'url correspond à la variable path.
  
  
- app/router/RouterV2.php
Le Router lui est chargé de comparé l'url actuelle avec la variable path.
(le plus important pour ce que tu cherche à faire est la ligne 31 à 38 du routeur,
c'est a dire le script qui permet d'envoyer des paramètres par l'url.
            - Si la variable path d'une route est égale à /cartes/{id:INT}/afficher et que l'url est égale à /cartes/15/afficher
            alors quand le routeur va appelé le controller, il lui fournira en paramètre $get['id'] = 15;
            
            /!\ Le plus dur est donc de parser les paths et de vérifié si l'url correspondantes est égale /!\
- app/service/urisolver/*
C'est le coeur du routeur c'est ce service qui prend en paramètre une url et une route et qui retourne true si l'url correspond à la route

-app/controller/CarteController.php ligne 103 --> récupèration d'un paramètre passer dans l'url. (la route correspondante se trouve à la ligne 53 de Config.php.            
