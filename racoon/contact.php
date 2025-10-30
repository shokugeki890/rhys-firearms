<?php
require_once 'database.php';
require_once 'auth.php';

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    
    // Basic validation
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // In a real application, you would:
        // 1. Save to database
        // 2. Send email notification
        // 3. Process the contact request
        
        $success_message = "Thank you, $name! Your message has been sent. We'll get back to you within 24 hours.";
        
        // Clear form fields
        $_POST = array();
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Contact Us - Rhys Firearms | Get In Touch</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="contact Rhys Firearms, firearm inquiries, customer service" name="keywords">
    <meta content="Contact Rhys Firearms for inquiries about our products, services, training, or any other questions." name="description">

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
                        url('https://images.unsplash.com/photo-1588347818122-cf3f5b79e49a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center;
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
        
        .contact-card {
            background-color: var(--secondary);
            border: 1px solid var(--border);
            transition: all 0.3s;
            height: 100%;
            text-align: center;
            padding: 2.5rem 1.5rem;
            position: relative;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border-color: var(--accent);
        }
        
        .contact-icon {
            font-size: 3rem;
            color: var(--accent);
            margin-bottom: 1.5rem;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .contact-card:hover .contact-icon {
            transform: scale(1.1);
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
        
        .form-control {
            background-color: var(--secondary);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 0;
            padding: 12px 15px;
        }
        
        .form-control:focus {
            background-color: var(--secondary);
            border-color: var(--accent);
            color: var(--text);
            box-shadow: none;
        }
        
        .form-label {
            color: var(--text-bright);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .map-container {
            height: 400px;
            background: var(--secondary);
            border: 1px solid var(--border);
        }
        
        .business-hours {
            background: var(--secondary);
            padding: 2rem;
            border-left: 3px solid var(--accent);
        }
        
        .hours-list {
            list-style: none;
            padding: 0;
        }
        
        .hours-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
        }
        
        .hours-list li:last-child {
            border-bottom: none;
        }
        
        .hours-list .day {
            font-weight: 500;
            color: var(--text-bright);
        }
        
        .hours-list .time {
            color: var(--accent);
        }
        
        .required-field::after {
            content: " *";
            color: var(--accent);
        }
        
        /* Additional brighter text styles */
        .contact-card p {
            color: var(--text-muted);
        }
        
        .feature-box p {
            color: var(--text-muted);
        }
        
        .accordion-body {
            color: var(--text-muted);
        }
        
        .breadcrumb-item a {
            color: var(--text-light);
        }
        
        .breadcrumb-item.active {
            color: var(--text-muted);
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border-color: #28a745;
            color: #b8f7c6;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #f8b4ba;
        }
        
        .user-welcome p {
            color: var(--text-muted);
        }
        
        .topbar span {
            color: var(--text-light);
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
                    <a href="services.php" class="nav-item nav-link">Services</a>
                    <?php if (isAdmin()): ?>
                        <a href="admin.php" class="nav-item nav-link text-accent">Admin Panel</a>
                    <?php endif; ?>
                    <a href="contact.php" class="nav-item nav-link active">Contact</a>
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
                            You have administrator privileges. <a href="admin.php?page=contacts" class="text-accent">View contact submissions</a>
                        <?php else: ?>
                            Need assistance? We're here to help with any questions about our products or services.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="services.php" class="btn btn-accent">Schedule Service</a>
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
                    <h1 class="display-4 text-uppercase text-bright">Contact Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Contact Section Start -->
    <div class="container-fluid py-5 bg-secondary-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-bright">Get In Touch</h2>
                <p>Have questions about our products or services? We're here to help.</p>
            </div>
            
            <div class="row g-5 mb-5">
                <!-- Contact Info Cards -->
                <div class="col-lg-4 col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4 class="text-bright mb-3">Our Location</h4>
                        <p>123 Safety Lane<br>Gunville, TX 75001</p>
                        <a href="https://maps.google.com" target="_blank" class="btn btn-outline-accent mt-3">Get Directions</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h4 class="text-bright mb-3">Email Us</h4>
                        <p>General Inquiries:<br>info@rhysfirearms.com</p>
                        <p>Service Department:<br>service@rhysfirearms.com</p>
                        <a href="mailto:info@rhysfirearms.com" class="btn btn-outline-accent mt-3">Send Email</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4 class="text-bright mb-3">Call Us</h4>
                        <p>Main Office:<br>+1 (555) 123-4567</p>
                        <p>Service Department:<br>+1 (555) 123-4568</p>
                        <a href="tel:+15551234567" class="btn btn-outline-accent mt-3">Call Now</a>
                    </div>
                </div>
            </div>
            
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="feature-box">
                        <h3 class="text-bright mb-4">Send Us a Message</h3>
                        
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label required-field">Your Name</label>
                                    <input type="text" name="name" class="form-control" id="name" 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label required-field">Your Email</label>
                                    <input type="email" name="email" class="form-control" id="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           required>
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label required-field">Subject</label>
                                    <select name="subject" class="form-control" id="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="Product Inquiry" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Product Inquiry') ? 'selected' : ''; ?>>Product Inquiry</option>
                                        <option value="Service Request" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Service Request') ? 'selected' : ''; ?>>Service Request</option>
                                        <option value="Training Information" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Training Information') ? 'selected' : ''; ?>>Training Information</option>
                                        <option value="Warranty Claim" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Warranty Claim') ? 'selected' : ''; ?>>Warranty Claim</option>
                                        <option value="General Question" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'General Question') ? 'selected' : ''; ?>>General Question</option>
                                        <option value="Other" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label required-field">Your Message</label>
                                    <textarea name="message" class="form-control" id="message" rows="5" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-accent w-100" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Additional Info -->
                <div class="col-lg-4">
                    <div class="business-hours mb-4">
                        <h4 class="text-bright mb-4">Business Hours</h4>
                        <ul class="hours-list">
                            <li>
                                <span class="day">Monday - Friday</span>
                                <span class="time">9:00 AM - 6:00 PM</span>
                            </li>
                            <li>
                                <span class="day">Saturday</span>
                                <span class="time">10:00 AM - 4:00 PM</span>
                            </li>
                            <li>
                                <span class="day">Sunday</span>
                                <span class="time">Closed</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- FAQ Section - Full Width -->
            <div class="row g-5">
                <div class="col-12">
                    <div class="feature-box">
                        <h3 class="text-bright mb-4 text-center">Frequently Asked Questions</h3>
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="accordion" id="faqAccordion">
                                    <div class="accordion-item bg-transparent border-secondary">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button bg-secondary text-bright" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                                Do you offer firearm transfers?
                                            </button>
                                        </h2>
                                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Yes, we handle FFL transfers for online purchases. Please contact us for our transfer fee schedule and procedures.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item bg-transparent border-secondary">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-secondary text-bright" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                                How long do repairs typically take?
                                            </button>
                                        </h2>
                                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Most repairs are completed within 2-3 weeks, depending on parts availability and the complexity of the work.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item bg-transparent border-secondary">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-secondary text-bright" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                                Do you offer training for beginners?
                                            </button>
                                        </h2>
                                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Absolutely! We offer beginner-friendly courses that cover firearm safety, basic operation, and fundamental shooting skills.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Section End -->

    <!-- Map Section Start -->
    <div class="container-fluid py-5 bg-dark-custom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase text-center text-bright">Visit Our Location</h2>
                <p>Stop by our showroom to see our products in person</p>
            </div>
            
            <div class="map-container">
                <!-- In a real implementation, you would embed a Google Map here -->
                <div class="d-flex align-items-center justify-content-center h-100 text-center">
                    <div>
                        <i class="bi bi-map text-accent" style="font-size: 3rem;"></i>
                        <h4 class="text-bright mt-3">Interactive Map</h4>
                        <p>123 Safety Lane, Gunville, TX 75001</p>
                        <a href="https://maps.google.com" target="_blank" class="btn btn-accent">View on Google Maps</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Map Section End -->

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
    <script src="js/main.js"></script>
</body>
</html>