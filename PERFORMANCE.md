Améliorations des performances

Introduction

Pourquoi la performance est-elle essentielle ?

La performance est un facteur déterminant dans l'expérience utilisateur et le référencement d'un site. Une application lente peut frustrer les utilisateurs et impacter le taux de conversion, en particulier sur mobile.

Temps de chargement : 53 % des utilisateurs quittent un site si son chargement dépasse trois secondes.

Fluidité de l'application : Un manque de réactivité nuit à l'expérience et peut entraîner une perte d'engagement.

L'optimisation des performances est donc cruciale pour garantir un site rapide, réactif et agréable à utiliser.

Hypothèses

Requêtes excessives dans la base de données

Dans app/src/Controller/CarouselController.php, plusieurs requêtes indépendantes récupèrent les modèles de chaque galaxie et leurs fichiers associés, augmentant le nombre total de requêtes SQL et ralentissant l'affichage.

Surcharge d'images côté client

L'absence d'optimisation des images entraîne un chargement excessif, impactant négativement la vitesse de rendu de la page.

Délais liés à l'emplacement du serveur

Hébergé au Canada, le serveur peut générer une latence importante pour des utilisateurs situés en Europe ou en Asie.

Limitations des ressources serveur

Le VPS dispose de 2 CPU et 2 Go de RAM, ce qui peut limiter la gestion des requêtes. L'optimisation de PostgreSQL via PgBouncer pourrait améliorer les performances.

Tests et mesures

Outils utilisés

Symfony Debug Bar

DevTools Chrome (onglet "Network")

Lighthouse

Monitoring des performances serveur (CPU, RAM, base de données)

Résultats

Analyse des requêtes SQL

Symfony Debug Bar indique 164 requêtes pour générer la page, entraînant un temps d'affichage de 1,5 seconde, ce qui reste trop long.

Optimisation des images

Chargement de 121 images totalisant 737 Mo.

Lighthouse attribue un score de 41/100 en mode mobile.

Temps de chargement critiques :

"First Contentful Paint" : 2,1 secondes

"Largest Contentful Paint" : 139,4 secondes

"Total Blocking Time" : 2,8 secondes

Charge serveur

CPU et mémoire fortement sollicités, ralentissant Symfony et PostgreSQL.

Solutions

Optimisation des requêtes SQL

Réduction du nombre de requêtes avec des jointures SQL dans GalaxyRepository pour regrouper les données nécessaires en une seule requête.

Ajout d'index PostgreSQL sur les colonnes fréquemment interrogées.

Utilisation de PgBouncer pour optimiser la gestion des connexions SQL.

Optimisation des images

Ajout de loading="lazy" pour un chargement différé des images.

Conversion en WebP pour réduire leur poids.

Utilisation de srcset pour adapter la taille de l'image en fonction de l'écran.

Distribution des images via un CDN.

Script de conversion en WebP :

# Conversion des fichiers .jpg et .png en .webp
type="jpg png"
for ext in $type; do
  for file in *.$ext; do
    cwebp "$file" -o "${file%.$ext}.webp"
  done
done

Optimisation du serveur

Activation de OPcache pour éviter la recompilation du code PHP à chaque requête.

Mise en cache HTTP avec Varnish ou Redis.

Optimisation de TailwindCSS en purgeant les classes inutilisées.

Résultats après optimisation

Optimisation des requêtes SQL

Passage de 164 requêtes à 1 requête unique, réduisant le temps de chargement à 0,1 seconde.

Optimisation des images

Nombre d'images chargées réduit à 11.

Poids total réduit de 737 Mo à 136 Mo.

Optimisation du serveur

Réduction significative de l'utilisation CPU et RAM grâce à PgBouncer et OPcache.

Axes d'amélioration future

Mise en place d'un CDN pour les images.

Hébergement plus proche des utilisateurs.

Utilisation de Thumbhash pour afficher des placeholders d’images et améliorer la perception de rapidité.

Ajout de logs et monitoring pour un suivi des performances.