<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
  redirect('../index.php');
}

$totalCategories = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();

$pageTitle = "Manage Categories";
require_once __DIR__ . '/../includes/header.php';

try {
  $stmt = $db->query("SELECT * FROM categories ORDER BY name");
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = "Failed to load categories: " . $e->getMessage();
}

?>

<section class="admin-categories dashboard">
  <h1>Manage Book Categories</h1>
<div class="stats-grid">
  
<div class="stat-card">
  <a href="categories.php" class="stat-card">
    <img src="../images/category.png" alt="">
    <h3>Total Categories</h3>
    <p>
      <?php echo $totalCategories; ?>
    </p>
  </a>
  
</div>
</div>

  <?php if (isset($_SESSION['success'])): ?>
  <div class="alert success">
    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
  </div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
  <div class="alert error">
    <?php echo $error; ?>
  </div>
  <?php endif; ?>

  <div class="actions">
    <a href="/admin/add_category.php" class="btn" style="margin-block: 50px -30px;">Add New Category</a>
  </div>

  <div class="table-responsive category-table">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
        <tr>
          <td><?php echo $category['id']; ?></td>
          <td class="username" style="font-weight: bold;"><?php echo htmlspecialchars($category['name']); ?></td>
          <td><p class="olaps" style="width: 300px; overflow: auto;">
            <?php echo htmlspecialchars($category['description']); ?>
          </p>
          </td>
          <td class="actions">
            <a href="/admin/edit_category.php?id=<?php echo $category['id']; ?>" class="small" style="background: yellow;">Edit</a>
            <a href="/admin/delete_category.php?id=<?php echo $category['id']; ?>"
              class="small danger"
              onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else : ?>
        <tr>
          <td colspan="4">No categories found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php
require_once '../includes/footer.php';
?>