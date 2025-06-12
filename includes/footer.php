<link rel="stylesheet" href="../assets/css/footer.css">
<footer class="footer" role="contentinfo" aria-label="Website footer">
  <div class="footer-content">
    <div class="contact">
      <a class="logof" href="#" aria-label="Sabian School Home">
        <span>
          Sabian
        </span>
        <span class="dotf"></span>
      </a>
      <p>
        Sabian Secondary and Preparatory School
      </p>
      <div>
        <img src="../images/call_60dp_17E800_FILL1_wght700_GRAD0_opsz48.svg" alt="Phone icon">
        <span>+251-70322-3408</span>
      </div>
      <div>
        <img src="../images/mail_60dp_17E800_FILL1_wght700_GRAD0_opsz48.svg" alt="Email icon">
        <span>SabianSS@gmail.com</span>
      </div>
      <div class="social">
        <a href="https://facebook.com" aria-label="Facebook"><img src="../images/facebook.png" alt="Facebook"></a>
        <a href="https://telegram.org" aria-label="Telegram"><img src="../images/telegram.png" alt="Telegram"></a>
        <a href="https://twitter.com" aria-label="Twitter"><img src="../images/twitter.png" alt="Twitter"></a>
      </div>
    </div>
    <div class="our-links">
      <h3>Our Links</h3>
      <ul>
        <li><a href="../index.php">Home</a></li>
        <li><a href="../dashboard.php">Dashboard</a></li>
        <li><a href="../books.php">Library</a></li>
        <li><a href="../profile.php">Profile</a></li>
        <li><a href="../categories.php">Category</a></li>
      </ul>
    </div>
    <div class="service">
      <h3>Our Service</h3>
      <ul>
        <li><a href="#">Grade 9</a></li>
        <li><a href="#">Grade 10</a></li>
        <li><a href="#">Grade 11</a></li>
        <li><a href="#">Grade 12</a></li>
        <li><a href="../books.php">Library</a></li>
      </ul>
    </div>
  </div>
  <div class="newsletter">
    <h2>Newsletter</h2>
    <p>
      Subscribe to our newsletter for the latest updates
    </p>
    <form action="#" method="POST">
      <div>
        <input type="email" name="email" id="email" placeholder="Your email address" required>
        <button type="submit">Send</button>
      </div>
    </form>
    <button class="back-to-top" style="position: fixed; right: 10px; padding-top: 10px; bottom: 20px; width: 50px; height: 50px; font-size: 1.8em; display:grid; place-content: center; background: greenyellow; border: none; outline: none; border-radius: 50%;">
      ^
    </button>
  </div>
  <p style="font-size: .5em;">
    &copy; <?php echo date('Y'); ?> Sabian Secondary and Preparatory School. All rights reserved.
  </p>
</footer>

<script>
  const backToTopBtn = document.querySelector('.back-to-top');
  backToTopBtn.style.display = 'none';
  document.body.appendChild(backToTopBtn);

  window.addEventListener('scroll', () => {
  if (window.pageYOffset > 500) {
  backToTopBtn.style.display = 'block';
  backToTopBtn.style.opacity = '1';
  } else {
  backToTopBtn.style.opacity = '0';
  setTimeout(() => {
  backToTopBtn.style.display = 'none';
  }, 300);
  }
  });
  backToTopBtn.addEventListener('click', () => {
  window.scrollTo({
  top: 0,
  behavior: 'smooth'
  });
  });
</script>
</body>
</html>
