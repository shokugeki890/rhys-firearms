<?php
require_once 'database.php';
require_once 'auth.php';
requireAdmin(); // Only admins can access this page

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit_user':
                $id = $_POST['id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                
                // Update user in database
                $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
                if ($stmt->execute([$username, $email, $role, $id])) {
                    $success_message = "User updated successfully!";
                } else {
                    $error_message = "Failed to update user.";
                }
                break;
                
            case 'delete_user':
                $id = $_POST['id'];
                if ($id != $_SESSION['user_id']) {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    if ($stmt->execute([$id])) {
                        $success_message = "User deleted successfully!";
                    } else {
                        $error_message = "Failed to delete user.";
                    }
                } else {
                    $error_message = "You cannot delete your own account.";
                }
                break;
                
            case 'add_user':
                $username = $_POST['username'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                // Check if user already exists
                $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $check_stmt->execute([$username, $email]);
                
                if ($check_stmt->fetch()) {
                    $error_message = "Username or email already exists!";
                } else {
                    // Insert new user
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$username, $email, $password, $role])) {
                        $success_message = "User added successfully!";
                    } else {
                        $error_message = "Failed to add user.";
                    }
                }
                break;

            // New: update order status
            case 'update_order_status':
                $order_id = $_POST['id'];
                $new_status = $_POST['status'];
                $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                if ($stmt->execute([$new_status, $order_id])) {
                    $success_message = "Order status updated.";
                } else {
                    $error_message = "Failed to update order status.";
                }
                break;

            // New: delete an order
            case 'delete_order':
                $order_id = $_POST['id'];
                $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
                if ($stmt->execute([$order_id])) {
                    // optional: delete order_items if table exists
                    try {
                        $del_items = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
                        $del_items->execute([$order_id]);
                    } catch (Exception $e) {
                        // ignore if table doesn't exist
                    }
                    $success_message = "Order deleted.";
                } else {
                    $error_message = "Failed to delete order.";
                }
                break;

            // New: refund transaction
            case 'refund_transaction':
                $tx_id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE transactions SET status = 'refunded' WHERE id = ?");
                if ($stmt->execute([$tx_id])) {
                    $success_message = "Transaction marked as refunded.";
                } else {
                    $error_message = "Failed to update transaction.";
                }
                break;
        }
    }
}

// Determine which admin page to show
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Panel - Rhys Firearms</title>
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
        
        .admin-sidebar {
            background: var(--secondary);
            min-height: 100vh;
            padding: 0;
        }
        
        .admin-content {
            padding: 2rem;
        }
        
        .admin-nav .nav-link {
            color: var(--text);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #444;
            display: block;
        }
        
        .admin-nav .nav-link:hover,
        .admin-nav .nav-link.active {
            background-color: var(--accent);
            color: white;
            text-decoration: none;
        }
        
        .stats-card {
            background: var(--secondary);
            padding: 1.5rem;
            border-left: 4px solid var(--accent);
            margin-bottom: 1rem;
        }
        
        .user-table {
            background: var(--secondary);
        }
        
        .user-table th {
            border-bottom: 2px solid var(--accent);
        }
        
        .text-bright {
            color: var(--bright-text) !important;
        }
        
        .text-muted {
            color: var(--bright-text) !important;
        }
        
        .user-table td {
            color: var(--bright-text);
        }
        
        .badge {
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
        
        .btn-outline-danger {
            border: 1px solid #dc3545;
            color: #dc3545;
            background: transparent;
        }
        
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
        
        .bg-accent {
            background-color: var(--accent) !important;
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
        
        .modal-content {
            background-color: var(--secondary);
            color: var(--text);
        }
        
        .modal-header {
            border-bottom: 1px solid var(--border);
        }
        
        .modal-footer {
            border-top: 1px solid var(--border);
        }
        
        .form-control, .form-select {
            background-color: #1a1a1a;
            border: 1px solid #444;
            color: var(--text);
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #1a1a1a;
            border-color: var(--accent);
            color: var(--text);
            box-shadow: 0 0 0 0.2rem rgba(200, 16, 46, 0.25);
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
        
        .editable-field {
            cursor: pointer;
            transition: background-color 0.2s;
            padding: 2px 4px;
            border-radius: 3px;
        }
        
        .editable-field:hover {
            background-color: rgba(200, 16, 46, 0.2);
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .action-buttons button {
            font-size: 0.8rem;
            padding: 4px 8px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <div class="p-3">
                    <h4 class="text-accent">Admin Panel</h4>
                    <p class="text-bright small">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>
                
                <nav class="admin-nav">
                    <a href="admin.php" class="nav-link <?php echo $page === 'dashboard' ? 'active' : '';?>">Dashboard</a>
                    <a href="admin.php?page=users" class="nav-link <?php echo $page === 'users' ? 'active' : '';?>">User Management</a>
                    <a href="admin.php?page=products" class="nav-link <?php echo $page === 'products' ? 'active' : '';?>">Product Management</a>
                    <a href="admin.php?page=orders" class="nav-link <?php echo $page === 'orders' ? 'active' : '';?>">Order Management</a>
                    <a href="admin.php?page=transactions" class="nav-link <?php echo $page === 'transactions' ? 'active' : '';?>">Transactions</a>
                    <a href="admin.php?page=reports" class="nav-link <?php echo $page === 'reports' ? 'active' : '';?>">Reports</a>
                    <a href="index.php" class="nav-link">← Back to Site</a>
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 admin-content">
                <?php if ($page === 'dashboard'): ?>
                    <h2 class="mb-4">Administrator Dashboard</h2>
                    
                    <!-- Success/Error Messages -->
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Highlight for admin landing page -->
                    <div class="mb-4">
                        <h4 class="text-accent">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?> — Admin Overview</h4>
                        <p class="text-bright">Quick access to users, orders and transactions. Use the left menu to manage system data.</p>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h5>Total Users</h5>
                                <h3 class="text-accent">
                                    <?php 
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
                                        echo (int)$stmt->fetchColumn();
                                    } catch (Exception $e) {
                                        echo '-';
                                    }
                                    ?>
                                </h3>
                                <p class="text-bright small mb-0">All registered users</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h5>Admins</h5>
                                <h3 class="text-accent">
                                    <?php 
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
                                        echo (int)$stmt->fetchColumn();
                                    } catch (Exception $e) {
                                        echo '-';
                                    }
                                    ?>
                                </h3>
                                <p class="text-bright small mb-0">Administrator accounts</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h5>Orders (Last 30 days)</h5>
                                <h3 class="text-accent">
                                    <?php 
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
                                        echo (int)$stmt->fetchColumn();
                                    } catch (Exception $e) {
                                        echo '-';
                                    }
                                    ?>
                                </h3>
                                <p class="text-bright small mb-0">Recent orders</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h5>Transactions (Last 30 days)</h5>
                                <h3 class="text-accent">
                                    <?php 
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM transactions WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
                                        echo (int)$stmt->fetchColumn();
                                    } catch (Exception $e) {
                                        echo '-';
                                    }
                                    ?>
                                </h3>
                                <p class="text-bright small mb-0">Recent transactions</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity Section -->
                    <div class="mt-5">
                        <h4 class="mb-3">Recent Activity</h4>
                        <div class="stats-card">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-bright">New Registrations (Last 7 Days)</h5>
                                    <p class="text-accent h4">
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
                                        echo (int)$stmt->fetchColumn();
                                    } catch (Exception $e) {
                                        echo '-';
                                    }
                                    ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-bright">System Status</h5>
                                    <p class="text-accent h4"><span class="badge bg-success">All Systems Operational</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($page === 'users'): ?>
                    <h2 class="mb-4">User Management</h2>

                    <!-- Success/Error Messages -->
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Users</h4>
                        <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table user-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
                                    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td><?php echo (int)$user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $user['role'] == 'admin' ? 'bg-accent' : 'bg-secondary'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(date('M j, Y', strtotime($user['created_at']))); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-accent" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                                data-id="<?php echo (int)$user['id']; ?>"
                                                data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                                data-role="<?php echo htmlspecialchars($user['role']); ?>">
                                                Edit
                                            </button>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" 
                                                    data-id="<?php echo (int)$user['id']; ?>"
                                                    data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                    Delete
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="6" class="text-bright">Unable to load users.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                <?php elseif ($page === 'orders'): ?>
                    <h2 class="mb-4">Order Management</h2>

                    <!-- Messages -->
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table user-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
                                    while ($order = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td><?php echo (int)$order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['username'] ?? 'Guest'); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($order['total'] ?? 0, 2)); ?></td>
                                    <td><span class="badge <?php echo ($order['status'] === 'shipped') ? 'bg-success' : 'bg-secondary'; ?>"><?php echo htmlspecialchars(ucfirst($order['status'] ?? 'pending')); ?></span></td>
                                    <td><?php echo htmlspecialchars(date('M j, Y', strtotime($order['created_at'] ?? 'now'))); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-accent" data-bs-toggle="modal" data-bs-target="#viewOrderModal"
                                                data-id="<?php echo (int)$order['id']; ?>">View</button>

                                            <button class="btn btn-sm btn-accent" data-bs-toggle="modal" data-bs-target="#updateOrderStatusModal"
                                                data-id="<?php echo (int)$order['id']; ?>" data-status="<?php echo htmlspecialchars($order['status']); ?>">Update Status</button>

                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete_order">
                                                <input type="hidden" name="id" value="<?php echo (int)$order['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this order?');">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile;
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="6" class="text-bright">No orders or unable to read orders table.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- View Order Modal -->
                    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Order Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="orderDetailsContent">
                                        <p class="text-bright">Loading order details...</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Order Status Modal -->
                    <div class="modal fade" id="updateOrderStatusModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Order Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="update_order_status">
                                        <input type="hidden" name="id" id="updateOrderId">
                                        <div class="mb-3">
                                            <label for="orderStatusSelect" class="form-label">Status</label>
                                            <select class="form-select" id="orderStatusSelect" name="status" required>
                                                <option value="pending">Pending</option>
                                                <option value="processing">Processing</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-accent">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php elseif ($page === 'transactions' || $page === 'reports'): ?>
                    <h2 class="mb-4"><?php echo $page === 'transactions' ? 'Transaction History' : 'Reports'; ?></h2>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table user-table">
                            <thead>
                                <tr>
                                    <th>TX ID</th>
                                    <th>User</th>
                                    <th>Order ID</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT t.*, u.username FROM transactions t LEFT JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
                                    while ($tx = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td><?php echo (int)$tx['id']; ?></td>
                                    <td><?php echo htmlspecialchars($tx['username'] ?? 'Guest'); ?></td>
                                    <td><?php echo htmlspecialchars($tx['order_id'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($tx['amount'] ?? 0, 2)); ?></td>
                                    <td><?php echo htmlspecialchars($tx['method'] ?? 'N/A'); ?></td>
                                    <td><span class="badge <?php echo ($tx['status'] === 'refunded') ? 'bg-secondary' : 'bg-accent'; ?>"><?php echo htmlspecialchars(ucfirst($tx['status'] ?? 'unknown')); ?></span></td>
                                    <td><?php echo htmlspecialchars(date('M j, Y', strtotime($tx['created_at'] ?? 'now'))); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-accent" data-bs-toggle="modal" data-bs-target="#viewTransactionModal"
                                                data-id="<?php echo (int)$tx['id']; ?>">View</button>

                                            <?php if (($tx['status'] ?? '') !== 'refunded'): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="refund_transaction">
                                                <input type="hidden" name="id" value="<?php echo (int)$tx['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Mark transaction as refunded?');">Refund</button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile;
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="8" class="text-bright">No transactions or unable to read transactions table.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- View Transaction Modal -->
                    <div class="modal fade" id="viewTransactionModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Transaction Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="transactionDetailsContent">
                                        <p class="text-bright">Loading transaction details...</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <h2>Unknown Page</h2>
                    <p class="text-bright">The requested admin page is not available.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Existing Modals: Edit/Delete/Add user (unchanged) -->
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_user">
                        <input type="hidden" name="id" id="editUserId">
                        
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-accent">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="delete_user">
                        <input type="hidden" name="id" id="deleteUserId">
                        <p class="text-bright">Are you sure you want to delete user: <strong id="deleteUsername"></strong>?</p>
                        <p class="text-bright">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_user">
                        
                        <div class="mb-3">
                            <label for="addUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="addUsername" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="addEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="addEmail" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="addPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="addPassword" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="addRole" class="form-label">Role</label>
                            <select class="form-select" id="addRole" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-accent">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Edit User Modal
        const editUserModal = document.getElementById('editUserModal');
        if (editUserModal) {
            editUserModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const username = button.getAttribute('data-username');
                const email = button.getAttribute('data-email');
                const role = button.getAttribute('data-role');
                
                document.getElementById('editUserId').value = id;
                document.getElementById('editUsername').value = username;
                document.getElementById('editEmail').value = email;
                document.getElementById('editRole').value = role;
            });
        }
        
        // Delete User Modal
        const deleteUserModal = document.getElementById('deleteUserModal');
        if (deleteUserModal) {
            deleteUserModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const username = button.getAttribute('data-username');
                
                document.getElementById('deleteUserId').value = id;
                document.getElementById('deleteUsername').textContent = username;
            });
        }

        // View Order Modal: fetch details via AJAX (fetch)
        const viewOrderModal = document.getElementById('viewOrderModal');
        if (viewOrderModal) {
            viewOrderModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const content = document.getElementById('orderDetailsContent');
                content.innerHTML = '<p class="text-bright">Loading order details...</p>';

                fetch('admin_ajax.php?action=order_details&id=' + encodeURIComponent(id))
                    .then(res => res.json())
                    .then(json => {
                        if (json.success) {
                            let html = '<h5 class="text-accent">Order #' + json.order.id + '</h5>';
                            html += '<p class="text-bright">Customer: ' + (json.order.username || 'Guest') + '</p>';
                            html += '<p class="text-bright">Total: ' + parseFloat(json.order.total).toFixed(2) + '</p>';
                            html += '<p class="text-bright">Status: ' + (json.order.status || '') + '</p>';
                            html += '<h6 class="mt-3">Items</h6>';
                            html += '<ul class="text-bright">';
                            if (json.items && json.items.length) {
                                json.items.forEach(function(it) {
                                    html += '<li>' + (it.product_name || ('Product #' + it.product_id)) + ' × ' + it.quantity + ' — $' + parseFloat(it.price).toFixed(2) + '</li>';
                                });
                            } else {
                                html += '<li>No items available.</li>';
                            }
                            html += '</ul>';
                            content.innerHTML = html;
                        } else {
                            content.innerHTML = '<p class="text-bright">Unable to load order details.</p>';
                        }
                    })
                    .catch(() => {
                        content.innerHTML = '<p class="text-bright">Unable to load order details.</p>';
                    });
            });
        }

        // Update Order Status Modal: populate current status and id
        const updateOrderStatusModal = document.getElementById('updateOrderStatusModal');
        if (updateOrderStatusModal) {
            updateOrderStatusModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const status = button.getAttribute('data-status') || 'pending';
                document.getElementById('updateOrderId').value = id;
                document.getElementById('orderStatusSelect').value = status;
            });
        }

        // View Transaction Modal
        const viewTransactionModal = document.getElementById('viewTransactionModal');
        if (viewTransactionModal) {
            viewTransactionModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const content = document.getElementById('transactionDetailsContent');
                content.innerHTML = '<p class="text-bright">Loading transaction details...</p>';

                fetch('admin_ajax.php?action=transaction_details&id=' + encodeURIComponent(id))
                    .then(res => res.json())
                    .then(json => {
                        if (json.success) {
                            let html = '<h5 class="text-accent">Transaction #' + json.tx.id + '</h5>';
                            html += '<p class="text-bright">User: ' + (json.tx.username || 'Guest') + '</p>';
                            html += '<p class="text-bright">Order ID: ' + (json.tx.order_id || '-') + '</p>';
                            html += '<p class="text-bright">Amount: $' + parseFloat(json.tx.amount).toFixed(2) + '</p>';
                            html += '<p class="text-bright">Method: ' + (json.tx.method || '-') + '</p>';
                            html += '<p class="text-bright">Status: ' + (json.tx.status || '-') + '</p>';
                            content.innerHTML = html;
                        } else {
                            content.innerHTML = '<p class="text-bright">Unable to load transaction details.</p>';
                        }
                    })
                    .catch(() => {
                        content.innerHTML = '<p class="text-bright">Unable to load transaction details.</p>';
                    });
            });
        }
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                try {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } catch (e) {}
            });
        }, 5000);
    </script>
</body>
</html>