<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
  redirect('../index.php');
}
$totalBooks = $db->query("SELECT COUNT(*) FROM books")->fetchColumn();
$pageTitle = "Manage Books";
require_once __DIR__ . '/../includes/header.php';

try {
  $stmt = $db->query("
  SELECT b.*, c.name as category_name, u.username as uploaded_by_name
  FROM books b
  LEFT JOIN categories c ON b.category_id = c.id
  LEFT JOIN users u ON b.uploaded_by = u.id
  ORDER BY b.uploaded_at DESC
  ");
  $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = "Failed to load books: " . $e->getMessage();
}
?>

<section class="admin-books dashboard">
  <h1>Manage Books</h1>
  <div class="stats-grid">
    <a href="../books.php" class="stat-card">
      <img src="../images/book.png" alt="">
      <h3>Total Books</h3>
      <p>
        <?php echo $totalBooks; ?>
      </p>
    </a>
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
    <a href="/admin/upload_book.php" class="btn">Upload New Book</a>
  </div>

  <div class="books-table table-responsive">
    <table style="min-width: 750px;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Author</th>
          <th>Category</th>
          <th>By</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
        <tr>
          <td><?php echo $book['id']; ?></td>
          <td><?php echo htmlspecialchars($book['title']); ?></td>
          <td><?php echo htmlspecialchars($book['author']); ?></td>
          <td><?php echo htmlspecialchars($book['category_name'] ?? 'N/A'); ?></td>
          <td><?php echo htmlspecialchars($book['uploaded_by_name']); ?></td>
          <td><?php echo date('M j, Y', strtotime($book['uploaded_at'])); ?></td>
          <td class="actions" style="padding: 0px 45px;">
            <a href="../edit_book.php?id=<?php echo $book['id']; ?>" class="up" style="background: #ffff00; color: #009900;">Edit</a>
            <a href="/admin/delete_book.php?id=<?php echo $book['id']; ?>"
              class="up" style="background: #ff0000;"
              onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
            <a href="/download.php?id=<?php echo $book['id']; ?>" class="up">Download</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else : ?>
        <tr>
          <td colspan="7">No books found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
