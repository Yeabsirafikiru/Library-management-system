<?php
require_once 'includes/config.php';

if (!isLoggedIn()) {
  redirect('/login.php');
}

try {
  $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$_SESSION['user_id']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    throw new Exception("User not found");
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  redirect('/dashboard.php');
}

$pageTitle = "My Profile";
require_once 'includes/header.php';
?>

  <div class="form-container">
<section class="profile">
  <h1>My Profile</h1>

  <?php if (isset($_SESSION['success'])): ?>
  <div class="alert success">
    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
  </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert error">
    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
  </div>
  <?php endif; ?>

    <div class="profile-info">
      <form action="/update_profile.php" method="POST">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
          <label for="current_password">Current Password (to confirm changes):</label>
          <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
          <label for="new_password">New Password (leave blank to keep current):</label>
          <input type="password" id="new_password" name="new_password">
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password:</label>
          <input type="password" id="confirm_password" name="confirm_password">
        </div>

        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

        <button type="submit" class="btn">Update Profile</button>
      </form>
    </div>
  </div>
</section>