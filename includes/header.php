<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? $pageTitle : 'My Website'; ?></title>
  <link rel="stylesheet" href="/assets/css/styles.css">
  <link rel="stylesheet" href="/assets/css/index.css">
  <link rel="stylesheet" href="/assets/css/home.css">
  <link rel="stylesheet" href="/assets/css/login.css">
  <link rel="stylesheet" href="/assets/css/dashboard.css">
  <link rel="stylesheet" href="/assets/css/book.css">
  <script src="../assets/js/script.js" defer></script>
</head>
<body>
  <header>
    <a class="logo" href="#">
      <span>
        Sabian
      </span>
      <span class="dot"></span>
    </a>
    <nav class="nav">
      <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
      </div>
      <ul class="nav-list">
        <li><a class="nav-links" href="/index.php">Home</a></li>
        <?php if (isLoggedIn()): ?>
        <?php if (isAdmin()): ?>
        <li><a class="nav-links" href="../admin_dashboard.php">Dashboard</a></li>
        <li><a class="nav-links" href="/categories.php">Categories</a></li>
        <?php else : ?>
        <li><a class="nav-links" href="../dashboard.php">Dashboard</a></li>
        <li><a class="nav-links" href="/categories.php">Categories</a></li>
        <?php endif; ?>
        <li><a class="nav-links" href="../books.php">Library</a></li>
        <li><a class="nav-links" href="../profile.php">Profile</a></li>
        <li><a class="nav-links" href="/logout.php">Logout</a></li>
        <?php else : ?>
        <li><a class="nav-links" href="/login.php">Login</a></li>
        <li><a class="nav-links" href="/admin_login.php">Admin Login</a></li>
        <li><a class="nav-links" href="/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>