<?php
// database.php - Database connection
$host = 'localhost';
$dbname = 'rhys_firearms';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create users table if it doesn't exist
$createTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$pdo->exec($createTable);

// Insert default admin user if not exists
$checkAdmin = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
if ($checkAdmin->fetchColumn() == 0) {
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $insertAdmin = "INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@rhysfirearms.com', '$hashedPassword', 'admin')";
    $pdo->exec($insertAdmin);
}

// Create teams table if it doesn't exist
$createTeamsTable = "
CREATE TABLE IF NOT EXISTS teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$pdo->exec($createTeamsTable);

// Insert default team members if not exists
$checkTeams = $pdo->query("SELECT COUNT(*) FROM teams");
if ($checkTeams->fetchColumn() == 0) {
    $insertTeams = "
    INSERT INTO teams (name) VALUES 
    ('John Rhys'),
    ('Sarah Chen'),
    ('Michael Rodriguez')
    ";
    $pdo->exec($insertTeams);
}

// Create category table if it doesn't exist
$createCategoryTable = "
CREATE TABLE IF NOT EXISTS category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$pdo->exec($createCategoryTable);

// Insert default categories if not exists
$checkCategories = $pdo->query("SELECT COUNT(*) FROM category");
if ($checkCategories->fetchColumn() == 0) {
    $insertCategories = "
    INSERT INTO category (name) VALUES 
    ('firearms'),
    ('courses'),
    ('subscriptions')
    ";
    $pdo->exec($insertCategories);
}

// Create products table if it doesn't exist
$createProductsTable = "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('subscription', 'course', 'firearms') NOT NULL,
    category_id INT NOT NULL,
    team_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES category(id),
    FOREIGN KEY (team_id) REFERENCES teams(id)
)";

$pdo->exec($createProductsTable);

// Create transaction table if it doesn't exist
$createTransactionTable = "
CREATE TABLE IF NOT EXISTS transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    status ENUM('cart', 'purchased') DEFAULT 'cart',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";

$pdo->exec($createTransactionTable);

// Insert default products if not exists
$checkProducts = $pdo->query("SELECT COUNT(*) FROM products");
if ($checkProducts->fetchColumn() == 0) {
    $insertProducts = "
    INSERT INTO products (name, type, category_id, team_id, price, description) VALUES
    ('R-15 Tactical Rifle', 'firearms', (SELECT id FROM category WHERE name = 'firearms'), (SELECT id FROM teams WHERE name = 'John Rhys'), 1299.99, 'Semi-automatic .223/5.56 rifle with tactical features'),
    ('RP9 9mm Pistol', 'firearms', (SELECT id FROM category WHERE name = 'firearms'), (SELECT id FROM teams WHERE name = 'Sarah Chen'), 599.99, 'Compact 9mm handgun with exceptional accuracy'),
    ('R-700 Bolt Action', 'firearms', (SELECT id FROM category WHERE name = 'firearms'), (SELECT id FROM teams WHERE name = 'John Rhys'), 899.99, 'Precision bolt-action rifle for hunting and long-range'),
    ('RS12 Tactical Shotgun', 'firearms', (SELECT id FROM category WHERE name = 'firearms'), (SELECT id FROM teams WHERE name = 'Michael Rodriguez'), 749.99, 'Semi-automatic tactical shotgun for defense'),
    ('R-10 Compact Rifle', 'firearms', (SELECT id FROM category WHERE name = 'firearms'), (SELECT id FROM teams WHERE name = 'Sarah Chen'), 1099.99, 'Lightweight compact rifle for versatile use'),
    ('RP45 .45 ACP Pistol', 'firearms', (SELECT id FROM category WHERE name = 'firearms'), (SELECT id FROM teams WHERE name = 'Michael Rodriguez'), 649.99, 'Full-size .45 ACP with exceptional stopping power'),
    ('Basic Firearm Safety Course', 'course', (SELECT id FROM category WHERE name = 'courses'), (SELECT id FROM teams WHERE name = 'Sarah Chen'), 99.99, 'Comprehensive beginner firearm safety training'),
    ('Concealed Carry Certification', 'course', (SELECT id FROM category WHERE name = 'courses'), (SELECT id FROM teams WHERE name = 'Michael Rodriguez'), 199.99, 'Complete CCW certification course with range time'),
    ('Advanced Tactical Training', 'course', (SELECT id FROM category WHERE name = 'courses'), (SELECT id FROM teams WHERE name = 'John Rhys'), 349.99, 'Advanced marksmanship and tactical training'),
    ('Annual Range Membership', 'subscription', (SELECT id FROM category WHERE name = 'subscriptions'), (SELECT id FROM teams WHERE name = 'Sarah Chen'), 299.99, 'Unlimited range access and training sessions'),
    ('Premium Gunsmith Service', 'subscription', (SELECT id FROM category WHERE name = 'subscriptions'), (SELECT id FROM teams WHERE name = 'John Rhys'), 149.99, 'Priority gunsmithing and maintenance services')
    ";
    $pdo->exec($insertProducts);
}
?>