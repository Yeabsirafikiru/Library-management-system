<?php
require_once __DIR__ . '/includes/config.php';

if (!isset($_GET['id'])) {
    redirect('/books.php');
}
if (!isLoggedIn()) {
  redirect('/login.php');
}
$book_id = (int)$_GET['id'];

try {
    $db->exec("UPDATE books SET views = views + 1 WHERE id = $book_id");
    
    // Get main book details
    $stmt = $db->prepare("SELECT b.*, c.name as category_name FROM books b 
                         LEFT JOIN categories c ON b.category_id = c.id 
                         WHERE b.id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();
    
    if (!$book) {
        throw new Exception("Book not found");
    }
    
    // Get related books (same category)
    $related_stmt = $db->prepare("SELECT b.* FROM books b 
                                WHERE b.category_id = ? AND b.id != ? 
                                ORDER BY RAND() LIMIT 4");
    $related_stmt->execute([$book['category_id'], $book_id]);
    $related_books = $related_stmt->fetchAll();
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    redirect('/books.php');
}

$pageTitle = "Preview: " . htmlspecialchars($book['title']);
require_once __DIR__ . '/includes/header.php';
?>

<section class="book-preview">
    <div class="book-header">
        <?php if ($book['cover_image']): ?>
            <img src="<?= htmlspecialchars($book['cover_image']) ?>" class="book-cover-large">
        <?php endif; ?>
        
        <div class="book-meta">
            <h1><?= htmlspecialchars($book['title']) ?></h1>
            <p class="author">By <?= htmlspecialchars($book['author']) ?></p>
            
            <?php if ($book['category_name']): ?>
                <p class="category">Category: <?= htmlspecialchars($book['category_name']) ?></p>
            <?php endif; ?>
            
            <p class="views"><?= $book['views'] ?> views</p>
            
            <div class="action-buttons">
                <a href="read.php?id=<?= $book['id'] ?>" class="btn">Read Now</a>
                <a href="download.php?id=<?= $book['id'] ?>" class="btn">Download</a>
                <a href="books.php" class="btn secondary">Back to Collection</a>
            </div>
        </div>
    </div>
    
    <div class="book-description">
        <h2>Description</h2>
        <p><?= nl2br(htmlspecialchars($book['description'] ?? 'No description available')) ?></p>
    </div>
    
    <div class="book-preview-content">
        <h2>Preview</h2>
        <div class="pdf-preview">
            <embed src="<?= htmlspecialchars($book['file_path']) ?>#toolbar=0&navpanes=0&scrollbar=0&view=FitH" type="application/pdf" width="100%" height="600px">
        </div>
    </div>
</section>

<!-- Add related books section -->
<?php if (!empty($related_books)): ?>
<section class="related-books">
    <h2>You Might Also Like</h2>
    <div class="book-grid">
        <?php foreach ($related_books as $related): ?>
        <div class="book-card">
            <div class="book-cover">
                <?php if ($related['cover_image']): ?>
                <img src="<?= htmlspecialchars($related['cover_image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>">
                <?php else : ?>
                <div class="default-cover">
                    <i class="fas fa-book-open"></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="book-info">
                <h3><?= htmlspecialchars($related['title']) ?></h3>
                <p class="author">By <?= htmlspecialchars($related['author']) ?></p>
                <div class="book-actions">
                    <a href="view.php?id=<?= $related['id'] ?>" class="btn small">Preview</a>
                    <a href="read.php?id=<?= $related['id'] ?>" class="btn small">Read</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>