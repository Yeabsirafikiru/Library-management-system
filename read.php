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
    $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();
    
    if (!$book) {
        throw new Exception("Book not found");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    redirect('/books.php');
}

$pageTitle = "Reading: " . htmlspecialchars($book['title']);
require_once __DIR__ . '/includes/header.php';
?>

<section class="book-reader">
    <div class="reader-header">
        <h1><?= htmlspecialchars($book['title']) ?></h1>
        <p class="author">By <?= htmlspecialchars($book['author']) ?></p>
        
        <div class="reader-controls">
            <a href="view.php?id=<?= $book['id'] ?>" class="btn">Back to Preview</a>
            <a href="download.php?id=<?= $book['id'] ?>" class="btn">Download</a>
        </div>
    </div>
    
    <div class="reader-container">
        <embed src="<?= htmlspecialchars($book['file_path']) ?>" type="application/pdf" width="100%" height="100%">
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>