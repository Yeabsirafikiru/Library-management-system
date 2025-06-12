<?php
require_once 'includes/config.php';
$pageTitle = "Home";
require_once 'includes/header.php';
?>

<section class="hero">
  <div class="hero-grid">
    <div class="hero-image">
      <img src="images/hero.jpg">
      <div class="option">
        <ul>
          <li>Learning</li>
          <li>Practicing</li>
          <li>Skill</li>
          <li>Oportunity</li>
          <li>Achievement</li>
        </ul>
      </div>
    </div>
    <div class="hero-description">
      <h1 style="line-height: 1; margin-bottom: 10px;">
        Welcome to <span>Sabian Secondary School</span>
      </h1>
      <q>Supportive and challenging learning environment!</q>
      <p>
        cillum velit aliqua incididunt voluptate ea consequat veniam proident nostrud commodo aliqua nostrud incididunt anim consectetur irure velit do consequat sunt nisi in nisi exercitation et labore amet sunt consequat labore commodo commodo in non quis
      </p>

      <div class="desc-boxs">
        <a href="dashboard.php" class="box">
          <img src="images/graduated.png" alt="">
          <p>
            50+ Teachers
          </p>
        </a>
        <a href="books.php" class="box">
          <img src="images/book.png" alt="">
          <p>
            1000+ Books
          </p>
        </a>
        <a href="categories.php" class="box">
          <img src="images/category.png" alt="">
          <p>
            10+ Category
          </p>
        </a>
        <a href="dashboard.php" class="box">
          <img src="images/users.png" alt="">
          <p>
            2100+ Students
          </p>
        </a>
      </div>
    </div>
  </div>
  <?php if (!isLoggedIn()): ?>
  <div class="login-links">
    <div class="login-box">
      <div>
        <img src="images/admin.png">
      </div>
      <h2>admin/Teachers</h2>
      <p>
        Full controle over site
      </p>
      <a href="/admin_login.php">Login</a>
    </div>
    <div class="login-box">
      <img src="images/user_login.png">
      <h2>Students</h2>
      <p>
        Less controle over site
      </p>
      <a href="/login.php">Login</a>
    </div>
    <div class="login-box">
      <img src="images/user_signup.png">
      <h2>Student/Teacher</h2>
      <p>
        Register as normal user
      </p>
      <a class="signup" href="/register.php">Singup</a>
    </div>
  </div>
  <?php endif; ?>
</section>


<?php
require_once 'includes/footer.php';
?>