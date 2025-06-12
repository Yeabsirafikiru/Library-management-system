<?php
require_once 'includes/config.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(isAdmin() ? '/admin_profile.php' : '/profile.php');
}

$errors = [];
$user_id = $_POST['user_id'];
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$current_password = trim($_POST['current_password']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);

if ($user_id != $_SESSION['user_id']) {
    $_SESSION['error'] = "You can only update your own profile";
    redirect(isAdmin() ? '/admin_profile.php' : '/profile.php');
}

try {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        throw new Exception("User not found");
    }
    
    if (!password_verify($current_password, $user['password'])) {
        $errors[] = "Current password is incorrect";
    }
    
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) > 50) {
        $errors[] = "Username too long";
    } elseif ($username != $user['username']) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $user_id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username already taken";
        }
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } elseif ($email != $user['email']) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email already registered";
        }
    }
    if (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "New passwords don't match";
        }
    }
    
    if (empty($errors)) {
        $update_data = [
            'username' => $username,
            'email' => $email,
            'id' => $user_id
        ];
        
        $password_set = '';
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_data['password'] = $hashed_password;
            $password_set = ', password = :password';
        }
        
        $stmt = $db->prepare("UPDATE users SET username = :username, email = :email $password_set WHERE id = :id");
        $stmt->execute($update_data);
        
        $_SESSION['username'] = $username;
        
        $_SESSION['success'] = "Profile updated successfully!";
        redirect(isAdmin() ? '/admin_profile.php' : '/profile.php');
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
        redirect(isAdmin() ? '/admin_profile.php' : '/profile.php');
    }
} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred: " . $e->getMessage();
    redirect(isAdmin() ? '/admin_profile.php' : '/profile.php');
}
?>