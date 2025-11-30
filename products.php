<?php
require_once 'database.php';
require_once 'auth.php';
//this is products.php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Products - Rhys Firearms | Premium Firearms & Accessories</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="firearms, guns, rifles, shotguns, handguns, products" name="keywords">
    <meta content="Browse our premium collection of firearms including rifles, shotguns, and handguns." name="description">

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
        
        /* Brighter text for product descriptions */
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
                        url('img/hero/products-hero.jpg') no-repeat center center;
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
        
        .product-card {
            background-color: var(--secondary);
            border: 1px solid var(--border);
            transition: all 0.3s;
            height: 100%;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .product-img {
            height: 250px;
            background-color: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            overflow: hidden;
        }
        
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .product-card:hover .product-img img {
            transform: scale(1.05);
        }
        
        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent);
            color: white;
            padding: 5px 10px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
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
        
        .user-welcome {
            background: var(--secondary);
            padding: 1rem;
            border-left: 3px solid var(--accent);
            margin-bottom: 2rem;
        }
        
        .admin-panel {
            background: var(--secondary);
            padding: 2rem;
            border: 1px solid var(--accent);
            margin-bottom: 2rem;
        }
        
        .filter-sidebar {
            background: var(--secondary);
            padding: 1.5rem;
            border: 1px solid var(--border);
            position: sticky;
            top: 20px;
        }
        
        .filter-title {
            border-bottom: 2px solid var(--accent);
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        
        .price-range {
            color: var(--accent);
            font-weight: 600;
        }
        
        .product-detail-modal .modal-content {
            background: var(--secondary);
            color: var(--text);
            border: 1px solid var(--accent);
        }
        
        .product-detail-modal .btn-close {
            filter: invert(1);
        }
        
        .pagination .page-link {
            background-color: var(--secondary);
            border-color: var(--border);
            color: var(--text);
        }
        
        .pagination .page-link:hover {
            background-color: var(--accent);
            border-color: var(--accent);
            color: white;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--accent);
            border-color: var(--accent);
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
                    <a href="products.php" class="nav-item nav-link active">Products</a>
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
                            You have administrator privileges. <a href="admin.php?page=products" class="text-accent">Manage products</a>
                        <?php else: ?>
                            Browse our premium collection of firearms and accessories.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="cart.php" class="btn btn-accent position-relative">
                        <i class="bi bi-cart3"></i> View Cart
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </a>
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
                    <h1 class="display-4 text-uppercase text-white">Our Products</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Products Section Start -->
    <div class="container-fluid py-5 bg-secondary-custom">
        <div class="container">
            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-5">
                    <div class="filter-sidebar">
                        <h4 class="filter-title">Filters</h4>
                        
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h6 class="text-white mb-3">Category</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="filter-rifles" checked>
                                <label class="form-check-label text-bright" for="filter-rifles">
                                    Rifles
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="filter-shotguns" checked>
                                <label class="form-check-label text-bright" for="filter-shotguns">
                                    Shotguns
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="filter-handguns" checked>
                                <label class="form-check-label text-bright" for="filter-handguns">
                                    Handguns
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="filter-accessories">
                                <label class="form-check-label text-bright" for="filter-accessories">
                                    Accessories
                                </label>
                            </div>
                        </div>
                        
                        <!-- Price Filter -->
                        <div class="mb-4">
                            <h6 class="text-white mb-3">Price Range</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-bright">$0</span>
                                <span class="price-range">$500 - $2,500</span>
                                <span class="text-bright">$5,000</span>
                            </div>
                            <input type="range" class="form-range" min="0" max="5000" step="100" id="priceRange">
                        </div>
                        
                        <!-- Caliber Filter -->
                        <div class="mb-4">
                            <h6 class="text-white mb-3">Caliber</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="caliber-9mm">
                                <label class="form-check-label text-bright" for="caliber-9mm">
                                    9mm
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="caliber-45acp">
                                <label class="form-check-label text-bright" for="caliber-45acp">
                                    .45 ACP
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="caliber-223">
                                <label class="form-check-label text-bright" for="caliber-223">
                                    .223 Remington
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="caliber-308">
                                <label class="form-check-label text-bright" for="caliber-308">
                                    .308 Winchester
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="caliber-12g">
                                <label class="form-check-label text-bright" for="caliber-12g">
                                    12 Gauge
                                </label>
                            </div>
                        </div>
                        
                        <button class="btn btn-accent w-100">Apply Filters</button>
                        <button class="btn btn-outline-accent w-100 mt-2">Reset Filters</button>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-white">Firearms Collection</h3>
                        <div class="d-flex">
                            <select class="form-select me-2" style="width: auto;">
                                <option>Sort by: Featured</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Name: A to Z</option>
                                <option>Newest First</option>
                            </select>
                            <div class="btn-group">
                                <button class="btn btn-outline-accent active"><i class="bi bi-grid-3x3-gap"></i></button>
                                <button class="btn btn-outline-accent"><i class="bi bi-list"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <?php
                        // Fetch products from database
                        $stmt = $pdo->query("
                            SELECT p.*, c.name as category_name, t.name as team_name
                            FROM products p
                            LEFT JOIN category c ON p.category_id = c.id
                            LEFT JOIN teams t ON p.team_id = t.id
                            ORDER BY p.created_at DESC
                        ");
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Image mapping for products
                        $imageMap = [
                            'R-15 Tactical Rifle' => 'r15-tactical-rifle.jpg',
                            'RP9 9mm Pistol' => 'rp9-pistol.jpg',
                            'R-700 Bolt Action' => 'r700-bolt-action.jpg',
                            'RS12 Tactical Shotgun' => 'rs12-shotgun.jpg',
                            'R-10 Compact Rifle' => 'r10-compact-rifle.jpg',
                            'RP45 .45 ACP Pistol' => 'rp45-pistol.jpg',
                            'Basic Firearm Safety Course' => 'basic-firearms-training.jpg',
                            'Concealed Carry Certification' => 'concealed-carry-certification.jpg',
                            'Advanced Tactical Training' => 'advanced-tactical-training.jpg',
                            'Annual Range Membership' => 'annual-range-membership.jpg',
                            'Premium Gunsmith Service' => 'premium-gunsmith-service.jpg',
                        ];

                        foreach ($products as $index => $product):
                            $modalId = 'productModal' . ($index + 1);
                            $badge = '';
                            if ($index == 0) $badge = '<div class="product-badge">NEW</div>';
                            elseif ($index == 2) $badge = '<div class="product-badge">BEST SELLER</div>';
                            elseif ($index == 5) $badge = '<div class="product-badge">ON SALE</div>';
                            $image = isset($imageMap[$product['name']]) ? $imageMap[$product['name']] : 'default.jpg';
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product-card">
                                <?php echo $badge; ?>
                                <div class="product-img">
                                    <img src="img/products/<?php echo $image; ?>"
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         onerror="this.onerror=null; this.classList.add('no-image'); this.innerHTML='<?php echo htmlspecialchars($product['name']); ?> Image';">
                                </div>
                                <div class="p-3">
                                    <h5 class="text-white"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="text-bright small"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-accent fw-bold">$<?php echo number_format($product['price'], 2); ?></span>
                                        <div class="rating">
                                            <?php
                                            $rating = rand(3, 5);
                                            $reviews = rand(5, 35);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo '<i class="bi bi-star-fill text-warning"></i>';
                                                } elseif ($i == $rating + 0.5) {
                                                    echo '<i class="bi bi-star-half text-warning"></i>';
                                                } else {
                                                    echo '<i class="bi bi-star text-warning"></i>';
                                                }
                                            }
                                            ?>
                                            <span class="text-bright small ms-1">(<?php echo $reviews; ?>)</span>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <?php if (isLoggedIn()): ?>
                                            <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">View Details</button>
                                            <form method="POST" action="cart.php" class="d-inline">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" class="btn btn-outline-accent w-100">Add to Cart</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">View Details</button>
                                            <a href="login.php" class="btn btn-outline-accent">Login to Purchase</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Product pagination" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Products Section End -->

    <!-- Product Detail Modals -->
    <?php foreach ($products as $index => $product):
        $modalId = 'productModal' . ($index + 1);

        // Determine specs (unchanged)
        $specs = [];
        if (strpos($product['name'], 'Rifle') !== false) {
            $specs = [
                'Caliber: .223 Remington / 5.56 NATO',
                'Barrel Length: 16 inches',
                'Overall Length: 34.5 inches (collapsed)',
                'Weight: 6.8 lbs',
                'Magazine Capacity: 30 rounds'
            ];
        } elseif (strpos($product['name'], 'Pistol') !== false) {
            $specs = [
                'Caliber: ' . (strpos($product['name'], '9mm') !== false ? '9mm' : '.45 ACP'),
                'Barrel Length: ' . (strpos($product['name'], 'Compact') !== false ? '4 inches' : '5 inches'),
                'Overall Length: ' . (strpos($product['name'], 'Compact') !== false ? '7.5 inches' : '8.5 inches'),
                'Weight: ' . (strpos($product['name'], 'Compact') !== false ? '1.8 lbs' : '2.2 lbs'),
                'Magazine Capacity: ' . (strpos($product['name'], '9mm') !== false ? '17 rounds' : '10 rounds')
            ];
        } elseif (strpos($product['name'], 'Shotgun') !== false) {
            $specs = [
                'Caliber: 12 Gauge',
                'Barrel Length: 18 inches',
                'Overall Length: 39 inches',
                'Weight: 7.5 lbs',
                'Magazine Capacity: 8 rounds'
            ];
        } elseif ($product['type'] == 'course') {
            $specs = [
                'Duration: ' . (strpos($product['name'], 'Basic') !== false ? '4 hours' : '8 hours'),
                'Includes: Range time and certification',
                'Instructor: Certified firearms instructor',
                'Prerequisites: Valid ID required',
                'Materials: Provided by Rhys Firearms'
            ];
        } elseif ($product['type'] == 'subscription') {
            $specs = [
                'Duration: 1 year',
                'Access: Unlimited range time',
                'Benefits: Priority service scheduling',
                'Includes: Safety equipment rental',
                'Support: 24/7 technical assistance'
            ];
        }

        // Resolve image specifically for this product (use same mapping from the listing)
        $modalImage = isset($imageMap[$product['name']]) ? $imageMap[$product['name']] : 'default.jpg';
    ?>
    <div class="modal fade product-detail-modal" id="<?php echo $modalId; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="product-img">
                                <img src="img/products/<?php echo htmlspecialchars($modalImage); ?>"
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.onerror=null; this.src='img/products/default.jpg';">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-accent">$<?php echo number_format($product['price'], 2); ?></h4>
                            <p class="text-bright"><?php echo htmlspecialchars($product['description']); ?></p>
                            <?php if (!empty($specs)): ?>
                            <ul class="text-bright">
                                <?php foreach ($specs as $spec): ?>
                                <li><?php echo $spec; ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                            <div class="d-grid gap-2 mt-4">
                                <?php if (isLoggedIn()): ?>
                                    <form method="POST" action="cart.php" class="d-inline">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-accent">Add to Cart</button>
                                    </form>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-accent">Login to Purchase</a>
                                <?php endif; ?>
                                <button class="btn btn-outline-accent" data-bs-dismiss="modal">Continue Shopping</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Footer Start -->
    <div class="container-fluid footer py-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <h4 class="text-uppercase text-white mb-4">RHYS FIREARMS</h4>
                    <p class="text-bright">Crafting precision firearms with uncompromising quality since 1987.</p>
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
                        <a class="text-bright mb-2" href="index.php"><i class="bi bi-arrow-right text-accent me-2"></i>Home</a>
                        <a class="text-bright mb-2" href="about.php"><i class="bi bi-arrow-right text-accent me-2"></i>About Us</a>
                        <a class="text-bright mb-2" href="products.php"><i class="bi bi-arrow-right text-accent me-2"></i>Products</a>
                        <a class="text-bright mb-2" href="services.php"><i class="bi bi-arrow-right text-accent me-2"></i>Services</a>
                        <a class="text-bright" href="contact.php"><i class="bi bi-arrow-right text-accent me-2"></i>Contact</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-uppercase text-white mb-4">Contact Info</h5>
                    <p class="text-bright mb-2"><i class="bi bi-geo-alt text-accent me-2"></i>123 Safety Lane, Gunville, TX 75001</p>
                    <p class="text-bright mb-2"><i class="bi bi-envelope text-accent me-2"></i>info@rhysfirearms.com</p>
                    <p class="text-bright mb-2"><i class="bi bi-phone text-accent me-2"></i>+1 (555) 123-4567</p>
                    <p class="text-bright"><i class="bi bi-clock text-accent me-2"></i>Mon-Fri: 9AM-6PM, Sat: 10AM-4PM</p>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-12 text-center">
                    <p class="text-bright mb-0">&copy; <a class="text-white border-bottom" href="index.php">Rhys Firearms</a>. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        // Price range display
        document.getElementById('priceRange').addEventListener('input', function() {
            const minPrice = 0;
            const maxPrice = 5000;
            const currentValue = this.value;
            const range = document.querySelector('.price-range');
            
            if(currentValue < 500) {
                range.textContent = `$0 - $${currentValue}`;
            } else {
                range.textContent = `$${currentValue - 500} - $${currentValue}`;
            }
        });

        // Filter functionality
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // In a real implementation, this would filter products
                console.log('Filter changed:', this.id, this.checked);
            });
        });

        // View mode toggle
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // In a real implementation, this would change the product display layout
                if(this.querySelector('.bi-list')) {
                    console.log('Switching to list view');
                } else {
                    console.log('Switching to grid view');
                }
            });
        });
    </script>
</body>
</html>