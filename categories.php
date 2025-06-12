<?php
require_once __DIR__ . '/includes/config.php';

$pageTitle = "Book Categories";
require_once __DIR__ . '/includes/header.php';
if (!isLoggedIn()) {
  redirect('/login.php');
}
try {
  $stmt = $db->query("SELECT * FROM categories ORDER BY name");
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = "Failed to load categories: " . $e->getMessage();
}
?>

<section class="categories">
  <h1>Book Categories</h1>

  <?php if (isset($error)): ?>
  <div class="alert error">
    <?php echo $error; ?>
  </div>
  <?php endif; ?>

  <div class="category-list">
    <?php if (!empty($categories)): ?>
    <?php foreach ($categories as $category): ?>
    <div class="category-card">
      <h3><?php echo htmlspecialchars($category['name']); ?></h3>
      <p class="olaps">
        <?php echo htmlspecialchars($category['description']); ?>
      </p>
    </div>
    <?php endforeach; ?>
    <?php else : ?>
    <p class="category-card" style="margin-bottom: 100px;">
      No categories found.
    </p>
    <?php endif; ?>
  </div>

  <?php if (isAdmin()): ?>
  <div class="admin-actions">
    <a style="max-width: 200px; margin: 30px auto 50px; padding: 15px 5px;" href="/admin/categories.php" class="btn">Manage Categories</a>
  </div>
  <?php endif; ?>
</section>

<?php
require_once 'includes/footer.php';
?>