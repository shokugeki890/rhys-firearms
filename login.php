<?php
require_once 'database.php';
require_once 'auth.php';
//this is login.php
// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $user = authenticateUser($pdo, $username, $password);
    
    if ($user) {
        login($user);
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid username/email or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - Rhys Firearms</title>
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-container {
            background: var(--secondary);
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            padding: 2rem;
            border: 1px solid #444;
        }
        
        .brand-logo {
            font-family: 'Oswald', sans-serif;
            font-size: 2.5rem;
            color: var(--accent);
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .btn-accent {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-accent:hover {
            background-color: #a00d25;
            color: white;
        }
        
        .form-control {
            background-color: #222;
            border: 1px solid #444;
            color: var(--text);
            border-radius: 0;
            padding: 10px 15px;
        }
        
        .form-control:focus {
            background-color: #222;
            border-color: var(--accent);
            color: var(--text);
            box-shadow: none;
        }
        
        .login-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .login-links a {
            color: var(--accent);
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-container">
                    <div class="brand-logo">RHYS FIREARMS</div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email</label>
                            <input type="text" class="form-control" id="username" name="username" required 
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-accent">Login</button>
                    </form>
                    
                    <div class="login-links">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                        <p><a href="index.php">← Back to Home</a></p>
                    </div>
                    
                    <!-- Demo Credentials -->
                    <div class="mt-4 p-3 bg-dark">
                        <h6 class="text-accent">Demo Credentials:</h6>
                        <p class="mb-1"><strong>Admin:</strong> admin / admin123</p>
                        <p class="mb-0"><strong>User:</strong> Register to create a user account</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>