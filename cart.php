<?php
require_once 'database.php';
require_once 'auth.php';
//this is cart.php
$message = '';
$error = '';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isLoggedIn()) {
        $error = 'Please login to manage your cart.';
    } else {
        $user_id = $_SESSION['user_id'];
        $action = $_POST['action'];

        switch ($action) {
            case 'add':
                $product_id = (int)$_POST['product_id'];
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

                // Check if item already in cart
                $stmt = $pdo->prepare("SELECT id, quantity FROM transaction WHERE user_id = ? AND product_id = ? AND status = 'cart'");
                $stmt->execute([$user_id, $product_id]);
                $existing = $stmt->fetch();

                if ($existing) {
                    // Update quantity
                    $new_quantity = $existing['quantity'] + $quantity;
                    $stmt = $pdo->prepare("UPDATE transaction SET quantity = ? WHERE id = ?");
                    $stmt->execute([$new_quantity, $existing['id']]);
                } else {
                    // Add new item
                    $stmt = $pdo->prepare("INSERT INTO transaction (user_id, product_id, quantity, status) VALUES (?, ?, ?, 'cart')");
                    $stmt->execute([$user_id, $product_id, $quantity]);
                }
                $message = 'Item added to cart successfully!';
                break;

            case 'update':
                $cart_id = (int)$_POST['cart_id'];
                $quantity = (int)$_POST['quantity'];

                if ($quantity > 0) {
                    $stmt = $pdo->prepare("UPDATE transaction SET quantity = ? WHERE id = ? AND user_id = ? AND status = 'cart'");
                    $stmt->execute([$quantity, $cart_id, $user_id]);
                    $message = 'Cart updated successfully!';
                } else {
                    // Remove item if quantity is 0
                    $stmt = $pdo->prepare("DELETE FROM transaction WHERE id = ? AND user_id = ? AND status = 'cart'");
                    $stmt->execute([$cart_id, $user_id]);
                    $message = 'Item removed from cart!';
                }
                break;

            case 'remove':
                $cart_id = (int)$_POST['cart_id'];
                $stmt = $pdo->prepare("DELETE FROM transaction WHERE id = ? AND user_id = ? AND status = 'cart'");
                $stmt->execute([$cart_id, $user_id]);
                $message = 'Item removed from cart!';
                break;

            case 'checkout':
                // Convert cart items to purchased
                $stmt = $pdo->prepare("UPDATE transaction SET status = 'purchased' WHERE user_id = ? AND status = 'cart'");
                $stmt->execute([$user_id]);
                $message = 'Order placed successfully! Thank you for your purchase.';
                break;
        }
    }
}

// Get cart items for logged in user
$cart_items = [];
$total = 0;
$item_count = 0;

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

if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("
        SELECT t.id as cart_id, t.quantity, p.id, p.name, p.price, p.description, p.type, c.name as category_name
        FROM transaction t
        JOIN products p ON t.product_id = p.id
        LEFT JOIN category c ON p.category_id = c.id
        WHERE t.user_id = ? AND t.status = 'cart'
        ORDER BY t.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
        $item_count += $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Shopping Cart - Rhys Firearms</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1a1a1a;
            --secondary: #333;
            --accent: #c8102e;
            --text: #e8e8e8;
            --bright-text: #b8b8b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: var(--text);
        }

        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .cart-item {
            background: var(--secondary);
            border: 1px solid #444;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .cart-item:hover {
            border-color: var(--accent);
        }

        .cart-item-img {
            width: 80px;
            height: 80px;
            background: #222;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            overflow: hidden;
        }

        .cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quantity-input {
            width: 80px;
            text-align: center;
            background: #1a1a1a;
            border: 1px solid #444;
            color: var(--text);
            padding: 0.5rem;
            border-radius: 4px;
        }

        .cart-summary {
            background: var(--secondary);
            border: 1px solid #444;
            border-radius: 8px;
            padding: 2rem;
            position: sticky;
            top: 20px;
        }

        .btn-accent {
            background-color: var(--accent);
            color: white;
            border: none;
        }

        .btn-accent:hover {
            background-color: #a00d25;
            color: white;
        }

        .btn-outline-accent {
            border: 1px solid var(--accent);
            color: var(--accent);
            background: transparent;
        }

        .btn-outline-accent:hover {
            background-color: var(--accent);
            color: white;
        }

        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--secondary);
            border: 1px solid #444;
            border-radius: 8px;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border-color: #28a745;
            color: #b8f7c5;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #f8b8be;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                    <?php if (isAdmin()): ?>
                        <a href="admin.php" class="nav-item nav-link text-accent">Admin Panel</a>
                    <?php endif; ?>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="cart.php" class="nav-item nav-link active">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php if ($item_count > 0): ?>
                                <span class="badge bg-accent"><?php echo $item_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="logout.php" class="nav-item nav-link">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="nav-item nav-link">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="cart-container">
        <h2 class="mb-4">Shopping Cart</h2>

        <!-- Messages -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!isLoggedIn()): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h3>Please Login</h3>
                <p class="text-muted">You need to be logged in to view and manage your shopping cart.</p>
                <a href="login.php" class="btn btn-accent">Login Now</a>
            </div>
        <?php elseif (empty($cart_items)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Add some products to get started!</p>
                <a href="products.php" class="btn btn-accent">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="cart-item-img">
                                        <?php $image = isset($imageMap[$item['name']]) ? $imageMap[$item['name']] : 'default.jpg'; ?>
                                        <img src="img/products/<?php echo $image; ?>"
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             onerror="this.onerror=null; this.classList.add('no-image'); this.innerHTML='<?php echo htmlspecialchars($item['name']); ?> Image';">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <p class="text-muted small mb-0"><?php echo htmlspecialchars($item['category_name']); ?> • <?php echo ucfirst($item['type']); ?></p>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold text-accent">$<?php echo number_format($item['price'], 2); ?></span>
                                </div>
                                <div class="col-md-2">
                                    <form method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>"
                                               min="1" class="quantity-input me-2" onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <span class="fw-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                    <form method="POST" class="d-inline ms-2">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this item from cart?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h4 class="mb-4">Order Summary</h4>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Items (<?php echo $item_count; ?>)</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>FREE</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>$<?php echo number_format($total * 0.08, 2); ?></span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong class="text-accent">$<?php echo number_format($total * 1.08, 2); ?></strong>
                        </div>

                        <a href="checkout.php" class="btn btn-accent w-100 mb-3">
                            <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                        </a>

                        <a href="products.php" class="btn btn-outline-accent w-100">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="container-fluid bg-dark py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Rhys Firearms. All Rights Reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
