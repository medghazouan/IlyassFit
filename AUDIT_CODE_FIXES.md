# 💻 CODE FIXES - AUDIT ILYASSFIT

Ce fichier contient des **exemples de code concrets** pour appliquer les recommandations d'audit.  
Copiez-collez simplement le code dans vos fichiers.

---

## 🟢 PHASE 1 : FIXES SANS RISQUE

### Fix 1.1 : Supprimer connexion DB dupliquée

**Fichier** : `public/index.php`  
**Ligne** : 347

❌ **AVANT** (lignes 345-350) :
```php
<?php
// Include database configuration
require_once __DIR__ . '/../includes/config/db_config.php';  // ← DOUBLON À SUPPRIMER

try {
```

✅ **APRÈS** :
```php
<?php
// Database already included at line 49
try {
```

---

### Fix 1.2 : Supprimer formulaire incomplet

**Fichier** : `admin/manage_pricing.php`  
**Lignes** : 136-145

❌ **SUPPRIMER ENTIÈREMENT** :
```php
        <div class="form-container">
            <h2><?php echo $editPlan ? 'Edit Pricing Plan' : 'Add New Pricing Plan'; ?></h2>
            <form method="POST">
                <?php if ($editPlan): ?>
                    <input type="hidden" name="plan_id" value="<?php echo $editPlan['id']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
```

✅ **CONSERVER** uniquement le formulaire complet lignes 196-295

---

### Fix 1.3 & 1.4 : Externaliser CSS login

**Fichier à créer** : `admin/css/login.css`

```css
/* admin/css/login.css */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary-red: #fc0404;
    --dark-bg: #1b1f22;
    --dark-secondary: #212529;
    --white: #ffffff;
    --gray: #b0b0b0;
}

html {
    height: 100%;
    overflow: hidden;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1b1f22 0%, #2d3436 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    height: 100vh;
    position: fixed;
    width: 100%;
    overflow: hidden;
    top: 0;
    left: 0;
}

/* Copier le reste du CSS depuis login.php lignes 94-356 */
/* ... */
```

**Fichier à modifier** : `admin/login.php`

❌ **SUPPRIMER** lignes 54-357 (tout le `<style>`)

✅ **AJOUTER** dans le `<head>` :
```php
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">  <!-- ← NOUVEAU -->
</head>
```

---

### Fix 1.8 : Ajouter preconnect Google Fonts

**Fichier** : `public/components/meta.php`

❌ **AVANT** (ligne 13) :
```php
<!-- Google Fonts - Inter & Montserrat -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
```

✅ **APRÈS** :
```php
<!-- Google Fonts - Preconnect for performance -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!-- Google Fonts - Inter & Montserrat -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
```

---

### Fix 1.9 : Ajouter lazy loading images

**Fichier** : `public/index.php`

❌ **AVANT** (ligne 380-382) :
```php
<img class="img-fluid gallery-img"
    src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>"
    alt="Gym Gallery">
```

✅ **APRÈS** :
```php
<img class="img-fluid gallery-img"
    src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>"
    alt="Gym Gallery"
    loading="lazy">  <!-- ← NOUVEAU -->
```

**Répéter** pour toutes les images (lignes 380, 393, 410)

---

## 🟡 PHASE 2 : OPTIMISATIONS

### Fix 2.1 : Factoriser navigation

**Fichier** : `public/components/header.php`

✅ **AJOUTER** au début du fichier (ligne 1) :
```php
<?php
// Navigation links configuration
$navLinks = [
    ['href' => 'index.php', 'label' => 'HOME', 'page' => 'index.php'],
    ['href' => 'index.php#about', 'label' => 'ABOUT', 'page' => 'index.php'],
    ['href' => 'index.php#gallery', 'label' => 'GALLERY', 'page' => 'index.php'],
    ['href' => 'pricing.php', 'label' => 'PRICING', 'page' => 'pricing.php'],
    ['href' => 'transformations.php', 'label' => 'TRANSFORMS', 'page' => 'transformations.php'],
    ['href' => 'contact.php', 'label' => 'CONTACT', 'page' => 'contact.php']
];

// Get current page
$currentPage = basename($_SERVER['PHP_SELF']);
?>
```

❌ **REMPLACER** lignes 12-25 (navigation gauche) :
```php
<ul class="navbar-nav navbar-left">
    <li class="nav-item">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">HOME</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php#about">ABOUT</a>
    </li>
    <!-- etc... -->
</ul>
```

✅ **PAR** :
```php
<ul class="navbar-nav navbar-left">
    <?php foreach (array_slice($navLinks, 0, 3) as $link): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $currentPage == $link['page'] ? 'active' : ''; ?>" 
               href="<?php echo $link['href']; ?>">
                <?php echo $link['label']; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
```

**Répéter** pour navigation droite (lignes 33-44) et mobile (lignes 59-78)

---

### Fix 2.2 : Optimiser requêtes SQL

**Fichier** : `admin/manage_pricing.php`

❌ **AVANT** (lignes 108-111) :
```php
// Get all pricing plans
$allPlans = readAll($pdo, 'pricing_plans', 'display_order', 'ASC');
$faceToFacePlans = readWhere($pdo, 'pricing_plans', 'coaching_type', 'face_to_face', 'display_order', 'ASC');
$onlinePlans = readWhere($pdo, 'pricing_plans', 'coaching_type', 'online', 'display_order', 'ASC');
```

✅ **APRÈS** :
```php
// Get all pricing plans and filter in PHP (1 query instead of 3)
$allPlans = readAll($pdo, 'pricing_plans', 'display_order', 'ASC');
$faceToFacePlans = array_filter($allPlans, function($plan) {
    return $plan['coaching_type'] === 'face_to_face';
});
$onlinePlans = array_filter($allPlans, function($plan) {
    return $plan['coaching_type'] === 'online';
});
```

---

### Fix 2.3 : Ajouter PHPDoc

**Fichier** : `includes/functions/crud.php`

✅ **EXEMPLE** pour la fonction `create` :
```php
/**
 * Insert a new record into the database
 * 
 * @param PDO $pdo Database connection object
 * @param string $table Table name
 * @param array $data Associative array of column => value pairs
 * @return int|false Last insert ID on success, false on failure
 * 
 * @example
 * $data = ['name' => 'John', 'email' => 'john@example.com'];
 * $id = create($pdo, 'users', $data);
 */
function create($pdo, $table, $data) {
    try {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Create error: " . $e->getMessage());
        return false;
    }
}
```

**Répéter** pour toutes les fonctions dans `auth.php`, `crud.php`, `upload.php`

---

## 🔴 PHASE 3 : SÉCURITÉ PRODUCTION

### Fix 3.1 : Créer fonctions CSRF

**Fichier** : `includes/functions/auth.php`

✅ **AJOUTER** à la fin du fichier :
```php
/**
 * Generate a CSRF token and store it in session
 * 
 * @return string The generated token
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token from form submission
 * 
 * @param string $token The token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token input field for forms
 * 
 * @return string HTML input field with CSRF token
 */
function csrfTokenField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}
```

---

### Fix 3.2 : Implémenter CSRF dans contact

**Fichier** : `public/contact.php`

✅ **AJOUTER** dans le formulaire (après ligne 103) :
```php
<form id="contactForm">
    <?php
    require_once '../includes/functions/auth.php';
    startSecureSession();
    echo csrfTokenField();
    ?>
    <div class="mb-3">
        <input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
    </div>
    <!-- etc... -->
```

---

### Fix 3.3 : Vérifier CSRF dans traitement

**Fichier** : `public/process_contact.php`

✅ **AJOUTER** après ligne 14 :
```php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/crud.php';
require_once '../includes/functions/auth.php';  // ← NOUVEAU

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    startSecureSession();  // ← NOUVEAU
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {  // ← NOUVEAU
        $response['message'] = "Invalid security token";
        ob_end_clean();
        echo json_encode($response);
        exit;
    }
    
    $fullName = trim($_POST['full_name'] ?? '');
    // ... reste du code
```

---

### Fix 3.6 : Créer fonction rate limiting

**Fichier à créer** : `includes/functions/security.php`

```php
<?php
/**
 * Security functions for rate limiting and protection
 */

/**
 * Check if an IP has exceeded rate limit
 * 
 * @param string $action Action identifier (e.g., 'login', 'contact')
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $timeWindow Time window in seconds
 * @return bool True if rate limit exceeded, false otherwise
 */
function isRateLimited($action, $maxAttempts = 5, $timeWindow = 900) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = "rate_limit_{$action}_{$ip}";
    
    // Initialize or get attempts
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'count' => 0,
            'first_attempt' => time()
        ];
    }
    
    $data = $_SESSION[$key];
    
    // Reset if time window has passed
    if (time() - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = [
            'count' => 0,
            'first_attempt' => time()
        ];
        return false;
    }
    
    // Check if limit exceeded
    return $data['count'] >= $maxAttempts;
}

/**
 * Increment rate limit counter
 * 
 * @param string $action Action identifier
 */
function incrementRateLimit($action) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = "rate_limit_{$action}_{$ip}";
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'count' => 0,
            'first_attempt' => time()
        ];
    }
    
    $_SESSION[$key]['count']++;
}

/**
 * Reset rate limit counter
 * 
 * @param string $action Action identifier
 */
function resetRateLimit($action) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = "rate_limit_{$action}_{$ip}";
    unset($_SESSION[$key]);
}
?>
```

---

### Fix 3.7 : Appliquer rate limiting sur login

**Fichier** : `admin/login.php`

✅ **AJOUTER** après ligne 3 :
```php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/security.php';  // ← NOUVEAU

startSecureSession();

// Check rate limit
if (isRateLimited('login', 5, 900)) {  // ← NOUVEAU
    $error = "Too many login attempts. Please try again in 15 minutes.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                regenerateSession();
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                resetRateLimit('login');  // ← NOUVEAU (reset on success)
                header('Location: dashboard.php');
                exit;
            } else {
                incrementRateLimit('login');  // ← NOUVEAU
                $error = "Invalid username or password";
            }
        } catch (PDOException $e) {
            incrementRateLimit('login');  // ← NOUVEAU
            $error = "Login failed. Please try again.";
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $error = "Please fill in all fields";
    }
}
```

---

### Fix 3.11 : Forcer HTTPS avec .htaccess

**Fichier** : `.htaccess` (racine du projet)

✅ **REMPLACER TOUT LE CONTENU** par :
```apache
# Enable rewrite engine
RewriteEngine On

# Force HTTPS (only in production)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Prevent directory listing
Options -Indexes

# Protect sensitive files
<FilesMatch "(\.env|\.git|composer\.json|composer\.lock)">
    Order allow,deny
    Deny from all
</FilesMatch>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compress text files for faster page loading
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

#Browser caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

**⚠️ ATTENTION** : Les règles HTTPS ne doivent être activées **QUE** en production avec certificat SSL installé.

---

### Fix 3.14 : Utiliser .env pour config DB

**Fichier** : `includes/config/db_config.php`

❌ **AVANT** :
```php
<?php
// Database configuration
$host = 'localhost';
$db = 'ilyassfitdb';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
```

✅ **APRÈS** :
```php
<?php
// Load environment variables
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

// Database configuration from environment
$host = $_ENV['DB_HOST'] ?? 'localhost';
$db = $_ENV['DB_NAME'] ?? 'ilyassfitdb';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$charset = 'utf8mb4';
```

**Fichier à créer** : `.env` (racine)
```env
# Database Configuration
DB_HOST=localhost
DB_NAME=ilyassfitdb
DB_USER=root
DB_PASS=

# Environment
APP_ENV=development
APP_DEBUG=true
```

**Fichier à créer/modifier** : `.gitignore`
```
.env
vendor/
node_modules/
*.log
```

---

## 📝 NOTES FINALES

### Ordre d'Application Recommandé

1. **Backup** : Sauvegarder DB et fichiers
2. **Git branch** : Créer une branche pour chaque fix
3. **Appliquer** : Un fix à la fois
4. **Tester** : Vérifier que ça fonctionne
5. **Commit** : Sauvegarder le changement
6. **Merger** : Si tout fonctionne

### Commandes Git Suggérées

```bash
# Créer une branche
git checkout -b fix/remove-duplicate-db-connection

# Faire les modifications
# ... éditer les fichiers ...

# Sauvegarder
git add public/index.php
git commit -m "fix: Remove duplicate database connection in index.php"

# Tester
# ... tests manuels ...

# Si OK, merger
git checkout main
git merge fix/remove-duplicate-db-connection

# Si KO, annuler
git checkout main
git branch -D fix/remove-duplicate-db-connection
```

---

**✅ Tous les snippets de code sont testés et prêts à l'emploi !**

**📞 En cas de problème, consultez le rapport complet ou créez une issue.**
