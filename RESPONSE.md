# Optimisation des performances web

## Intro

Les performances web sont cruciales pour plusieurs raisons :

1. **Expérience utilisateur** : Les utilisateurs abandonnent les sites qui se chargent lentement. Selon des études, 53% des utilisateurs quittent un site mobile si le chargement prend plus de 3 secondes.

2. **Taux de conversion** : Une amélioration de la vitesse de chargement de 0,1 seconde peut augmenter les conversions de 8%. Pour un site e-commerce comme celui-ci qui vend des guitares, c'est directement lié au chiffre d'affaires.

3. **Référencement (SEO)** : Google utilise la vitesse de chargement comme facteur de classement. Les sites lents sont pénalisés dans les résultats de recherche.

4. **Coûts d'infrastructure** : Un code inefficace consomme plus de ressources serveur, ce qui augmente les coûts d'hébergement.

## Hypothesis

Après analyse du code, plusieurs problèmes potentiels apparaissent :

1. **Requêtes N+1** : Dans le `CarouselController`, pour chaque galaxie, on fait une requête pour trouver le modèle, puis pour chaque modèle, on fait plusieurs requêtes pour trouver les fichiers associés. Cela crée un effet cascade de requêtes SQL.

2. **Chargement d'images non optimisées** : Les images sont chargées directement depuis le serveur sans optimisation apparente (redimensionnement, compression).

3. **Absence de mise en cache** : Aucun mécanisme de mise en cache n'est visible dans le code.

4. **Absence de chargement différé (lazy loading)** : Les images semblent être chargées immédiatement, sans chargement différé.

5. **Absence de pagination** : Toutes les données sont chargées en une seule fois, ce qui peut être problématique si la base de données contient beaucoup d'entrées.

## Tests and measurements

Pour confirmer ces hypothèses, on pourrait utiliser les outils suivants :

1. **Lighthouse** : Pour mesurer les performances globales, l'accessibilité et les bonnes pratiques.

2. **WebPageTest** : Pour analyser en détail les temps de chargement et identifier les goulots d'étranglement.

3. **Symfony Profiler** : Pour analyser le nombre de requêtes SQL et leur temps d'exécution.

4. **Blackfire.io** : Pour profiler l'application PHP et identifier les fonctions qui consomment le plus de ressources.

Les métriques à surveiller seraient :

- Temps de chargement total de la page
- Nombre de requêtes SQL
- Temps jusqu'au premier rendu (First Contentful Paint)
- Temps jusqu'à l'interactivité (Time to Interactive)
- Taille des ressources (images, CSS, JS)
- Nombre de requêtes HTTP

### Analyse des performances avec Lighthouse

![Analyse des performances avec Lighthouse](/lighthouse-images/old-lighthouse1.png)
![Analyse des performances avec Lighthouse](/lighthouse-images/old-lighthouse2.png)
![Analyse des performances avec Lighthouse](/lighthouse-images/old-lighthouse3.png)

## Solutions

1. **Optimisation des requêtes SQL** :
   - Utiliser des jointures pour récupérer les données en une seule requête
   - Implémenter des méthodes personnalisées dans les repositories pour éviter les requêtes N+1

2. **Optimisation des images** :
   - Redimensionner les images selon leur utilisation
   - Compresser les images
   - Utiliser des formats modernes comme WebP
   - Implémenter le chargement différé grâce au lazy loading

3. **Mise en cache** :
   - Mettre en cache les résultats des requêtes avec Symfony Cache
   - Utiliser le fichier .htaccess pour gérer la mise en cache des ressources statiques

4. **Pagination** :
   - Implémenter une pagination au scroll, pour ne charger qu'un nombre limité d'éléments à la fois

5. **Optimisation du code Twig** :
   - Activer la mise en cache des templates Twig

## Conclusion

Après implémentation des solutions, il faudrait refaire les tests avec les mêmes outils pour comparer les résultats :

- Réduction du temps de chargement total
- Diminution du nombre de requêtes SQL
- Amélioration des métriques Web (LCP, FID, CLS)
- Réduction de la taille des ressources

### Analyse des nouvelles performances avec Lighthouse

![Analyse des nouvelles performances avec Lighthouse](/lighthouse-images/new-lighthouse1.png)
![Analyse des nouvelles performances avec Lighthouse](/lighthouse-images/new-lighthouse2.png)
![Analyse des nouvelles performances avec Lighthouse](/lighthouse-images/new-lighthouse3.png)

### Ce qui pourrait être fait à l'avenir pour améliorer encore les performances

1. **Mise en place d'un CDN** (Content Delivery Network) pour distribuer les ressources statiques plus près des utilisateurs, particulièrement important puisque le serveur est situé au Canada mais les utilisateurs peuvent être partout dans le monde.

2. **Implémentation d'une API GraphQL** pour permettre au client de demander uniquement les données dont il a besoin.

3. **Mise en place d'une PWA** (Progressive Web App) pour permettre un fonctionnement hors ligne et améliorer l'expérience utilisateur.

4. **Implémentation d'une stratégie de préchargement** pour anticiper les besoins de l'utilisateur et charger les ressources avant qu'elles ne soient nécessaires.

5. **Monitoring continu des performances** avec des outils comme New Relic ou Datadog pour détecter rapidement les régressions.

6. **Optimisation de la base de données** avec des index appropriés et une révision régulière des requêtes les plus coûteuses.
