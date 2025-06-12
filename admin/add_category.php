<?php
require_once '../includes/config.php';

if (!isAdmin()) {
  redirect('../index.php');
}

$errors = [];
$name = $description = '';

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
      $stmt = $db->prepare("INSERT INTO categories (name, description, created_by) VALUES (?, ?, ?)");
      $stmt->execute([$name, $description, $_SESSION['user_id']]);

      $_SESSION['success'] = "Category added successfully!";
      redirect('/admin/categories.php');
    } catch (PDOException $e) {
      $errors[] = "Failed to add category: " . $e->getMessage();
    }
  }
}

$pageTitle = "Add New Category";
require_once '../includes/header.php';
?>
<div class="form-container">
  
<section class="admin-form">
  <h1>Add New Category</h1>

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
      <button type="submit" class="btn">Save</button>
      <a href="/admin/categories.php" class="btn secondary">Cancel</a>
    </div>
  </form>
</section>
</div>
