<?php
require_once 'includes/config.php';

if (!isLoggedIn()) {
  redirect('/login.php');
}

if (isAdmin()) {
  redirect('/admin_dashboard.php');
}

$pageTitle = "User Dashboard";
require_once 'includes/header.php';
?>

<section class="dashboard">
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
  <p>
    This is your user dashboard.
  </p>
  <div class="dashboard-content" style="margin-top: 50px;">
    <div class="udd">
      <p style="
        text-align: left;
        font-size: .9em;
        ">
        Hello our user this <span style="color: #009900; font-weight: bold;">Sabian Secondary School</span> site has designed for students book library system. Our site have over 1000 books about highschool. Our books has selected by skilled teacher for support students achieve their goal.
      </p>
      <a class="btn" style="width: 300px; margin-top: 30px;" href="books.php">Explore library</a>
    </div>
    <div class="desc-boxs" style="justify-content: center; margin-block: 50px 80px;">
      <a href="books.php" class="box">
        <img src="images/book.png" alt="">
        <p>
          1000+ Books
        </p>
      </a>
      <a href="books.php" class="box">
        <img src="images/graduated.png" alt="">
        <p>
          100+ Extreme
        </p>
      </a>
      <a href="books.php" class="box">
        <img src="images/users.png" alt="">
        <p>
          10+ Subjuct
        </p>
      </a>
      <a href="categories.php" class="box">
        <img src="images/users.png" alt="">
        <p>
          10+ Category
        </p>
      </a>
    </div>
  </section>

  <?php
  require_once 'includes/footer.php';
  ?>