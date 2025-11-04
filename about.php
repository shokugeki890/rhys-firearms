<?php
require_once 'database.php';
require_once 'auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>About Us - Rhys Firearms</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Firearms, About Us, Guns, History" name="keywords">
    <meta content="Learn more about Rhys Firearms, our heritage, and our commitment to quality firearms." name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1a1a1a;
            --secondary: #333;
            --accent: #c8102e;
            --text: #e8e8e8;
            --text-bright: #f0f0f0;
            --text-muted: #cccccc;
            --text-light: #b8b8b8;
            --light-bg: #f5f5f5;
            --border: #555;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background-color: #0a0a0a;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;
            font-weight: 600;
        }
        
        .bg-dark-custom {
            background-color: var(--primary) !important;
        }
        
        .bg-secondary-custom {
            background-color: var(--secondary) !important;
        }
        
        .text-accent {
            color: var(--accent) !important;
        }
        
        /* Updated text colors for better readability */
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        .text-light {
            color: var(--text-light) !important;
        }
        
        .text-bright {
            color: var(--text-bright) !important;
        }
        
        .btn-accent {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.3s;
        }
        
        .btn-accent:hover {
            background-color: #a00d25;
            color: white;
        }
        
        .btn-outline-accent {
            border: 1px solid var(--accent);
            color: var(--accent);
            background: transparent;
            padding: 10px 25px;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.3s;
        }
        
        .btn-outline-accent:hover {
            background-color: var(--accent);
            color: white;
        }
        
        .border-accent {
            border-color: var(--accent) !important;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            letter-spacing: 1px;
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--accent) !important;
        }
        
        .dropdown-menu {
            background-color: var(--secondary);
            border: 1px solid var(--border);
            border-radius: 0;
        }
        
        .dropdown-item {
            color: var(--text);
            padding: 0.5rem 1rem;
        }
        
        .dropdown-item:hover {
            background-color: var(--accent);
            color: white;
        }
        
        .hero-section {
            background: linear-gradient(rgba(10, 10, 10, 0.85), rgba(10, 10, 10, 0.85)), 
                        url('img/hero/about-hero.jpg') no-repeat center center;
            background-size: cover;
            background-position: center;
            padding: 120px 0 80px;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--accent);
        }
        
        .section-title.text-center:after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .feature-box {
            background-color: var(--secondary);
            padding: 2rem;
            border-left: 3px solid var(--accent);
            height: 100%;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent);
            margin-bottom: 1rem;
        }
        
        .footer {
            background-color: #0a0a0a;
            border-top: 1px solid var(--border);
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--secondary);
            color: var(--text);
            border-radius: 0;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            background-color: var(--accent);
            color: white;
        }
        
        .topbar {
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
        }
        
        .breadcrumb-item a {
            color: var(--accent);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: var(--text-muted);
        }
        
        .timeline {
            position: relative;
            padding: 0;
            list-style: none;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--accent);
            left: 50%;
            margin-left: -1px;
        }
        
        .timeline-item {
            margin-bottom: 50px;
            position: relative;
        }
        
        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 0;
            margin-right: 50%;
            padding-right: 30px;
            text-align: right;
        }
        
        .timeline-item:nth-child(even) .timeline-content {
            margin-left: 50%;
            padding-left: 30px;
        }
        
        .timeline-marker {
            position: absolute;
            top: 0;
            left: 50%;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent);
            margin-left: -6px;
        }
        
        .timeline-content {
            position: relative;
        }
        
        .team-member {
            background-color: var(--secondary);
            padding: 2rem;
            transition: all 0.3s;
            border: 1px solid var(--border);
            text-align: center;
            height: 100%;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Fixed team image container - now with proper aspect ratio */
        .team-img {
            height: 250px;
            background-color: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            overflow: hidden;
            border-radius: 4px;
            position: relative;
        }
        
        /* Improved image styling to prevent cropping */
        .team-img img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Changed from cover to contain to show full image */
            object-position: center;
            transition: transform 0.3s ease;
        }
        
        .team-member:hover .team-img img {
            transform: scale(1.05);
        }
        
        /* User welcome section for logged in users */
        .user-welcome {
            background: var(--secondary);
            padding: 1rem;
            border-left: 3px solid var(--accent);
            margin-bottom: 2rem;
        }
        
        /* Fallback for missing images */
        .no-image {
            background: linear-gradient(45deg, #333, #555);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-style: italic;
            width: 100%;
            height: 100%;
        }
        
        /* Responsive adjustments for team images */
        @media (max-width: 768px) {
            .team-img {
                height: 200px;
            }
            
            .timeline-item:nth-child(odd) .timeline-content,
            .timeline-item:nth-child(even) .timeline-content {
                margin-left: 0;
                margin-right: 0;
                padding-left: 40px;
                padding-right: 0;
                text-align: left;
            }
            
            .timeline:before {
                left: 20px;
            }
            
            .timeline-marker {
                left: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid topbar py-2 d-none d-lg-block bg-dark-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="me-4">
                            <i class="bi bi-envelope text-accent me-2"></i>
                            <span class="text-light">info@rhysfirearms.com</span>
                        </div>
                        <div>
                            <i class="bi bi-phone text-accent me-2"></i>
                            <span class="text-light">+1 (555) 123-4567</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end">
                        <?php if (isLoggedIn()): ?>
                            <span class="me-3 text-light">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                            <a href="logout.php" class="btn btn-sm btn-accent">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-sm btn-accent me-2">Login</a>
                            <a href="register.php" class="btn btn-sm btn-outline-light">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom shadow-sm py-3">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <h1 class="m-0 text-uppercase text-bright">RHYS FIREARMS</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link active">About</a>
                    <a href="products.php" class="nav-item nav-link">Products</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="cart.php" class="nav-item nav-link">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php
                            // Get cart count
                            $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM transaction WHERE user_id = ? AND status = 'cart'");
                            $stmt->execute([$_SESSION['user_id']]);
                            $cart_count = $stmt->fetchColumn() ?: 0;
                            if ($cart_count > 0):
                            ?>
                                <span class="badge bg-accent"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <a href="services.php" class="nav-item nav-link">Services</a>
                    <?php if (isAdmin()): ?>
                        <a href="admin.php" class="nav-item nav-link text-accent">Admin Panel</a>
                    <?php endif; ?>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- User Welcome Section -->
    <?php if (isLoggedIn()): ?>
    <div class="container mt-4">
        <div class="user-welcome">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="text-bright mb-2">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
                    <p class="mb-0">
                        <?php if (isAdmin()): ?>
                            You have administrator privileges. <a href="admin.php" class="text-accent">Access admin panel</a>
                        <?php else: ?>
                            Learn more about our company's history and values.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="products.php" class="btn btn-accent">Browse Products</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Page Header Start -->
    <div class="container-fluid hero-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 text-uppercase text-bright">About Rhys Firearms</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">About</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Our Story Start -->
    <div class="container-fluid py-5 bg-secondary-custom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="section-title text-uppercase text-bright mb-4">Our Heritage</h2>
                    <p class="mb-4">Founded in 1987 by military veteran John Rhys, Rhys Firearms began as a small custom gunsmithing shop with a simple mission: to build the most reliable, accurate firearms for hunting and personal defense.</p>
                    <p class="mb-4">Over three decades later, we've grown into a respected manufacturer known for precision engineering and uncompromising quality. Our firearms are trusted by hunters, competitive shooters, and law enforcement professionals across the country.</p>
                    <p>Every Rhys firearm is built with the same attention to detail and commitment to excellence that John Rhys instilled in his original workshop.</p>
                </div>
                <div class="col-lg-6">
                    <div class="feature-box">
                        <h3 class="text-bright mb-4">Our Mission</h3>
                        <p class="mb-4">To engineer and manufacture premium firearms that exceed customer expectations for performance, reliability, and safety.</p>
                        <h3 class="text-bright mb-4">Our Vision</h3>
                        <p>To be the most trusted name in firearms by combining traditional craftsmanship with cutting-edge technology.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Story End -->

    <!-- Timeline Start -->
    <div class="container-fluid py-5 bg-dark-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-bright">Our Journey</h2>
                <p>Three decades of excellence in firearms manufacturing</p>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4 class="text-bright">1987</h4>
                                <p>John Rhys opens a small custom gunsmithing shop in Texas, focusing on precision rifle work.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4 class="text-bright">1995</h4>
                                <p>Rhys Firearms introduces its first production model, the R-700 bolt-action rifle, which quickly gains popularity among hunters.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4 class="text-bright">2003</h4>
                                <p>Expansion of manufacturing facilities allows for increased production and the introduction of the RP series of handguns.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4 class="text-bright">2012</h4>
                                <p>Rhys Firearms receives its first major military contract, supplying specialized rifles to special forces units.</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4 class="text-bright">2020</h4>
                                <p>Introduction of the R-15 tactical rifle series, incorporating advanced materials and manufacturing techniques.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Timeline End -->

    <!-- Values Start -->
    <div class="container-fluid py-5 bg-secondary-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-bright">Our Values</h2>
                <p>The principles that guide everything we do</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="text-bright mb-3">Safety First</h4>
                        <p class="mb-0">Every firearm undergoes rigorous testing to ensure it meets the highest safety standards before leaving our facility.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <h4 class="text-bright mb-3">Quality Craftsmanship</h4>
                        <p class="mb-0">We combine traditional gunsmithing techniques with modern precision engineering for exceptional performance.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h4 class="text-bright mb-3">Customer Commitment</h4>
                        <p class="mb-0">We stand behind our products with comprehensive warranties and exceptional customer service.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Values End -->

    <!-- Team Start -->
    <div class="container-fluid py-5 bg-dark-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-bright">Leadership Team</h2>
                <p>The experts behind Rhys Firearms</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="team-member">
                        <div class="team-img">
                            <img src="img/team/john-rhys.jpg" alt="John Rhys - Founder & CEO" 
                                 onerror="this.onerror=null; this.classList.add('no-image'); this.innerHTML='John Rhys Image';">
                        </div>
                        <h4 class="text-bright">John Rhys</h4>
                        <p class="text-accent mb-2">Founder & CEO</p>
                        <p>Former military armorer with over 40 years of firearms experience.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-member">
                        <div class="team-img">
                            <img src="img/team/sarah-chen.jpg" alt="Sarah Chen - Chief Operations Officer" 
                                 onerror="this.onerror=null; this.classList.add('no-image'); this.innerHTML='Sarah Chen Image';">
                        </div>
                        <h4 class="text-bright">Sarah Chen</h4>
                        <p class="text-accent mb-2">Chief Operations Officer</p>
                        <p>Manufacturing expert with 15 years in precision engineering.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-member">
                        <div class="team-img">
                            <img src="img/team/michael-rodriguez.jpg" alt="Michael Rodriguez - Head of R&D" 
                                 onerror="this.onerror=null; this.classList.add('no-image'); this.innerHTML='Michael Rodriguez Image';">
                        </div>
                        <h4 class="text-bright">Michael Rodriguez</h4>
                        <p class="text-accent mb-2">Head of R&D</p>
                        <p>Materials scientist specializing in advanced firearm technologies.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <h4 class="text-uppercase text-bright mb-4">RHYS FIREARMS</h4>
                    <p>Crafting precision firearms with uncompromising quality since 1987.</p>
                    <div class="d-flex pt-2">
                        <a class="social-icon" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="social-icon" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="social-icon" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="social-icon" href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-uppercase text-bright mb-4">Quick Links</h5>
                    <div class="d-flex flex-column">
                        <a class="mb-2" href="index.php"><i class="bi bi-arrow-right text-accent me-2"></i>Home</a>
                        <a class="mb-2" href="about.php"><i class="bi bi-arrow-right text-accent me-2"></i>About Us</a>
                        <a class="mb-2" href="products.php"><i class="bi bi-arrow-right text-accent me-2"></i>Products</a>
                        <a class="mb-2" href="services.php"><i class="bi bi-arrow-right text-accent me-2"></i>Services</a>
                        <a href="contact.php"><i class="bi bi-arrow-right text-accent me-2"></i>Contact</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-uppercase text-bright mb-4">Contact Info</h5>
                    <p class="mb-2"><i class="bi bi-geo-alt text-accent me-2"></i>123 Safety Lane, Gunville, TX 75001</p>
                    <p class="mb-2"><i class="bi bi-envelope text-accent me-2"></i>info@rhysfirearms.com</p>
                    <p class="mb-2"><i class="bi bi-phone text-accent me-2"></i>+1 (555) 123-4567</p>
                    <p><i class="bi bi-clock text-accent me-2"></i>Mon-Fri: 9AM-6PM, Sat: 10AM-4PM</p>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <a class="text-bright border-bottom" href="index.php">Rhys Firearms</a>. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>