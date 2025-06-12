<?php
require_once __DIR__ . '/includes/config.php';
if (!isLoggedIn()) {
  redirect('/login.php');
}
$pageTitle = "Our Book Collection";
require_once __DIR__ . '/includes/header.php';

$search = $_GET['search'] ?? '';
$category_id = $_GET['category_id'] ?? null;

try {
  $query = "SELECT b.*, c.name as category_name FROM books b
              LEFT JOIN categories c ON b.category_id = c.id
              WHERE 1=1";

  $params = [];

  if (!empty($search)) {
    $query .= " AND (b.title LIKE ? OR b.author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
  }

  if ($category_id && is_numeric($category_id)) {
    $query .= " AND b.category_id = ?";
    $params[] = $category_id;
  }

  $query .= " ORDER BY b.uploaded_at DESC";

  $categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

  $stmt = $db->prepare($query);
  $stmt->execute($params);
  $books = $stmt->fetchAll();

} catch (PDOException $e) {
  $error = "Failed to load books: " . $e->getMessage();
}
?>

<section class="book-listing">
  <div class="options">

    <h1>Book Collection</h1>
    <div class="book-filters">
      <form method="get" class="search-form">
        <input type="text" name="search" placeholder="Search books..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
      </form>

      <div class="horizontal-categories">
        <a href="books.php" class="category-tag <?= empty($category_id) ? 'active' : '' ?>">All Categories</a>
        <?php foreach ($categories as $cat): ?>
        <a href="books.php?category_id=<?= $cat['id'] ?>"
          class="category-tag <?= $category_id == $cat['id'] ? 'active' : '' ?>">
          <?= htmlspecialchars($cat['name']) ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php if (!empty($books)): ?>
  <div class="book-grid">
    <?php foreach ($books as $book): ?>
    <div class="book-card">
      <div class="book-cover">
        <?php if ($book['cover_image']): ?>
        <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        <?php else : ?>
        <div class="default-cover">
          <i class="fas fa-book-open"></i>
        </div>
        <?php endif; ?>
      </div>
      <div class="book-info">
        <h3><?= htmlspecialchars($book['title']) ?></h3>
        <p class="author">
          By <?= htmlspecialchars($book['author']) ?>
        </p>
        <?php if ($book['category_name']): ?>
        <span class="category"><?= htmlspecialchars($book['category_name']) ?></span>
        <?php endif; ?>
        <div class="book-actions">
          <a href="view.php?id=<?= $book['id'] ?>" style="color: #009900; text-decoration: underline;">Preview</a>
          <a href="read.php?id=<?= $book['id'] ?>" style="color: #009900; text-decoration: underline; margin-left: 10px;">Read</a>
          <a href="download.php?id=<?= $book['id'] ?>" class="bt" style="display: block; margin: auto; border-radius: 10px; background: #009900; padding: 5px 10px; color: white;">Download</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else : ?>
  <p class="no-books">
    No books found. <?= !empty($search) ? 'Try a different search.' : '' ?>
  </p>
  <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>