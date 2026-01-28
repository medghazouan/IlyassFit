<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Achieve your dream physique with Ilyass Fit. Professional personal training, custom nutrition plans, and online coaching in Marrakech.'; ?>">
    <meta name="keywords" content="fitness, marrakech, personal trainer, nutrition plan, online coaching, gym, bodybuilding, weight loss, ilyass fit">
    <meta name="author" content="Ilyass Fit">
    <link rel="canonical" href="<?php echo isset($canonicalUrl) ? $canonicalUrl : 'https://ilyass.fit' . $_SERVER['REQUEST_URI']; ?>">
    <meta name="google-site-verification" content="lqYK4UTatpVz1VBQ5WlvOC5PxiLDgpAYLDNC5TIeo9o" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo isset($canonicalUrl) ? $canonicalUrl : 'https://ilyass.fit' . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - Ilyass Fit' : 'Ilyass Fit - Transform Your Body'; ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Achieve your dream physique with Ilyass Fit. Professional personal training, custom nutrition plans, and online coaching in Marrakech.'; ?>">
    <meta property="og:image" content="https://ilyass.fit/assets/images/og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo isset($canonicalUrl) ? $canonicalUrl : 'https://ilyass.fit' . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - Ilyass Fit' : 'Ilyass Fit - Transform Your Body'; ?>">
    <meta property="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Achieve your dream physique with Ilyass Fit. Professional personal training, custom nutrition plans, and online coaching in Marrakech.'; ?>">
    <meta property="twitter:image" content="https://ilyass.fit/assets/images/og-image.jpg">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/static/logo.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS - FIXED LOAD ORDER (No Duplicates) -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/hero.css"> 
    <link rel="stylesheet" href="assets/css/about.css">
    <link rel="stylesheet" href="assets/css/services.css">
    <link rel="stylesheet" href="assets/css/gallery.css">
    <link rel="stylesheet" href="assets/css/transformations.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    
    <!-- Page-Specific CSS (Conditional Loading) -->
    <?php if(isset($pageTitle) && $pageTitle == "Contact"): ?>
    <link rel="stylesheet" href="assets/css/contact.css">
    <?php endif; ?>
    
    <?php if(isset($pageTitle) && $pageTitle == "Pricing Plans"): ?>
    <link rel="stylesheet" href="assets/css/pricing.css">
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Ilyass Fit' : 'Ilyass Fit - Transform Your Body'; ?></title>
</head>
<body>