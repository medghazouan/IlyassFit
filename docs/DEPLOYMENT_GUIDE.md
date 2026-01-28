# Deployment Guide - Hostinger

This guide covers how to deploy the **IlyassFit** project to Hostinger Shared Hosting.

## 1. Prepare Your Files

The project uses a secure structure where the "public" contents are separated from the "logic". The `.htaccess` file in the root handles the routing.

**Structure to Upload:**
You will upload the **contents** of your local project folder directly into the `public_html` folder on Hostinger.

```
public_html/
├── .htaccess            <-- Key file! Handles routing to public/
├── admin/               <-- Backend folder
├── includes/            <-- Database config & functions
├── public/              <-- Frontend assets & pages
└── ...
```

## 2. Database Setup

1.  **Log in to Hostinger hPanel**.
2.  Go to **Databases** > **Management**.
3.  Create a New MySQL Database:
    *   **Database Name**: e.g., `u123456789_ilyassfit`
    *   **Username**: e.g., `u123456789_admin`
    *   **Password**: Create a strong password (and copy it!).
    *   **Click Create**.
4.  **Import Data**:
    *   Click **Enter phpMyAdmin** next to your new database.
    *   Go to the **Import** tab.
    *   Upload your local database SQL export (if you have one) or manually create the tables (`users`, `pricing_plans`, `gallery`, `reviews`, `messages`).

## 3. Update Configuration

Before or after uploading, you must update the database connection details.

1.  Open `includes/config/db_config.php`.
2.  Edit the **Production** section:

```php
} else {
    // Production (Hostinger) Credentials
    $host = 'localhost'; // Usually remains 'localhost' on Hostinger
    $db = 'u123456789_ilyassfit'; // Your NEW Database Name
    $user = 'u123456789_admin';   // Your NEW Username
    $pass = 'YourStrongPassword123!'; // Your NEW Password
}
```

## 4. Uploading Files

1.  Go to **Hostinger Dashboard** > **Files** > **File Manager**.
2.  Navigate into `public_html`.
3.  **Delete** the default `default.php` or `index.php` if Hostinger put one there.
4.  **Upload** all your project files and folders (`admin`, `includes`, `public`, `.htaccess`, etc.).
    *   *Tip*: It is faster to Zip your project locally, upload the `.zip` file, and then right-click > **Extract** it in the File Manager.

## 5. Verify Permissions

Ensure the upload folder is writable so you can upload images from the admin panel.

1.  In File Manager, navigate to `public/images/uploads`.
2.  Right-click the `uploads` folder > **Permissions**.
3.  Ensure it is set to **755** (standard) or **775**.

## 6. Testing

1.  Visit your domain (e.g., `www.yourdomain.com`).
    *   It should automatically show the content from `public/index.php` without showing `/public/` in the URL.
2.  Visit `www.yourdomain.com/admin`.
    *   Log in and test uploading an image to the gallery to confirm database connection and folder permissions.

## Troubleshooting

-   **500 Internal Server Error**: Usually an issue with `.htaccess`.
    *   Ensure `RewriteBase /` is uncommented if you are in a subfolder, or leave it commented for root.
    *   Check that `public_html/.htaccess` exists.
-   **Database Error**: Double-check the `db_config.php` credentials (User, DB Name, Password).
-   **Images not loading**: Check that the `public/images/uploads` path exists.
