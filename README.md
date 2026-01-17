# IlyassFit

## Project Title
**IlyassFit** - Professional Personal Training & Coaching Platform

## Project Description
IlyassFit is a dynamic and responsive web application designed for a personal fitness coach. The platform serves as a hub for potential clients to explore services (1-on-1 training, online coaching, nutrition plans), view client transformations, browse a gallery of training sessions, and get in touch with the trainer. It allows the admin to manage content like pricing packages, gallery images, client reviews, and messages through a secure backend dashboard.

## Features
### Public Frontend
- **Hero Section**: Engaging video background with clear Call-to-Actions.
- **Services Showcase**: Interactive slider detailing Nutrition Plans, 1-on-1 Training, and Online Coaching.
- **About Section**: Trainer bio with a client testimonial carousel.
- **Transformations**: Before & After photo comparison gallery.
- **Gallery**: Expandable image gallery displaying training and community photos.
- **Pricing**: Detailed pricing cards for different coaching tiers.
- **Contact Form**: Direct messaging system for inquiries.

### Admin Backend
- **Dashboard**: Overview of total messages, reviews, gallery items, and unseen notifications.
- **Content Management**:
  - **Manage Pricing**: Update plan details and prices.
  - **Manage Gallery**: Upload and remove images.
  - **Manage Reviews**: Add or edit client testimonials.
  - **Manage Messages**: View and manage contact form submissions.
- **Security**: Secure login system with hashed passwords and session management.

## Technologies Used
- **Backend**: PHP (Native/Procedural), PDO (PHP Data Objects) for database interactions.
- **Database**: MySQL / MariaDB.
- **Frontend**: HTML5, CSS3 (Custom responsive design), Vanilla JavaScript.
- **Server**: Apache (utilizing `.htaccess` for routing and security).
- **Tools**: FontAwesome (Icons), Google Fonts.

## Project Structure
```
IlyassFit/
├── admin/                  # Backend admin panel files
│   ├── css/                # Admin-specific styles
│   ├── includes/           # Admin shared components (navbar, etc.)
│   ├── dashboard.php       # Main admin dashboard
│   ├── manage_*.php        # CMS pages for various features
│   └── ...
├── includes/               # Shared logic
│   ├── config/             # Database configuration
│   └── functions/          # Helper functions (auth, crud, security)
├── public/                 # Public facing frontend
│   ├── assets/             # Static assets (css, js, images, video)
│   ├── components/         # Reusable UI parts (header, footer, hero)
│   ├── *.php               # Main pages (index, pricing, contact)
│   └── ...
├── .htaccess               # Apache configuration (Routing, Security, Caching)
└── README.md               # Project documentation
```

## Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/medghazouan/IlyassFit.git
    cd IlyassFit
    ```

2.  **Set Up the Web Server**
    - Ensure you have a PHP environment installed (e.g., XAMPP, WAMP, or centralized LAMP/LEMP stack).
    - Point your web server's document root to the project folder.

3.  **Database Setup**
    - Create a new MySQL database named `ilyassfitdb` (or your preferred name).
    - Import the provided database schema (if available) or create the necessary tables (`users`, `gallery`, `reviews`, `messages`, `pricing`, `services`) manually.

4.  **Configuration**
    - Navigate to `includes/config/db_config.php`.
    - Update the database credentials:
    ```php
    // Localhost
    $host = 'localhost';
    $db = 'ilyassfitdb';
    $user = 'root';
    $pass = '';
    ```

5.  **Run the Application**
    - Open your browser and navigate to `http://localhost/IlyassFit`.

## Usage Instructions
- **Visitors**: Can browse services, view the gallery, check pricing, and contact the trainer via the `public/` interface.
- **Admin**:
    - Access the admin panel at `http://localhost/IlyassFit/admin`.
    - Log in with your admin credentials.
    - Use the dashboard to view stats and manage website content.

## Authentication & Roles
- **Admin Role**: Full access to the `admin/` directory. Protected by session-based authentication (`auth.php`).
- **Public**: Read-only access to frontend pages.

## Future Improvements
- **Payment Integration**: Stripe/PayPal integration for direct service purchase.
- **User Accounts**: Client login area for tracking progress and accessing plans.
- **Blog Section**: Fitness tips and articles for SEO.

## Author & License
**Author**: Bidayalab Team
**License**: MIT License
