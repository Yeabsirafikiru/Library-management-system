<?php
session_start();

define('BASE_DIR', dirname(__DIR__));
define('DB_FILE', BASE_DIR . '/includes/database.db');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); 
define('ALLOWED_BOOK_TYPES', ['application/pdf']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
try {
    $db = new PDO('sqlite:' . DB_FILE);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("PRAGMA foreign_keys = ON");
} catch (PDOException $e) {
    die("Database connection failed. Please contact administrator.");
}
function validateFileUpload($file, $allowedTypes, $maxSize) {
    $errors = [];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error";
        return $errors;
    }
    if ($file['size'] > $maxSize) {
        $errors[] = "File too large (max " . ($maxSize / 1024 / 1024) . "MB)";
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    
    if (!in_array($mime, $allowedTypes)) {
        $errors[] = "Invalid file type";
    }
    
    return $errors;
}
function secureDownload($filePath, $originalName) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $filePath = realpath($filePath);
    $baseDir = realpath(__DIR__ . '/uploads');
    
    if (strpos($filePath, $baseDir) !== 0) {
        return false;
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($originalName) . '.pdf"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    
    ob_clean();
    flush();
    
    readfile($filePath);
    return true;
}
function redirect($url, $statusCode = 303) {
    header("Location: $url", true, $statusCode);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['is_admin'];
}
?>