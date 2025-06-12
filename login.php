<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? '/admin_dashboard.php' : '/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND is_admin = 0");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            redirect('/dashboard.php');
        } else {
            $error = 'Invalid username or password';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

$pageTitle = "User Login";
require_once 'includes/header.php';
?>
<div class="form-container">
<section class="auth-form">
        <img src="images/user_login.png">
    <h1>User Login</h1>
    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
  
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <p>Don't have an account? <a href="/register.php">Register here</a></p>
    <p>Are you an admin? <a href="/admin_login.php">Admin login here</a></p>
</section>
</div>