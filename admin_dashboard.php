<?php
require_once 'includes/config.php';
define('BOSS_ADMIN_ID', 1);
if (!isAdmin()) {
  redirect('/index.php');
}
try {
  $totalBooks = $db->query("SELECT COUNT(*) FROM books")->fetchColumn();

  $totalStudents = $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 0")->fetchColumn();

  $totalTeachers = $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 1 AND id != " . BOSS_ADMIN_ID)->fetchColumn();

  $totalCategories = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();

  $stmt = $db->query("SELECT id, username, email, created_at, is_admin FROM users ORDER BY created_at DESC");
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  $error = 'Failed to fetch data: ' . $e->getMessage();
  $users = [];
  $totalBooks = $totalStudents = $totalTeachers = $totalCategories = 0;
}

if (isset($_POST['delete_user'])) {
  $userId = $_POST['user_id'] ?? null;

  if ($userId && is_numeric($userId)) {
    try {
      if ($userId != $_SESSION['user_id'] && $userId != BOSS_ADMIN_ID) {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $_SESSION['message'] = "User deleted successfully";
        redirect('/admin_dashboard.php');
      } else {
        $error = "You cannot delete this account";
      }
    } catch (PDOException $e) {
      $error = "Failed to delete user: " . $e->getMessage();
    }
  } else {
    $error = "Invalid user ID";
  }
}

if (isset($_POST['change_role']) && $_SESSION['user_id'] == BOSS_ADMIN_ID) {
  $userId = $_POST['user_id'] ?? null;
  $newRole = $_POST['new_role'] ?? null;

  if ($userId && is_numeric($userId) && in_array($newRole, ['0', '1'])) {
    try {
      if ($userId != BOSS_ADMIN_ID) {
        $stmt = $db->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
        $stmt->execute([$newRole, $userId]);
        $_SESSION['message'] = "User role updated successfully";
        redirect('/admin_dashboard.php');
      } else {
        $error = "Cannot change admin's role";
      }
    } catch (PDOException $e) {
      $error = "Failed to update user role: " . $e->getMessage();
    }
  } else {
    $error = "Invalid request";
  }
} elseif (isset($_POST['change_role'])) {
  $error = "Only admin can change user roles";
}

$pageTitle = "Admin Dashboard";
require_once 'includes/header.php';
?>

<section class="dashboard">
  <h1>Admin Dashboard</h1>
  <p>
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
  </p>
  <p>
  </p>
  <?php if (isset($_SESSION['message'])): ?>
  <div class="alert success">
    <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
  </div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
  <div class="alert error">
    <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <div class="stats-grid">
    <a href="admin/books.php" class="stat-card">
      <img src="images/book.png" alt="">
      <h3>Total Books</h3>
      <p>
        <?php echo $totalBooks; ?>
      </p>
    </a>
    <a href="#user" class="stat-card">
      <img src="images/users.png" alt="">
      <h3>Total Students</h3>
      <p>
        <?php echo $totalStudents; ?>
      </p>
    </a>
    <a href="#user" class="stat-card">
      <img src="images/graduated.png" alt="">
      <h3>Total Teachers</h3>
      <p>
        <?php echo $totalTeachers; ?>
      </p>
    </a>
    <a href="categories.php" class="stat-card">
      <img src="images/category.png" alt="">
      <h3>Categories</h3>
      <p>
        <?php echo $totalCategories; ?>
      </p>
    </a>
  </div>
  <?php if ($user['id'] != BOSS_ADMIN_ID): ?>
  <?php if ($_SESSION['user_id'] == BOSS_ADMIN_ID): ?>
  <h2 id="user">User Management</h2>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Registered</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
          <td><?php echo htmlspecialchars($user['id']); ?></td>
          <td class="username"><?php echo htmlspecialchars($user['username']); ?>
            <?php if ($user['id'] == BOSS_ADMIN_ID): ?>
            <span class="admin-badge">ADMIN</span>
            <?php elseif ($user['is_admin']): ?>
            <span class="teacher-badge">TEACHER</span>
            <?php else : ?>
            <span class="student-badge">STUDENT</span>
            <?php endif; ?>
          </td>
          <td><?php echo htmlspecialchars($user['email']); ?></td>
          <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
          <td>
            <?php if ($user['id'] == BOSS_ADMIN_ID): ?>
            Admin
            <?php elseif ($user['is_admin']): ?>
            Teacher
            <?php else : ?>
            Student
            <?php endif; ?>
          </td>
          <td class="actions">
            <?php if ($user['id'] != BOSS_ADMIN_ID): ?>
            <?php if ($_SESSION['user_id'] == BOSS_ADMIN_ID): ?>
            <form method="post" class="role-form">
              <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
              <select name="new_role" onchange="this.form.submit()" <?php echo ($user['id'] == $_SESSION['user_id']) ? 'disabled' : ''; ?>>
                <option value="0" <?php echo !$user['is_admin'] ? 'selected' : ''; ?>>Student</option>
                <option value="1" <?php echo $user['is_admin'] ? 'selected' : ''; ?>>Teacher</option>
              </select>
              <input type="hidden" name="change_role" value="1">
            </form>
            <?php endif; ?>

            <?php if ($user['id'] != $_SESSION['user_id']): ?>
            <form method="post" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this user?');">
              <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
              <button type="submit" name="delete_user" class="btn-danger">Delete</button>
            </form>
            <?php endif; ?>
            <?php else : ?>
            <span class="no-actions">Immutable</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</section>
<?php
require_once 'includes/footer.php';
?>