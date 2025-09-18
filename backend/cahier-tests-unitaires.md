# CAHIER DES TESTS UNITAIRES
## PARC NATIONAL DES CALANQUES - SYSTÈME D'AUTHENTIFICATION

**Projet :** Application Web Parc National des Calanques  
**Équipe :** Développement collaboratif  
**Branche :** Auth  
**Période :** 18 septembre - 16 octobre 2025 (4 semaines)  
**Framework de test :** PHPUnit  

---

## PLANNING DES TESTS - 4 SEMAINES

### SEMAINE 1 : Tests Unitaires Base (18-24 septembre)

| Date | Composant | Tests à réaliser | Priorité | Statut |
|------|-----------|------------------|----------|--------|
| Lundi 18 | User.php | testCreateUser, testEmailExists | HAUTE | ✅ TERMINÉ |
| Mardi 19 | User.php | testLogin, testReadOne | HAUTE | ✅ TERMINÉ |
| Mercredi 20 | User.php | testUpdate, testDelete, testValidate | HAUTE | ✅ TERMINÉ |
| Jeudi 21 | Validator.php | testValidateEmail, testValidatePassword | HAUTE | ✅ TERMINÉ |
| Vendredi 22 | Validator.php | testSanitizeString, testValidateRole, testValidateRegistration | HAUTE | ✅ TERMINÉ |

**Objectifs semaine 1 :**
- ✅ Validation des modèles de base
- ✅ Tests de sécurité des mots de passe
- ✅ Validation des données utilisateur

---

### SEMAINE 2 : Tests JWT & Sécurité (25 septembre - 1 octobre)

| Date | Composant | Tests à réaliser | Priorité | Statut |
|------|-----------|------------------|----------|--------|
| Lundi 25 | JWTHandler.php | testGenerateToken, testVerifyValidToken | HAUTE | ✅ TERMINÉ |
| Mardi 26 | JWTHandler.php | testVerifyInvalidToken, testVerifyTamperedToken | HAUTE | ✅ TERMINÉ |
| Mercredi 27 | JWTHandler.php | testCreateUserPayload, testGenerateRefreshToken | MOYENNE | ✅ TERMINÉ |
| Jeudi 28 | JWTHandler.php | testVerifyRefreshToken | MOYENNE | ✅ TERMINÉ |
| Vendredi 29 | AuthMiddleware.php | testAuthenticateUser, testRequireRole | HAUTE | ⏳ À FAIRE |

**Objectifs semaine 2 :**
- ✅ Sécurité des tokens JWT
- ✅ Gestion des refresh tokens
- ⏳ Middleware d'authentification

---

### SEMAINE 3 : Tests Intégration & APIs (2-8 octobre)

| Date | Composant | Tests à réaliser | Priorité | Statut |
|------|-----------|------------------|----------|--------|
| Lundi 2 | AuthenticationFlowTest | testCompleteRegistrationAndLoginFlow | HAUTE | ✅ TERMINÉ |
| Mardi 3 | AuthenticationFlowTest | testUserUpdateFlow, testVisiteurProfileFlow | MOYENNE | ✅ TERMINÉ |
| Mercredi 4 | API Tests | testRegisterAPI, testLoginAPI | HAUTE | ⏳ À FAIRE |
| Jeudi 5 | API Tests | testRefreshTokenAPI, testProfileAPI | MOYENNE | ⏳ À FAIRE |
| Vendredi 6 | API Tests | testLogoutAPI, testProtectedRoutes | MOYENNE | ⏳ À FAIRE |

**Objectifs semaine 3 :**
- ✅ Flux d'authentification complets
- ⏳ Tests des endpoints API
- ⏳ Validation des réponses HTTP

---

### SEMAINE 4 : Tests Avancés & Couverture (9-16 octobre)

| Date | Composant | Tests à réaliser | Priorité | Statut |
|------|-----------|------------------|----------|--------|
| Lundi 9 | Visiteur.php | testCreateVisiteur, testReadByUserId | MOYENNE | ⏳ À FAIRE |
| Mardi 10 | Visiteur.php | testUpdateVisiteur, testDeleteVisiteur | MOYENNE | ⏳ À FAIRE |
| Mercredi 11 | RoleBasedAccess.php | testCheckPermission, testResourceOwnership | HAUTE | ⏳ À FAIRE |
| Jeudi 12 | Database.php | testConnection, testTransactions | MOYENNE | ⏳ À FAIRE |
| Vendredi 13 | Tests Performance | testTokenGeneration, testDatabaseQueries | BASSE | ⏳ À FAIRE |
| Lundi 16 | Couverture & Rapport | Analyse couverture, rapport final | HAUTE | ⏳ À FAIRE |

**Objectifs semaine 4 :**
- ⏳ Tests des modèles étendus
- ⏳ Contrôle d'accès par rôles
- ⏳ Optimisation et performance

---

## MÉTRIQUES ET OBJECTIFS

### Couverture de Code
- **Objectif :** ≥ 85%
- **Actuel :** ~60%
- **Tests unitaires :** 45+ tests (25 ✅ terminés)
- **Tests d'intégration :** 8+ tests (3 ✅ terminés)
- **Tests API :** 12+ tests (0 terminés)
- **Assertions totales :** 150+ (80 actuelles)

### Composants Testés
- ✅ **User.php** - Modèle utilisateur complet
- ✅ **Validator.php** - Validation des données
- ✅ **JWTHandler.php** - Gestion des tokens
- ⏳ **AuthMiddleware.php** - Middleware d'authentification
- ⏳ **Visiteur.php** - Profils visiteurs
- ⏳ **RoleBasedAccess.php** - Contrôle d'accès
- ⏳ **APIs** - Endpoints REST

---

## PROCHAINES ÉTAPES IMMÉDIATES

### Cette semaine (25 septembre - 1 octobre)
1. **Lundi :** Finaliser tests AuthMiddleware
2. **Mardi :** Tests de sécurité avancés JWT
3. **Mercredi :** Tests d'autorisation par rôles
4. **Jeudi :** Tests de gestion des erreurs
5. **Vendredi :** Révision et optimisation

### Commandes utiles
\`\`\`bash
# Exécuter tous les tests
./run-tests.sh

# Tests avec couverture
composer test-coverage

# Tests spécifiques
./vendor/bin/phpunit tests/Unit/UserTest.php
\`\`\`

---

## NOTES TECHNIQUES

### Configuration PHPUnit
- **Version :** PHPUnit 9.x
- **Base de données :** SQLite en mémoire pour tests
- **Fixtures :** DatabaseTestHelper pour données de test
- **Assertions :** Minimum 3 par test

### Bonnes Pratiques
- Tests isolés et indépendants
- Noms de tests descriptifs
- Données de test réalistes
- Nettoyage après chaque test
- Documentation des cas limites

### Critères de Validation
- ✅ Tous les tests passent
- ✅ Couverture ≥ 85%
- ✅ Aucune régression
- ✅ Performance acceptable
- ✅ Documentation à jour

---

**Document généré le :** 18 septembre 2025  
**Dernière mise à jour :** Version 1.0  
**Responsable :** Équipe de développement Auth
