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
        }
    }
}
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
        }
        
        .admin-nav .nav-link:hover,
        .admin-nav .nav-link.active {
            background-color: var(--accent);
            color: white;
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
                    <a href="admin.php" class="nav-link active">Dashboard</a>
                    <a href="admin.php?page=users" class="nav-link">User Management</a>
                    <a href="admin.php?page=products" class="nav-link">Product Management</a>
                    <a href="admin.php?page=orders" class="nav-link">Order Management</a>
                    <a href="admin.php?page=reports" class="nav-link">Reports</a>
                    <a href="index.php" class="nav-link">← Back to Site</a>
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 admin-content">
                <h2 class="mb-4">Administrator Dashboard</h2>
                
                <!-- Success/Error Messages -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h5>Total Users</h5>
                            <h3 class="text-accent">
                                <?php 
                                $stmt = $pdo->query("SELECT COUNT(*) FROM users");
                                echo $stmt->fetchColumn();
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
                                $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
                                echo $stmt->fetchColumn();
                                ?>
                            </h3>
                            <p class="text-bright small mb-0">Administrator accounts</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h5>Regular Users</h5>
                            <h3 class="text-accent">
                                <?php 
                                $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
                                echo $stmt->fetchColumn();
                                ?>
                            </h3>
                            <p class="text-bright small mb-0">Standard user accounts</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h5>Active Sessions</h5>
                            <h3 class="text-accent">-</h3>
                            <p class="text-bright small mb-0">Currently logged in users</p>
                        </div>
                    </div>
                </div>
                
                <!-- User Management -->
                <div class="mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>User Management</h4>
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
                                $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
                                while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $user['role'] == 'admin' ? 'bg-accent' : 'bg-secondary'; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-accent" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                                data-id="<?php echo $user['id']; ?>"
                                                data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                                data-role="<?php echo $user['role']; ?>">
                                                Edit
                                            </button>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" 
                                                    data-id="<?php echo $user['id']; ?>"
                                                    data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                    Delete
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Recent Activity Section -->
                <div class="mt-5">
                    <h4 class="mb-3">Recent Activity</h4>
                    <div class="stats-card">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-bright">New Registrations (Last 7 Days)</h5>
                                <p class="text-accent h4">12</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-bright">System Status</h5>
                                <p class="text-accent h4"><span class="badge bg-success">All Systems Operational</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        
        // Auto-dismiss alerts after 5 seconds
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