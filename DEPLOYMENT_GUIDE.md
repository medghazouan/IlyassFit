# Hostinger Deployment Guide for ilyass.fit

## Step 1: Create Database on Hostinger

1. Log into **Hostinger hPanel**
2. Go to **Databases** → **MySQL Databases**
3. Create a new database:
   - Database name: `ilyassfit` (Hostinger will prefix it, e.g., `u123456789_ilyassfit`)
   - Username: `admin` (will become `u123456789_admin`)
   - Password: Create a strong password
4. **Save these credentials!** You'll need them for `db_config.php`

---

## Step 2: Export Your Local Database

1. Open **phpMyAdmin** (http://localhost/phpmyadmin)
2. Select your database: `ilyassfitdb`
3. Click **Export** → **Go** (download the `.sql` file)

---

## Step 3: Import Database to Hostinger

1. In Hostinger hPanel, go to **Databases** → **phpMyAdmin**
2. Select your newly created database
3. Click **Import** → Choose the `.sql` file → **Go**

---

## Step 4: Update Database Credentials

Edit `includes/config/db_config.php` and replace:
```php
$db = 'YOUR_DATABASE_NAME';    // Your actual database name
$user = 'YOUR_DATABASE_USER';  // Your actual username
$pass = 'YOUR_DATABASE_PASSWORD'; // Your actual password
```

---

## Step 5: Upload Files to Hostinger

### Option A: File Manager (FTP)
1. Go to Hostinger hPanel → **Files** → **File Manager**
2. Navigate to `public_html` folder
3. Upload ALL files from your `IlyassFit` folder

### Option B: Git (Recommended)
1. In Hostinger hPanel, go to **Advanced** → **GIT**
2. Connect your GitHub repository
3. Pull the `dev` branch

### Folder Structure on Hostinger:
```
public_html/
├── .htaccess
├── admin/
├── includes/
├── public/
└── ...
```

---

## Step 6: Verify Domain Settings

1. Make sure your GoDaddy domain `ilyass.fit` points to Hostinger nameservers
2. In Hostinger, verify the domain is connected
3. Enable **SSL Certificate** (free with Hostinger)

---

## Step 7: Test Your Site

1. Visit `https://ilyass.fit` - should show the homepage
2. Visit `https://ilyass.fit/admin/login.php` - should show admin login
3. Test all features:
   - [ ] Homepage loads
   - [ ] Pricing page works
   - [ ] Contact form submits
   - [ ] Admin login works
   - [ ] Admin dashboard loads
   - [ ] PWA can be installed

---

## Troubleshooting

### 500 Internal Server Error
- Check `.htaccess` syntax
- Check PHP version (needs 8.0+)

### Database Connection Error
- Verify credentials in `db_config.php`
- Check database exists in Hostinger

### 404 Errors for Pages
- Verify `.htaccess` is uploaded
- Check if `mod_rewrite` is enabled

### Admin Login Not Working
- Check if sessions are working
- Clear browser cache
