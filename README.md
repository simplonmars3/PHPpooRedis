# Jeu d'échecs grandeur nature : exercices PHP poo et Redis
Exercices PHP POO avec binding vers Redis    
    
On construit un échiquier, sur lequel on pose plusieurs types de pièces. Chaque type de pièce a des propriétés propres.    
Chaque type de pièce est décliné en plusieurs variantes.    

On placera les pièces par leurs coordonnées lat/long, à la la manière d'un pokemon-Go-like    

# Dépendances
NOTE : on a ajouté le dépôt `dotdeb` pour profiter d'une version récente de Redis :
```
# On ajoute dans le fichier /etc/apt/sources.list.d/dotdeb.list                                                                                                                                                                                                                          
deb http://packages.dotdeb.org jessie all
deb-src http://packages.dotdeb.org jessie all    

# Puis on passe les commandes :                                                                                                                                                                                                                                                  
wget https://www.dotdeb.org/dotdeb.gpg
sudo apt-key add dotdeb.gpg    

# Puis on installe le serveur Redis à jour...    
sudo aptitude update
sudo aptitude install redis-server
```

Cet exemple s'appuie sur une Debian 8, on a installé les paquets suivants installés avec `apt` :
- `php5-cli`
- `redis-server`
- `libphp-predis`

# Exercices    

Vous pouvez vous inspirer librement de la doc en ligne et des différents tutos trouvables en lignes, par exemple :
- https://matt.sh/redis-geo
- http://www.infoworld.com/article/3128306/application-development/build-geospatial-apps-with-redis.html
- http://cristian.regolo.cc/2015/07/07/introducing-the-geo-api-in-redis.html
- http://objectrocket.com/blog/company/redis-geo-tutorial    


1. **Ajouter une propriété obligatoire aux objets Tower**    
   Les classes ont déjà des propriétés `tower_type` ou `id`. On doit leur ajouter un rayon de non-constructibilité :    
   ellez auront besoin de ce rayon autour d'elles sans autre construction, et une fois construites elles empêcheront la construction à l'intérieur de ce rayon.    
   Ce "rayon d'empêchement" peut être exprimé en kilomètres    

2. **Passer les coordonnées en type geo dans redis et coder les méthodes :**    
    - `ChessRedis::addObjectLocation(object, lat, long)`    
      La méthode qui ajoute dans Redis la position géo, en liant avec l'objet positionné    
      Elle peut prendre en arguments : l'objet (Tower par exemple), les positions géo lat / long    
      Elle doit renvoyer un booleen indiquant le succès de l'écriture dans Redis    
      
    - `ChessRedis::getObjectLocation(id)`    
      La méthode qui renvoie les coordonnées d'un objet, par son id    
      Elle peut prendre en arguments : l'id de l'objet dont on veut les coordonnées    
      Elle doit renvoyer un array(long=>xxxx,lat=>yyyy)
      
    - `ChessRedis::getObjectsAroundLocation(lat, long, radius)`     
      La méthode qui renvoie la liste des objets présents autour d'une position, dans un rayon donné    
      Elle peut prendre en arguments : la position long/lat , le rayon voulu autour de cette position    
      Elle doit renvoyer un array d'objets divers (fireTower, sniperTower, ...) qui sont positionnés dans le cercle calculé    
      
    - `ChessRedis::locationIsConstructable(lat, long, radius)`    
      La méthode qui donne l'état de constructibilité pour une nouvelle construction ; une première version simple est facile à faire.    
      Elle peut prendre en arguments : la position souhaitée pour la nouvelle construction lat/lon , le rayon correspondant à la propriété ajoutée à l'exercice 1 de la Tower à construire, par exemple    
      Elle doit au minimum vérifier si aucune construction ne se trouve dans le cercle calculé    
      Elle peut éventuellement vérifier si la position ne viole pas les règles de rayon de non-constructibilité des autres constructions qui seraient en dehors du propre rayon de la Tower à construire    
      Elle doit renvoyer un booléen true/false en fonction du résultat du calcul    
    
3. **Modifier les méthodes pour adapter aux nouvelles coordonnées**    
    - `*Tower::addTower(lat,lon)`    
      On ajoute maintenant une tour en donnant une position lat / lon    
      Les FireTower ont un rayon de 2km, les sniperTower de 1km
    
4. **Récupérer tous ces points et les afficher sur une carto au choix**    
    - Ajouter une méthode `ChessRedis::getAllElements()` qui renvoie une liste de (par exemple)    
      *array(
        type=>'SniperTower',
        'id'=>'iddelobjet',
        lat=>45.22,
        long=>4.56)*, 
        ...    
    avec autant d'arrays que d'éléments présents    
    
    
