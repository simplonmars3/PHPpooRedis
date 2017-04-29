# Jeu d'échecs grandeur nature : exercices PHP poo et Redis
Exercices PHP POO avec binding vers Redis    

On construit un échiquier, sur lequel on pose plusieurs types de pièces. Chaque type de pièce a des propriétés propres.    
Chaque type de pièce est décliné en plusieurs variantes.    

On placera les pièces par leurs coordonnées lat/long, à la la manière d'un pokemon-Go-like    

# Dépendances
> NOTE : tout ce qui est décrit ci-dessous a lieu **dans votre VM**, sauf avis contraire    

## Serveur Redis

> NOTE : on a ajouté le dépôt `dotdeb` pour profiter d'une version récente de Redis :    

```bash
# On ajoute dans le fichier /etc/apt/sources.list.d/dotdeb.list    

deb http://packages.dotdeb.org jessie all
deb-src http://packages.dotdeb.org jessie all    

```
```bash
# Puis on passe ces commandes pour ajouter la clé du dépôt au trousseau de apt :    
wget https://www.dotdeb.org/dotdeb.gpg
sudo apt-key add dotdeb.gpg    

# Puis on installe le serveur Redis à jour...    
sudo aptitude update
sudo aptitude install redis-server php5-cli
```

Cet exemple s'appuie sur une Debian 8, on vient juste d'installer les paquets suivants avec `apt` :
- `php5-cli`
- `redis-server`

## Librairie PHP -> Redis
> NOTE : On installe la lirairie `predis` via `composer`, pour avoir les features nécessaires aux exercices.    
Ce `composer` va se servir des fichiers `composer.json` et `composer.lock` ajoutés au commit 68013b6b3bab1b66c9402d2a8ebb6564b5efe7cb    

> Pour en savoir plus sur `composer` il suffit de lire [la documentation sur le site officiel](https://getcomposer.org/doc/00-intro.md)    

Pour faire court, on va :
1.  Désinstaller le paquet libphp-predis préalablement installé avec apt
    ```bash
    # Désinstaller le paquet libphp-predis préalablement installé avec apt :
    # il n'est pas à jour et il manque les features geo de redis dans la lib php
    sudo aptitude remove libphp-predis
    ```

1.  Installer localement composer
    ```bash
    # Se placer dans le répertoire du projet php pour installer localement composer
    # et ainsi monter l'ensemble des dépendances en une seule commande, une seule fois
    cd /home/simplon/PHPpooRedis    # Adaptez à votre configuration

    # On installe composer dans notre projet, conformément au mode d'emploi détaillé
    # ici https://getcomposer.org/download/
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"

    # Composer est maintenant installé dans notre dossier, on peut
    # gérer les paquets et dépendances PHP localement
    ```

1.  On monte les dépendances
    ```bash
    # On monte les dépendances avec l'outil composer.phar qu'on vient d'installer localement
    # Les dépendances sont spécifiées dans le fichier composer.json notamment , qui a été ajouté
    # au dépôt.
    php composer.phar install

    # Après ça, un dossier vendor/ devrait avoir été ajouté avec à sa racine
    # un fichier autoload.php
    ```

1.  On doit dire à git d'ignorer ces fichiers, **sur la machine locale** ; en effet c'est la copie locale qui est versionnée par git.    
    ```bash
    # On se place dans le dossier du projet PHP
    cd /home/ben/PHPpooRedis   # Adaptez selon votre config  
    
    # On doit dire à git d'ignorer ce dossier, ainsi que le
    # fichier `composer.phar` qu'on vient d'installer
    touch .gitignore
    echo "vendor/" >> .gitignore
    echo "composer.phar" >> .gitignore
    echo ".gitignore" >> .gitignore
    ```

## PHP en mode interactif
On utilise PHP dans son mode interactif pour manipuler nos objets directement. Ainsi on procède en ligne de commande pour manipuler notre partie.    

Pour démarrer et charger les librairies utiles à une partie, on charge le fichier `index.php` en mémoire dans le mode interactif dans un `php -a` :    

```bash
php -a
# Interactive mode enabled

php > require('index.php') ;
php > $game2 = new cGameMgt(15) ;
php > $game2->drawChessBoard() ;
---------------------------------------------
|  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |
|  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |
---------------------------------------------
|  |  |  |  |  |  |  |  |  |  |  |  |  |  |  |
...

php > require_once 'class/cFireTower.php' ;
php > $tour = new FireTower() ;
je suis en train de me faire construire
php >

```

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

4. **Récupérer tous ces points pour les afficher plus tard sur une carto au choix**    
    - Ajouter une méthode `ChessRedis::getAllElements()` qui renvoie une liste de (par exemple)    
      *array(
        type=>'SniperTower',
        'id'=>'iddelobjet',
        lat=>45.22,
        long=>4.56)*,
        ...    
    avec autant d'arrays que d'éléments présents    

    - Je vous laisse imaginer la suite, on passera en web et on ajoutera du joli à tout ça. Et des **contrôleurs**   
    - *To be continued* ...
