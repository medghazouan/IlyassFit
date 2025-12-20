# ✅ CHECKLIST D'ACTIONS - AUDIT ILYASSFIT

Cette checklist regroupe **toutes les actions recommandées** classées par priorité et risque.  
Cochez les cases au fur et à mesure de la réalisation.

---

## 🟢 PHASE 1 : ACTIONS SANS RISQUE (1-2 jours)

### 🗑️ Suppression de Code Dupliqué

- [ ] **Action 1.1** : Supprimer ligne 347 dans `public/index.php`
  - Ligne : `require_once __DIR__ . '/../includes/config/db_config.php';`
  - Raison : Déjà inclus ligne 49
  - Temps estimé : 1 min
  - Fichier : `public/index.php`

- [ ] **Action 1.2** : Supprimer lignes 138-146 dans `admin/manage_pricing.php`
  - Code : Formulaire incomplet et non utilisé
  - Raison : Formulaire complet existe lignes 198-294
  - Temps estimé : 2 min
  - Fichier : `admin/manage_pricing.php`

### 🎨 Externalisation CSS

- [ ] **Action 1.3** : Créer fichier `admin/css/login.css`
  - Source : Copier lignes 54-357 de `admin/login.php`
  - Action : Créer le fichier CSS
  - Temps estimé : 10 min
  - Fichier source : `admin/login.php`
  - Fichier destination : `admin/css/login.css`

- [ ] **Action 1.4** : Remplacer CSS inline par link dans `login.php`
  - Supprimer : Lignes 54-357
  - Ajouter : `<link rel="stylesheet" href="css/login.css">`
  - Temps estimé : 2 min
  - Fichier : `admin/login.php`

### 📜 Externalisation JavaScript

- [ ] **Action 1.5** : Créer fichier `public/assets/js/main.js`
  - Action : Le fichier existe mais est vide
  - Temps estimé : 0 min
  - Fichier : `public/assets/js/main.js`

- [ ] **Action 1.6** : Déplacer scripts de `header.php` vers `main.js`
  - Script 1 : Navbar scroll (lignes 85-97)
  - Script 2 : Mobile menu toggle (lignes 100-141)
  - Script 3 : Active link management (lignes 142-254)
  - Temps estimé : 30 min
  - Fichier source : `public/components/header.php`
  - Fichier destination : `public/assets/js/main.js`

- [ ] **Action 1.7** : Déplacer scripts de `index.php` vers fichiers JS
  - Script 1 : About section animations (lignes 132-191)
  - Script 2 : Services slider (lignes 232-284)
  - Script 3 : Video modal (lignes 452-502)
  - Temps estimé : 30 min
  - Fichier source : `public/index.php`
  - Fichier destination : `public/assets/js/main.js`

### ⚡ Optimisations Performance

- [ ] **Action 1.8** : Ajouter preconnect Google Fonts
  - Fichier : `public/components/meta.php`
  - Ajouter avant les liens de fonts :
    ```html
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    ```
  - Temps estimé : 2 min

- [ ] **Action 1.9** : Ajouter lazy loading sur images
  - Fichiers : `public/index.php` (galerie)
  - Ajouter : `loading="lazy"` sur toutes les `<img>`
  - Temps estimé : 10 min
  - Impact : Performance +5-10%

### 🧹 Nettoyage de Code

- [ ] **Action 1.10** : Vérifier utilisation de `test.php`
  - Fichier : `public/test.php`
  - Action : Demander à l'équipe si utilisé
  - Si non : Supprimer ou déplacer vers `/dev/`
  - Temps estimé : 5 min (discussion) ou 1 min (suppression)

- [ ] **Action 1.11** : Décider du sort de `gallery.js`
  - Fichier : `public/assets/js/gallery.js` (vide)
  - Option 1 : Supprimer si inutilisé
  - Option 2 : Conserver pour développement futur
  - Temps estimé : 2 min

---

## 🟡 PHASE 2 : OPTIMISATIONS (2-3 jours)

### 🔧 Refactoring Code

- [ ] **Action 2.1** : Factoriser navigation dans `header.php`
  - Créer tableau PHP de liens
  - Remplacer lignes 12-44 (desktop)
  - Remplacer lignes 59-78 (mobile)
  - Temps estimé : 1h
  - Fichier : `public/components/header.php`
  - Gain : -30 lignes, maintenance facilitée

- [ ] **Action 2.2** : Optimiser requêtes SQL dans `manage_pricing.php`
  - Ligne 109-111 : 3 requêtes actuellement
  - Solution : 1 requête + filtrage PHP
  - Temps estimé : 30 min
  - Fichier : `admin/manage_pricing.php`
  - Gain : -2 requêtes SQL

### 📝 Documentation

- [ ] **Action 2.3** : Ajouter commentaires PHPDoc
  - Fichiers : `includes/functions/*.php`
  - Format : Documenter chaque fonction
  - Temps estimé : 2h
  - Exemple fourni dans `AUDIT_CODE_FIXES.md`

- [ ] **Action 2.4** : Documenter scripts complexes
  - Fichier : `public/components/header.php`
  - Scripts : IntersectionObserver, active link logic
  - Temps estimé : 30 min

### 🎨 Amélioration CSS

- [ ] **Action 2.5** : Vérifier et nettoyer CSS inutilisé
  - Fichiers : Tous les `.css`
  - Outil suggéré : PurgeCSS ou manuel
  - Temps estimé : 2h
  - Gain estimé : -20% taille CSS

---

## 🔴 PHASE 3 : SÉCURITÉ PRODUCTION (2-3 jours)

### 🔒 CSRF Protection

- [ ] **Action 3.1** : Créer fonction génération token CSRF
  - Fichier : `includes/functions/auth.php`
  - Code fourni dans `AUDIT_CODE_FIXES.md`
  - Temps estimé : 15 min

- [ ] **Action 3.2** : Implémenter CSRF dans formulaire contact
  - Fichier : `public/contact.php`
  - Ajouter champ hidden avec token
  - Temps estimé : 10 min

- [ ] **Action 3.3** : Vérifier CSRF dans traitement contact
  - Fichier : `public/process_contact.php`
  - Valider token avant traitement
  - Temps estimé : 10 min

- [ ] **Action 3.4** : Implémenter CSRF dans login admin
  - Fichier : `admin/login.php`
  - Temps estimé : 10 min

- [ ] **Action 3.5** : Implémenter CSRF dans tous les formulaires admin
  - Fichiers : `manage_*.php`
  - Temps estimé : 1h

### 🚦 Rate Limiting

- [ ] **Action 3.6** : Créer fonction rate limiting
  - Fichier : `includes/functions/security.php` (nouveau)
  - Code fourni dans `AUDIT_CODE_FIXES.md`
  - Temps estimé : 30 min

- [ ] **Action 3.7** : Appliquer rate limiting sur login
  - Fichier : `admin/login.php`
  - Limite : 5 tentatives / 15 minutes
  - Temps estimé : 15 min

- [ ] **Action 3.8** : Appliquer rate limiting sur contact
  - Fichier : `public/process_contact.php`
  - Limite : 3 messages / 1 heure
  - Temps estimé : 15 min

### 🔐 Configuration HTTPS

- [ ] **Action 3.9** : Activer HTTPS sur serveur production
  - Outil : Let's Encrypt (gratuit)
  - Action : Installation certificat SSL
  - Temps estimé : 1h (si Let's Encrypt)
  - Documentation : https://letsencrypt.org/

- [ ] **Action 3.10** : Modifier `session.cookie_secure`
  - Fichier : `includes/functions/auth.php` ligne 8
  - Changer : `0` → `1`
  - **ATTENTION** : Seulement APRÈS activation HTTPS
  - Temps estimé : 1 min

- [ ] **Action 3.11** : Forcer redirection HTTPS
  - Fichier : `.htaccess` (racine)
  - Ajouter règles de redirection
  - Code fourni dans `AUDIT_CODE_FIXES.md`
  - Temps estimé : 5 min

### 🔑 Variables d'Environnement

- [ ] **Action 3.12** : Créer fichier `.env`
  - Fichier : `.env` (racine)
  - Contenu : DB_HOST, DB_NAME, DB_USER, DB_PASS
  - Temps estimé : 5 min

- [ ] **Action 3.13** : Installer bibliothèque dotenv
  - Commande : `composer require vlucas/phpdotenv`
  - Temps estimé : 3 min

- [ ] **Action 3.14** : Modifier `db_config.php` pour utiliser .env
  - Fichier : `includes/config/db_config.php`
  - Code fourni dans `AUDIT_CODE_FIXES.md`
  - Temps estimé : 10 min

- [ ] **Action 3.15** : Ajouter `.env` au `.gitignore`
  - Fichier : `.gitignore`
  - Ligne : `.env`
  - Temps estimé : 1 min

---

## 🧪 PHASE 4 : TESTS (1 jour)

### ✅ Tests Fonctionnels

- [ ] **Test 4.1** : Login admin fonctionne
  - Page : `/admin/login.php`
  - Actions : Se connecter, vérifier session
  - Résultat attendu : Redirection vers dashboard

- [ ] **Test 4.2** : CRUD Gallery fonctionne
  - Page : `/admin/manage_gallery.php`
  - Actions : Créer, lire, modifier, supprimer image
  - Résultat attendu : Toutes les opérations fonctionnent

- [ ] **Test 4.3** : CRUD Reviews fonctionne
  - Page : `/admin/manage_reviews.php`
  - Actions : Créer, lire, modifier, supprimer review
  - Résultat attendu : Toutes les opérations fonctionnent

- [ ] **Test 4.4** : CRUD Messages fonctionne
  - Page : `/admin/manage_messages.php`
  - Actions : Lire, marquer comme lu, supprimer
  - Résultat attendu : Toutes les opérations fonctionnent

- [ ] **Test 4.5** : CRUD Pricing fonctionne
  - Page : `/admin/manage_pricing.php`
  - Actions : Créer, lire, modifier, supprimer plan
  - Résultat attendu : Toutes les opérations fonctionnent

- [ ] **Test 4.6** : Formulaire contact fonctionne
  - Page : `/public/contact.php`
  - Actions : Envoyer message
  - Résultat attendu : Message reçu dans admin

- [ ] **Test 4.7** : Navigation mobile fonctionne
  - Pages : Toutes
  - Actions : Ouvrir/fermer menu, naviguer
  - Résultat attendu : Menu s'ouvre/ferme correctement

- [ ] **Test 4.8** : Video modal fonctionne
  - Page : `/public/index.php`
  - Actions : Ouvrir modal, fermer, ESC, click outside
  - Résultat attendu : Modal s'ouvre/ferme correctement

### 🔒 Tests Sécurité

- [ ] **Test 4.9** : CSRF tokens fonctionnent
  - Action : Tenter de soumettre formulaire sans token
  - Résultat attendu : Erreur CSRF

- [ ] **Test 4.10** : Rate limiting fonctionne
  - Action : 6 tentatives login rapides
  - Résultat attendu : Blocage après 5 tentatives

- [ ] **Test 4.11** : Upload fichier malveillant échoue
  - Action : Uploader un `.php` renommé en `.jpg`
  - Résultat attendu : Upload refusé

- [ ] **Test 4.12** : SQL injection échoue
  - Action : Tenter `admin' OR '1'='1` dans login
  - Résultat attendu : Login échoue

### 🚀 Tests Performance

- [ ] **Test 4.13** : Temps de chargement homepage < 3s
  - Outil : Google PageSpeed Insights
  - Résultat attendu : Score > 80/100

- [ ] **Test 4.14** : Images lazy loading fonctionnent
  - Action : Vérifier dans DevTools (Network)
  - Résultat attendu : Images chargées à la demande

- [ ] **Test 4.15** : Cache CSS/JS fonctionne
  - Action : Vérifier headers HTTP
  - Résultat attendu : Cache-Control présent

---

## 📋 RÉCAPITULATIF PAR PRIORITÉ

### 🔴 CRITIQUE (Avant production)
- [ ] Action 3.9 : Activer HTTPS
- [ ] Action 3.10 : Changer cookie_secure à 1
- [ ] Actions 3.1-3.5 : Implémenter CSRF
- [ ] Actions 3.6-3.8 : Rate limiting

**Total estimé : 1-2 jours**

### 🟠 IMPORTANT (Recommandé avant production)
- [ ] Actions 1.1-1.9 : Optimisations simples
- [ ] Actions 2.1-2.2 : Refactoring code
- [ ] Actions 3.12-3.15 : Variables d'environnement

**Total estimé : 2-3 jours**

### 🟡 RECOMMANDÉ (Amélioration continue)
- [ ] Actions 1.10-1.11 : Nettoyage fichiers
- [ ] Actions 2.3-2.5 : Documentation et CSS
- [ ] Phase 4 : Tests complets

**Total estimé : 2-3 jours**

---

## 📊 SUIVI DE PROGRESSION

**Date de début** : ___/___/_____  
**Date de fin prévue** : ___/___/_____  

**Progression** :
- Phase 1 : ☐ 0% | ☐ 25% | ☐ 50% | ☐ 75% | ☐ 100%
- Phase 2 : ☐ 0% | ☐ 25% | ☐ 50% | ☐ 75% | ☐ 100%
- Phase 3 : ☐ 0% | ☐ 25% | ☐ 50% | ☐ 75% | ☐ 100%
- Phase 4 : ☐ 0% | ☐ 25% | ☐ 50% | ☐ 75% | ☐ 100%

**Progression globale** : _____ %

---

**💡 Conseil** : Travaillez phase par phase, toujours avec une branche Git séparée et tests après chaque modification.

**✅ Bonne chance !**
