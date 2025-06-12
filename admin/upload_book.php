<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
  redirect('../index.php');
}

$pageTitle = "Upload Book";
require_once __DIR__ . '/../includes/header.php';
try {
  $categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = "Failed to load categories: " . $e->getMessage();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $author = trim($_POST['author']);
  $description = trim($_POST['description']);
  $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;

  $errors = [];

  if (empty($title)) {
    $errors[] = "Title is required";
  }

  if (empty($author)) {
    $errors[] = "Author is required";
  }

  $file_path = '';
  $cover_image = '';

  try {
    if (isset($_FILES['book_file']) && $_FILES['book_file']['error'] === UPLOAD_ERR_OK) {
      $file_ext = pathinfo($_FILES['book_file']['name'], PATHINFO_EXTENSION);
      $file_name = uniqid('book_', true) . '.' . $file_ext;
      $upload_dir = __DIR__ . '/../uploads/books/';

      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
      }

      $file_path = $upload_dir . $file_name;

      if (!move_uploaded_file($_FILES['book_file']['tmp_name'], $file_path)) {
        throw new Exception("Failed to move uploaded file");
      }

      $file_path = 'uploads/books/' . $file_name;
    } else {
      $errors[] = "Book file is required";
    }

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
      $image_ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
      $image_name = uniqid('cover_', true) . '.' . $image_ext;
      $upload_dir = __DIR__ . '/../uploads/covers/';

      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
      }

      $image_path = $upload_dir . $image_name;

      if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $image_path)) {
        throw new Exception("Failed to move cover image");
      }

      $cover_image = 'uploads/covers/' . $image_name;
    }

    if (empty($errors)) {
      $stmt = $db->prepare("INSERT INTO books (title, author, description, category_id, file_path, cover_image, uploaded_by)
      VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([
      $title,
      $author,
      $description,
      $category_id,
      $file_path,
      $cover_image,
      $_SESSION['user_id']
      ]);

      $_SESSION['success'] = "Book uploaded successfully!";
      redirect('/admin/books.php');
    }
  } catch (Exception $e) {
    $errors[] = $e->getMessage();
  }
}
?>
<div class="form-container">

  <section class="admin-form">
    <h1>Upload New Book</h1>

    <?php if (!empty($errors)): ?>
    <div class="alert error">
      <?php foreach ($errors as $error): ?>
      <p>
        <?php echo htmlspecialchars($error); ?>
      </p>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">Title:*</label>
        <input type="text" id="title" name="title" required>
      </div>

      <div class="form-group">
        <label for="author">Author:*</label>
        <input type="text" id="author" name="author" required>
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"></textarea>
      </div>

      <div class="form-group">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id">
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $category): ?>
          <option value="<?php echo $category['id']; ?>">
            <?php echo htmlspecialchars($category['name']); ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="book_file">Book File (PDF):*</label>
        <input type="file" id="book_file" name="book_file" accept=".pdf" required>
      </div>

      <div class="form-group">
        <label for="cover_image">Cover Image (optional):</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/*">
      </div>

      <div class="form-actions">
        <button type="submit" class="btn">Upload</button>
        <a href="/admin/books.php" class="btn secondary">Cancel</a>
      </div>
    </form>
  </section>
</div>