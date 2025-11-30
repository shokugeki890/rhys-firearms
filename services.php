<?php
require_once 'database.php';
require_once 'auth.php';
//this is services.php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Services - Rhys Firearms | Professional Firearm Services</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="firearm services, gunsmithing, training, repairs, customization" name="keywords">
    <meta content="Professional firearm services including gunsmithing, training, repairs, and customizations from Rhys Firearms." name="description">

    <!-- Favicon -->
    <link href="css/favicon.ico" rel="icon">

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
            --light-bg: #f5f5f5;
            --border: #444;
            --bright-text: #b8b8b8; /* New brighter text color */
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
        
        /* New brighter text class */
        .text-bright {
            color: var(--bright-text) !important;
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
                        url('img/services/services-hero.jpg') no-repeat center center;
            background-size: cover;
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
        
        .service-card {
            background-color: var(--secondary);
            border: 1px solid var(--border);
            transition: all 0.3s;
            height: 100%;
            text-align: center;
            padding: 2.5rem 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border-color: var(--accent);
        }
        
        .service-icon {
            font-size: 3rem;
            color: var(--accent);
            margin-bottom: 1.5rem;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .service-card:hover .service-icon {
            transform: scale(1.1);
        }
        
        .service-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent);
            color: white;
            padding: 5px 10px;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 0;
        }
        
        .feature-box {
            background-color: var(--secondary);
            padding: 2rem;
            border-left: 3px solid var(--accent);
            height: 100%;
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
        
        .user-welcome {
            background: var(--secondary);
            padding: 1rem;
            border-left: 3px solid var(--accent);
            margin-bottom: 2rem;
        }
        
        .process-step {
            text-align: center;
            padding: 2rem 1rem;
            position: relative;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1.5rem;
        }
        
        .process-connector {
            position: absolute;
            top: 30px;
            right: -30px;
            width: 60px;
            height: 2px;
            background: var(--accent);
            z-index: 1;
        }
        
        @media (max-width: 768px) {
            .process-connector {
                display: none;
            }
        }
        
        .pricing-card {
            background: var(--secondary);
            border: 1px solid var(--border);
            transition: all 0.3s;
            text-align: center;
            padding: 2.5rem 2rem;
        }
        
        .pricing-card.featured {
            border-color: var(--accent);
            transform: scale(1.05);
            position: relative;
            overflow: hidden;
        }
        
        .pricing-card.featured:before {
            content: 'POPULAR';
            position: absolute;
            top: 20px;
            right: -30px;
            background: var(--accent);
            color: white;
            padding: 5px 30px;
            transform: rotate(45deg);
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .price {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent);
            margin: 1.5rem 0;
        }
        
        .price span {
            font-size: 1rem;
            color: var(--bright-text);
        }
        
        .service-feature-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }
        
        .service-feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
            color: var(--bright-text);
        }
        
        .service-feature-list li:last-child {
            border-bottom: none;
        }
        
        .service-feature-list li i {
            color: var(--accent);
            margin-right: 10px;
        }
        
        /* Override Bootstrap's text-muted with our brighter color */
        .text-muted {
            color: var(--bright-text) !important;
        }
        
        /* Make sure all paragraph text in service cards is brighter */
        .service-card p {
            color: var(--bright-text);
        }
        
        /* Footer text adjustments */
        .footer p, .footer a {
            color: var(--bright-text);
        }
        
        .footer a:hover {
            color: var(--accent);
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
                            <span>info@rhysfirearms.com</span>
                        </div>
                        <div>
                            <i class="bi bi-phone text-accent me-2"></i>
                            <span>+1 (555) 123-4567</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end">
                        <?php if (isLoggedIn()): ?>
                            <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
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
                <h1 class="m-0 text-uppercase">RHYS FIREARMS</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
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
                    <a href="services.php" class="nav-item nav-link active">Services</a>
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
                    <h4 class="text-white mb-2">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
                    <p class="text-bright mb-0">
                        <?php if (isAdmin()): ?>
                            You have administrator privileges. <a href="admin.php?page=services" class="text-accent">Manage services</a>
                        <?php else: ?>
                            Explore our professional firearm services and training programs.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="contact.php" class="btn btn-accent">Schedule Service</a>
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
                    <h1 class="display-4 text-uppercase text-white">Our Services</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Services</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Services Section Start -->
    <div class="container-fluid py-5 bg-secondary-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-white">Professional Firearm Services</h2>
                <p class="text-bright">Expert services to maintain, customize, and enhance your firearms</p>
            </div>
            
            <div class="row g-5 mb-5">
                <!-- Service 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-badge">POPULAR</div>
                        <div class="service-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h4 class="text-white mb-3">Gunsmithing & Repairs</h4>
                        <p>Professional gunsmithing services including repairs, maintenance, and custom modifications to keep your firearms in optimal condition.</p>
                        <ul class="service-feature-list text-start">
                            <li><i class="bi bi-check-circle"></i> Complete firearm diagnostics</li>
                            <li><i class="bi bi-check-circle"></i> Parts replacement & repair</li>
                            <li><i class="bi bi-check-circle"></i> Trigger job & action tuning</li>
                            <li><i class="bi bi-check-circle"></i> Barrel threading & crowning</li>
                        </ul>
                        <a href="contact.php" class="btn btn-accent mt-3">Schedule Service</a>
                    </div>
                </div>
                
                <!-- Service 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h4 class="text-white mb-3">Firearm Training</h4>
                        <p>Comprehensive training programs for all skill levels, from beginners to advanced shooters, taught by certified instructors.</p>
                        <ul class="service-feature-list text-start">
                            <li><i class="bi bi-check-circle"></i> Basic firearm safety courses</li>
                            <li><i class="bi bi-check-circle"></i> Concealed carry certification</li>
                            <li><i class="bi bi-check-circle"></i> Advanced tactical training</li>
                            <li><i class="bi bi-check-circle"></i> Competitive shooting coaching</li>
                        </ul>
                        <a href="contact.php" class="btn btn-accent mt-3">View Classes</a>
                    </div>
                </div>
                
                <!-- Service 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-palette"></i>
                        </div>
                        <h4 class="text-white mb-3">Customization & Finishes</h4>
                        <p>Enhance your firearm's appearance and performance with our premium customization services and durable finishes.</p>
                        <ul class="service-feature-list text-start">
                            <li><i class="bi bi-check-circle"></i> Cerakote & Duracoat finishes</li>
                            <li><i class="bi bi-check-circle"></i> Custom engraving & laser work</li>
                            <li><i class="bi bi-check-circle"></i> Stock fitting & bedding</li>
                            <li><i class="bi bi-check-circle"></i> Optics mounting & zeroing</li>
                        </ul>
                        <a href="contact.php" class="btn btn-accent mt-3">Get Quote</a>
                    </div>
                </div>
                
                <!-- Service 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="text-white mb-3">Safety Inspections</h4>
                        <p>Thorough safety inspections to ensure your firearms are functioning properly and meet all safety standards.</p>
                        <ul class="service-feature-list text-start">
                            <li><i class="bi bi-check-circle"></i> Comprehensive function testing</li>
                            <li><i class="bi bi-check-circle"></i> Wear & tear assessment</li>
                            <li><i class="bi bi-check-circle"></i> Safety mechanism verification</li>
                            <li><i class="bi bi-check-circle"></i> Detailed inspection report</li>
                        </ul>
                        <a href="contact.php" class="btn btn-accent mt-3">Book Inspection</a>
                    </div>
                </div>
                
                <!-- Service 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h4 class="text-white mb-3">Firearm Restoration</h4>
                        <p>Bring old or damaged firearms back to their former glory with our expert restoration services.</p>
                        <ul class="service-feature-list text-start">
                            <li><i class="bi bi-check-circle"></i> Antique firearm restoration</li>
                            <li><i class="bi bi-check-circle"></i> Rust & corrosion treatment</li>
                            <li><i class="bi bi-check-circle"></i> Wood stock refinishing</li>
                            <li><i class="bi bi-check-circle"></i> Metal re-bluing & polishing</li>
                        </ul>
                        <a href="contact.php" class="btn btn-accent mt-3">Start Restoration</a>
                    </div>
                </div>
                
                <!-- Service 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-badge">NEW</div>
                        <div class="service-icon">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <h4 class="text-white mb-3">Custom Builds</h4>
                        <p>Work with our master gunsmiths to create a completely custom firearm tailored to your specific needs and preferences.</p>
                        <ul class="service-feature-list text-start">
                            <li><i class="bi bi-check-circle"></i> Precision rifle builds</li>
                            <li><i class="bi bi-check-circle"></i> Custom competition pistols</li>
                            <li><i class="bi bi-check-circle"></i> Tactical builds & upgrades</li>
                            <li><i class="bi bi-check-circle"></i> Complete project management</li>
                        </ul>
                        <a href="contact.php" class="btn btn-accent mt-3">Start Project</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services Section End -->

    <!-- Service Process Start -->
    <div class="container-fluid py-5 bg-dark-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-white">Our Service Process</h2>
                <p class="text-bright">Simple and transparent process for all our firearm services</p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h4 class="text-white mb-3">Consultation</h4>
                        <p class="text-bright">Discuss your needs with our experts and get a detailed service recommendation.</p>
                        <div class="process-connector d-none d-lg-block"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h4 class="text-white mb-3">Quote & Approval</h4>
                        <p class="text-bright">Receive a transparent quote and approve the work before we begin.</p>
                        <div class="process-connector d-none d-lg-block"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h4 class="text-white mb-3">Expert Service</h4>
                        <p class="text-bright">Our certified gunsmiths perform the work with precision and care.</p>
                        <div class="process-connector d-none d-lg-block"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <h4 class="text-white mb-3">Quality Check</h4>
                        <p class="text-bright">Rigorous testing and inspection to ensure complete satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service Process End -->

    <!-- Training Pricing Start -->
    <div class="container-fluid py-5 bg-secondary-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-white">Training Programs</h2>
                <p class="text-bright">Comprehensive firearm training for all experience levels</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="pricing-card">
                        <h4 class="text-white">Basic Safety</h4>
                        <p class="text-bright">Ideal for first-time firearm owners</p>
                        <div class="price">$99<span>/session</span></div>
                        <ul class="service-feature-list">
                            <li><i class="bi bi-check-circle"></i> Firearm safety fundamentals</li>
                            <li><i class="bi bi-check-circle"></i> Basic handling & operation</li>
                            <li><i class="bi bi-check-circle"></i> Cleaning & maintenance basics</li>
                            <li><i class="bi bi-check-circle"></i> Range time with instructor</li>
                            <li><i class="bi bi-x-circle"></i> Advanced techniques</li>
                        </ul>
                        <a href="contact.php" class="btn btn-outline-accent w-100">Enroll Now</a>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="pricing-card featured">
                        <h4 class="text-white">Concealed Carry</h4>
                        <p class="text-bright">Complete CCW certification course</p>
                        <div class="price">$199<span>/course</span></div>
                        <ul class="service-feature-list">
                            <li><i class="bi bi-check-circle"></i> Legal requirements & responsibilities</li>
                            <li><i class="bi bi-check-circle"></i> Defensive shooting techniques</li>
                            <li><i class="bi bi-check-circle"></i> Situational awareness training</li>
                            <li><i class="bi bi-check-circle"></i> State certification exam</li>
                            <li><i class="bi bi-check-circle"></i> Range qualification</li>
                        </ul>
                        <a href="contact.php" class="btn btn-accent w-100">Enroll Now</a>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="pricing-card">
                        <h4 class="text-white">Advanced Tactical</h4>
                        <p class="text-bright">For experienced shooters</p>
                        <div class="price">$349<span>/course</span></div>
                        <ul class="service-feature-list">
                            <li><i class="bi bi-check-circle"></i> Advanced marksmanship</li>
                            <li><i class="bi bi-check-circle"></i> Movement & cover techniques</li>
                            <li><i class="bi bi-check-circle"></i> Multiple threat engagement</li>
                            <li><i class="bi bi-check-circle"></i> Low-light shooting</li>
                            <li><i class="bi bi-check-circle"></i> Force-on-force scenarios</li>
                        </ul>
                        <a href="contact.php" class="btn btn-outline-accent w-100">Enroll Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Training Pricing End -->

    <!-- CTA Section Start -->
    <div class="container-fluid py-5 bg-dark-custom">
        <div class="container text-center">
            <h2 class="section-title text-uppercase text-center text-white mb-4">Ready to Get Started?</h2>
            <p class="text-bright mb-4">Contact us today to schedule a service or training session</p>
            <a href="contact.php" class="btn btn-accent me-3">Contact Us</a>
            <a href="products.php" class="btn btn-outline-accent">Browse Products</a>
        </div>
    </div>
    <!-- CTA Section End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <h4 class="text-uppercase text-white mb-4">RHYS FIREARMS</h4>
                    <p>Crafting precision firearms with uncompromising quality since 1987.</p>
                    <div class="d-flex pt-2">
                        <a class="social-icon" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="social-icon" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="social-icon" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="social-icon" href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-uppercase text-white mb-4">Quick Links</h5>
                    <div class="d-flex flex-column">
                        <a class="mb-2" href="index.php"><i class="bi bi-arrow-right text-accent me-2"></i>Home</a>
                        <a class="mb-2" href="about.php"><i class="bi bi-arrow-right text-accent me-2"></i>About Us</a>
                        <a class="mb-2" href="products.php"><i class="bi bi-arrow-right text-accent me-2"></i>Products</a>
                        <a class="mb-2" href="services.php"><i class="bi bi-arrow-right text-accent me-2"></i>Services</a>
                        <a href="contact.php"><i class="bi bi-arrow-right text-accent me-2"></i>Contact</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-uppercase text-white mb-4">Contact Info</h5>
                    <p class="mb-2"><i class="bi bi-geo-alt text-accent me-2"></i>123 Safety Lane, Gunville, TX 75001</p>
                    <p class="mb-2"><i class="bi bi-envelope text-accent me-2"></i>info@rhysfirearms.com</p>
                    <p class="mb-2"><i class="bi bi-phone text-accent me-2"></i>+1 (555) 123-4567</p>
                    <p><i class="bi bi-clock text-accent me-2"></i>Mon-Fri: 9AM-6PM, Sat: 10AM-4PM</p>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <a class="text-white border-bottom" href="index.php">Rhys Firearms</a>. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>