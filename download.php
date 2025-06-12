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
    
    $db->exec("UPDATE books SET downloads = downloads + 1 WHERE id = $book_id");
    
    $file_path = __DIR__ . '/' . $book['file_path'];
    
    if (!file_exists($file_path)) {
        throw new Exception("File not found");
    }
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($book['title']) . '.pdf"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    redirect('/books.php');
}
?>