## Pourquoi la performance est importante ?

- **Expérience utilisateur** : Une page lente réduit l'engagement et augmente le taux de rebond
- **SEO** : Google favorise les sites rapides dans ses résultats de recherche
- **Charge serveur** : Une application mal optimisée consomme plus de ressources, ce qui génère des coûts supplémentaires et des ralentissements

## Hypothèses

- **Problèmes liés aux requêtes SQL** : Trop de requêtes qui ralentissent le chargement des données
- **Mauvaise gestion du cache** : Absence de mise en cache des données ou d'utilisation d'un système de cache performant
- **Problèmes front-end** : Chargement excessif de ressources CSS/JS (Tailwindcss mal optimisé, JavaScript bloquant, images non compressées)
- **Configuration Symfony** : Utilisation incorrecte du cache HTTP, logs excessifs, ou mauvaise gestion des assets

## Tests et mesures
Voir les images

### Outils utilisés

- **Lighthouse** : Audit de performance et d'accessibilité directement intégré à Chrome
- **Symfony Profiler** : Analyse des requêtes SQL, de la consommation mémoire et du temps d'exécution des composants Symfony

### Métriques à surveiller

- Temps de chargement de la page
- Nombre et durée des requêtes SQL
- Taille des images
- Score Lighthouse

## Résultats des tests et problèmes identifiés

## Solutions

- Optimisation des requêtes SQL : utilisation d'id du modèle
- Compression des images pour les rendre plus légères
- Activation du lazy loading pour les images

Autres solutions possibles :
- Choisir un hébergeur plus proche (Europe plutôt que Canada)
- Réduction des logs excessifs pour alléger la charge serveur
- Activation de l’OpCache PHP pour améliorer l’exécution des scripts

## Conclusion

Une première série d’optimisations permettra d’améliorer la vitesse de la page `/carousel`, notamment en réduisant les requêtes SQL et en allégeant les ressources front-end