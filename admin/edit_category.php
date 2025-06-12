<?php
require_once '../includes/config.php';

if (!isAdmin()) {
  redirect('../index.php');
}

if (!isset($_GET['id'])) {
  redirect('/admin/categories.php');
}

$id = (int)$_GET['id'];
$errors = [];
$category = null;

try {
  $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
  $stmt->execute([$id]);
  $category = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$category) {
    throw new Exception("Category not found");
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  redirect('/admin/categories.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);

  if (empty($name)) {
    $errors[] = 'Category name is required';
  }

  if (strlen($name) > 100) {
    $errors[] = 'Category name must be less than 100 characters';
  }

  if (empty($errors)) {
    try {
      $stmt = $db->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
      $stmt->execute([$name, $description, $id]);

      $_SESSION['success'] = "Category updated successfully!";
      redirect('/admin/categories.php');
    } catch (PDOException $e) {
      $errors[] = "Failed to update category: " . $e->getMessage();
    }
  }
} else {
  $name = $category['name'];
  $description = $category['description'];
}

$pageTitle = "Edit Category";
require_once '../includes/header.php';
?>
<div class="form-container">

  <section class="admin-form">
    <h1>Edit Category</h1>

    <?php if (!empty($errors)): ?>
    <div class="alert error">
      <?php foreach ($errors as $error): ?>
      <p>
        <?php echo $error; ?>
      </p>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn">Update</button>
        <a href="/admin/categories.php" class="btn secondary">Cancel</a>
      </div>
    </form>
  </section>
</div>