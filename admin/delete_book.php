<?php
require_once '../includes/config.php';

if (!isAdmin()) {
  redirect('../index.php');
}

if (!isset($_GET['id'])) {
  redirect('/admin/books.php');
}

$id = (int)$_GET['id'];

try {
  $stmt = $db->prepare("SELECT id FROM books WHERE id = ?");
  $stmt->execute([$id]);

  if (!$stmt->fetch()) {
    throw new Exception("books not found");
  }

  $stmt = $db->prepare("DELETE FROM books WHERE id = ?");
  $stmt->execute([$id]);

  $_SESSION['success'] = "book deleted successfully!";
} catch (Exception $e) {
  $_SESSION['error'] = "Failed to delete books: " . $e->getMessage();
}

redirect('/admin/books.php');
?>