<?php
require_once 'includes/config.php';

if (isLoggedIn() && isAdmin()) {
    redirect('/admin_dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND is_admin = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            redirect('/admin_dashboard.php');
        } else {
            $error = 'Invalid admin credentials';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

$pageTitle = "Admin Login";
require_once 'includes/header.php';
?>
<div class="form-container">
  
<section class="auth-form">
          <img src="images/admin.png">
    <h1>Admin Login</h1>
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
    <p>Not an admin? <a href="/login.php">User login here</a></p>
</section>
</div>
