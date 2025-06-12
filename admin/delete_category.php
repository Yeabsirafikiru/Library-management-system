<?php
require_once '../includes/config.php';

if (!isAdmin()) {
    redirect('../index.php');
}

if (!isset($_GET['id'])) {
    redirect('/admin/categories.php');
}

$id = (int)$_GET['id'];

try {
    $stmt = $db->prepare("SELECT id FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    
    if (!$stmt->fetch()) {
        throw new Exception("Category not found");
    }
    
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['success'] = "Category deleted successfully!";
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to delete category: " . $e->getMessage();
}

redirect('/admin/categories.php');
?>