<?php
require_once 'database.php';
require_once 'auth.php';

$message = '';
$error = '';

// Redirect if not logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
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

$total = 0;
$item_count = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
    $item_count += $item['quantity'];
}

$tax = $total * 0.08;
$grand_total = $total + $tax;

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'complete_order') {
    // Basic validation
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip = trim($_POST['zip'] ?? '');
    $card_number = trim($_POST['card_number'] ?? '');
    $expiry = trim($_POST['expiry'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');

    if (empty($name) || empty($email) || empty($address) || empty($city) || empty($state) || empty($zip) ||
        empty($card_number) || empty($expiry) || empty($cvv)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($cart_items)) {
        $error = 'Your cart is empty.';
    } else {

        // Start transaction for atomicity
        $pdo->beginTransaction();

        try {
            // Insert order
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total, shipping_name, shipping_email, shipping_address, shipping_city, shipping_state, shipping_zip)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$user_id, $grand_total, $name, $email, $address, $city, $state, $zip]);
            $order_id = $pdo->lastInsertId();

            // Insert order items
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");
            foreach ($cart_items as $item) {
                $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            }

            // Insert transaction record
            $stmt = $pdo->prepare("
                INSERT INTO transactions (user_id, order_id, amount, method, status)
                VALUES (?, ?, ?, 'credit_card', 'completed')
            ");
            $stmt->execute([$user_id, $order_id, $grand_total]);

            // Update cart items to purchased (keep for history)
            $stmt = $pdo->prepare("UPDATE transaction SET status = 'purchased' WHERE user_id = ? AND status = 'cart'");
            $stmt->execute([$user_id]);

            // Commit transaction
            $pdo->commit();

            $message = 'Order completed successfully! You will receive a confirmation email shortly.';

            // Clear cart items from display
            $cart_items = [];
            $total = 0;
            $item_count = 0;

        } catch (Exception $e) {
            // Rollback on error
            $pdo->rollBack();
            $error = 'Failed to process order. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Checkout - Rhys Firearms</title>
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

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .checkout-form {
            background: var(--secondary);
            border: 1px solid #444;
            border-radius: 8px;
            padding: 2rem;
        }

        .order-summary {
            background: var(--secondary);
            border: 1px solid #444;
            border-radius: 8px;
            padding: 2rem;
            position: sticky;
            top: 20px;
        }

        .form-control {
            background-color: #1a1a1a;
            border: 1px solid #444;
            color: var(--text);
        }

        .form-control:focus {
            background-color: #1a1a1a;
            border-color: var(--accent);
            color: var(--text);
            box-shadow: 0 0 0 0.2rem rgba(200, 16, 46, 0.25);
        }

        .form-label {
            color: var(--bright-text);
            font-weight: 500;
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

        .order-item {
            border-bottom: 1px solid #444;
            padding: 1rem 0;
        }

        .order-item:last-child {
            border-bottom: none;
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
                    <a href="cart.php" class="nav-item nav-link">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <?php if ($item_count > 0): ?>
                            <span class="badge bg-accent"><?php echo $item_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="logout.php" class="nav-item nav-link">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="checkout-container">
        <h2 class="mb-4">Checkout</h2>

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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items) && !$message): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Add some products before checking out.</p>
                <a href="products.php" class="btn btn-accent">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Checkout Form -->
                <div class="col-lg-8">
                    <div class="checkout-form">
                        <h4 class="mb-4">Shipping & Payment Information</h4>

                        <form method="POST">
                            <input type="hidden" name="action" value="complete_order">

                            <!-- Shipping Information -->
                            <h5 class="mb-3">Shipping Address</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Street Address *</label>
                                <input type="text" class="form-control" id="address" name="address" required
                                       placeholder="123 Main Street">
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label">State *</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="zip" class="form-label">ZIP Code *</label>
                                    <input type="text" class="form-control" id="zip" name="zip" required>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <h5 class="mb-3 mt-4">Payment Details</h5>
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number *</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" required
                                       placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiry" class="form-label">Expiry Date *</label>
                                    <input type="text" class="form-control" id="expiry" name="expiry" required
                                           placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV *</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" required
                                           placeholder="123" maxlength="4">
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label text-muted" for="terms">
                                        I agree to the <a href="#" class="text-accent">Terms of Service</a> and <a href="#" class="text-accent">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-accent btn-lg w-100">
                                <i class="fas fa-lock me-2"></i>Complete Order - $<?php echo number_format($grand_total, 2); ?>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="order-summary">
                        <h4 class="mb-4">Order Summary</h4>

                        <?php foreach ($cart_items as $item): ?>
                            <div class="order-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                                    </div>
                                    <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>FREE</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>$<?php echo number_format($tax, 2); ?></span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-0">
                            <strong>Total</strong>
                            <strong class="text-accent">$<?php echo number_format($grand_total, 2); ?></strong>
                        </div>
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

    <script>
        // Format card number input
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
            e.target.value = formattedValue;
        });

        // Format expiry date input
        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
