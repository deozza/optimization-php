1 - why performance is important ?
 
La performance d'un site web est important car cela est cruciale pour attirer et retenir les visiteurs sur le site.
Une page qui est lente décourage souvent les visiteurs , ce qui entraine la diminution duu traffic sur le site et 
par consequent moins de revenus (s'il s'agit par exemple d'un site de vente)

2- Hypothèses ( Analyse du code )
- CHARGEMENT D'IMAGES NON OPTIMISÉES 
* Les images sont chargées en haute résolution (h-[640px] object-cover), sans optimisation ni lazy loading.
* Toutes les images associées sont chargées même si l’utilisateur ne les voit pas immédiatement.
Impact : Temps de chargement élevé et consommation excessive de bande passante.

- UTILISATION DE Raw
* {{ item.title | raw }} et {{ item.description | raw }} posent un risque potentiel d’injection XSS.
Impact : Failles de sécurité potentielles.

- CHARGEMENT INEFFICACE DES DONNÉES depuis la base de donnée PostgreSQL :
Le code effectue plusieurs requêtes pour récupérer les données :
* findAll() sur GalaxyRepository, ce qui charge potentiellement toutes les galaxies sans pagination.
* find() sur ModelesRepository à chaque itération, ce qui entraîne une requête SQL par galaxie.
* findBy() sur ModelesFilesRepository et find() sur DirectusFilesRepository, ce qui ajoute encore plus de requêtes.
Impact: Cela surchage la base de donnée et rélentit la page 

- MANQUE DE MISE EN CACHE
* Aucun cache n’est mis en place pour les requêtes répétitives.
* Pas de cache HTTP ou d’optimisation via Symfony.
Impact: Recalcul constant des mêmes données, rendant le serveur inefficace 

- NOMBRE DE REQUETES SQL EXCESSIF

3-Tests and Measurements
Les outils suivants permet de confirmer ces hypothèses : 

. Symfony Profiler :Pour vérifier le nombre de requêtes SQL et leur impact.
. Blackfire.io : Identifier les goulots d’étranglement.
. WebPageTest & Lighthouse : Analyser les performances globales et le chargement des assets.
. GTmetrix : Vérifier le temps de chargement des images et la taille des fichiers.

Avec SYMFONY PROFILER dans Performances on peut voir :
Temps total d'exécution : 3568 ms -> Beaucoup trop long
Temps passé dans le contrôleur : 3223 ms
La majorité du temps est consommée dans le controleur , ce qui indique que les requêtes et le traitement des données prennent trop de temps ce qui confirme mes hypothèses

Le temps passé sur Doctrine : 53.7 ms n'est pas énorme donc les requêtes sql ne sont la principale cause
Le temps passé à rendre carousel/index.html.twig et base.html.twig : 3067 ms on peut donc remarquer que le moteur de rendu twig est extrement lent et cela est du : les images trop lourdes à charger, la boucle pour l'affichage des images, le manque de cache pour le rendu du template 

SOLUTIONS IMMÉDIATES

1. Optimisation des requêtes SQL
* Utiliser JOIN pour récupérer les modèles et fichiers en une seule requête SQL.
* Ajouter des index sur les colonnes utilisées fréquemment (modeles_id, directus_files_id).
* Mettre en place une pagination pour findAll().
2. Optimisation des images
* Ajouter du lazy loading (loading='lazy').
* Générer des miniatures pour les aperçus.
* Utiliser un CDN pour le chargement des images.
3. Mise en cache
* Utiliser Symfony Cache pour stocker les requêtes SQL fréquentes.
* Ajouter du cache HTTP avec Cache-Control et ETag.
4. Sécurité et nettoyage Twig
* Remplacer {{ item.title | raw }} par {{ item.title|e }} pour éviter les injections XSS.
* Vérifier les entrées utilisateur et valider les données affichées.

CONCLUSION 

Après application des corrections, faudra effectuer de nouveaux tests pour mesurer les améliorations :
* Vérifier la réduction du nombre de requêtes SQL.
* Mesurer la réduction du temps de chargement.
* Comparer les performances avec et sans cache.

AMELIORATIONS FUTURES
* Ajouter un système de préchargement des images côté serveur.
* Migrer les images vers un service cloud type S3 pour alléger le serveur.
* Étudier l’impact de l’augmentation des ressources VPS (RAM/CPU).
Avec ces optimisations, la page carousel sera plus rapide et plus efficace, améliorant ainsi l’expérience utilisateur et la conversion.


 