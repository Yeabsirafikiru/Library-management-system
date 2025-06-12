<?php
require_once 'includes/config.php';

if (!isAdmin()) {
  redirect('/index.php');
}

if (!isset($_GET['id'])) {
  redirect('/admin/books.php');
}

$id = (int)$_GET['id'];
$errors = [];
$book = null;

try {
  $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
  $stmt->execute([$id]);
  $book = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$book) {
    throw new Exception("Book not found");
  }

  // Get all categories for the dropdown
  $categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  redirect('/admin/books.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $author = trim($_POST['author'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;

  if (empty($title)) {
    $errors[] = 'Book title is required';
  }

  if (strlen($title) > 100) {
    $errors[] = 'Book title must be less than 100 characters';
  }

  if (empty($errors)) {
    try {
      $stmt = $db->prepare("UPDATE books SET title = ?, author = ?, category_id = ?, description = ? WHERE id = ?");
      $stmt->execute([$title, $author, $category_id, $description, $id]);

      $_SESSION['success'] = "Book updated successfully!";
      redirect('/admin/books.php');
    } catch (PDOException $e) {
      $errors[] = "Failed to update book: " . $e->getMessage();
    }
  }
} else {
  $title = $book['title'];
  $description = $book['description'];
  $author = $book['author'];
  $category_id = $book['category_id'];
}

$pageTitle = "Edit Book";
require_once "includes/header.php";
?>

<?php if (!empty($errors)): ?>
<div class="alert error">
  <?php foreach ($errors as $error): ?>
  <p>
    <?php echo htmlspecialchars($error); ?>
  </p>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="form-container">
  <section class="admin-form">
    <h1>Edit Book</h1>

    <form method="POST">
      <div class="form-group">
        <label for="title">Title:*</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
      </div>

      <div class="form-group">
        <label for="author">Author:*</label>
        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
      </div>

      <div class="form-group">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id">
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $category): ?>
          <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($category['name']); ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn">Update Book</button>
        <a href="/admin/books.php" class="btn secondary">Cancel</a>
      </div>
    </form>
  </section>
</div>