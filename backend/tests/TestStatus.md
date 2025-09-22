# Statut des tests du backend

Ce tableau récapitule les tests unitaires et d'intégration réalisés pour le projet Parc National des Calanques.

| Nom du test                | Type         | Objectif principal                                      | Statut actuel   |
|----------------------------|--------------|---------------------------------------------------------|-----------------|
| UserTest                   | Unitaire     | CRUD utilisateur, login, vérification email              | OK              |
| JWTHendlerTest             | Unitaire     | Génération, vérification, sécurité des tokens JWT        | OK              |
| ValidatorTest              | Unitaire     | Validation email, mot de passe, nettoyage de chaînes     | OK              |
| AuthenticationFlowTest     | Intégration  | Inscription, profil visiteur, login, JWT, refresh token  | OK              |

**À améliorer :**
- Ajouter des tests pour les autres entités (camping, sentier, notification, etc.)
- Tester les cas d’erreur et la sécurité avancée
- Vérifier la couverture des tests pour les flux complets et les cas limites
