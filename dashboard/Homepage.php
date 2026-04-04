<?php 
session_start();
include(__DIR__ . '/../config/db_connection.php');
include(__DIR__ . '/../config/auth_functions.php');
include(__DIR__ . '/../config/helpers.php');

// Get all categories
$categories_result = $conn->query("SELECT category_id, name, image FROM categories ORDER BY category_id");
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Get selected category filter
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get products based on filter
if ($selected_category > 0) {
    $stmt = $conn->prepare("SELECT product_id, name, description, price, stock, image, category_id FROM products WHERE category_id = ? ORDER BY product_id LIMIT 20");
    $stmt->bind_param("i", $selected_category);
} else {
    $stmt = $conn->prepare("SELECT product_id, name, description, price, stock, image, category_id FROM products ORDER BY product_id LIMIT 20");
}
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home Page – Well Care Pharmacy</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --green: #2e8b57;
      --green-dark: #1a5c38;
      --green-light: #4caf7d;
      --green-btn: #3aaa6a;
      --bg: #f0f0f0;
      --white: #ffffff;
      --text: #111;
      --muted: #777;
      --border: #ddd;
      --price: #2e8b57;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    .navbar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08);
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .hamburger {
      display: flex;
      flex-direction: column;
      gap: 4px;
      cursor: pointer;
      padding: 4px;
    }

    .hamburger span {
      width: 20px;
      height: 2px;
      background: var(--text);
      border-radius: 2px;
      display: block;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    .brand-logo {
      height: 38px;
      width: auto;
      object-fit: contain;
    }

    .brand-name {
      font-family: 'DM Serif Display', serif;
      font-size: 15px;
      color: var(--green);
      letter-spacing: 0.01em;
    }

    .search-wrap {
      flex: 1;
      max-width: 600px;
      margin: 0 24px;
      position: relative;
    }

    .search-wrap input {
      width: 100%;
      padding: 9px 14px 9px 36px;
      border: 1px solid var(--border);
      border-radius: 24px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      background: #f9f9f9;
      outline: none;
      color: var(--text);
      transition: border-color 0.2s;
    }

    .search-wrap input:focus {
      border-color: var(--green);
      background: #fff;
    }

    .search-wrap svg {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 28px;
    }

    .nav-right a {
      color: var(--muted);
      text-decoration: none;
      position: relative;
      display: flex;
      align-items: center;
    }

    .nav-right a:hover {
      color: var(--green);
    }

    .badge {
      position: absolute;
      top: -7px;
      right: -9px;
      background: var(--green);
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .slider {
      width: 100%;
      height: 400px;
      position: relative;
      overflow: hidden;
    }

    .slides {
      display: flex;
      height: 100%;
      transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .slide {
      min-width: 100%;
      height: 100%;
      position: relative;
      display: flex;
      align-items: center;
      flex-shrink: 0;
    }

    .slide-1 {
      background: url('SlidersImage/Slide1.png') center/cover no-repeat;
    }

    .slide-2 {
      background: url('SlidersImage/Slide2.png') center/cover no-repeat;
    }

    .slide-3 {
      background: url('SlidersImage/Slide3.png') center/cover no-repeat;
    }

    .hero-text {
      padding: 0 48px;
      z-index: 2;
      position: relative;
    }

    .hero-text h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 26px;
      color: var(--green-dark);
      line-height: 1.2;
      margin-bottom: 8px;
    }

    .hero-text p {
      font-size: 13px;
      color: var(--green);
      margin-bottom: 14px;
    }

    .hero-btn {
      background: var(--green);
      color: #fff;
      border: none;
      padding: 10px 24px;
      border-radius: 24px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }

    .hero-btn:hover {
      background: var(--green-dark);
    }

    .slider-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.75);
      border: none;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 10;
      font-size: 18px;
      color: #333;
      transition: background 0.2s;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .slider-arrow:hover {
      background: rgba(255, 255, 255, 0.98);
    }

    .slider-prev {
      left: 16px;
    }

    .slider-next {
      right: 16px;
    }

    .slider-dots {
      position: absolute;
      bottom: 12px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 7px;
      z-index: 10;
    }

    .slider-dots button {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      border: none;
      background: rgba(0, 0, 0, 0.2);
      cursor: pointer;
      padding: 0;
      transition: background 0.25s, transform 0.25s;
    }

    .slider-dots button.active {
      background: var(--green);
      transform: scale(1.35);
    }

    .wrap {
      width: 100%;
      padding: 20px 32px 48px;
    }

    .sec-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }

    .sec-title {
      font-family: 'DM Serif Display', serif;
      font-size: 35px;
      color: var(--text);
    }

    .categories {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    .cat-card {
      background: var(--white);
      border-radius: 12px;
      padding: 20px 12px;
      text-align: center;
      border: 1px solid var(--border);
      cursor: pointer;
      transition: box-shadow 0.2s, transform 0.15s;
    }

    .cat-card:hover {
      box-shadow: 0 4px 16px rgba(46, 139, 87, 0.12);
      transform: translateY(-2px);
    }

    .cat-icon img {
      width: 350px;
      height: 350px;
      object-fit: contain;
    }

    .cat-card p {
      font-size: 30px;
      font-weight: 500;
      color: var(--text);
      line-height: 1.3;
    }

    .products {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    .prod-card {
      background: var(--white);
      border-radius: 12px;
      border: 1px solid var(--border);
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      transition: box-shadow 0.2s, transform 0.15s;
      cursor: pointer;
    }

    .prod-card:hover {
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
      transform: translateY(-2px);
    }

    .prod-card.featured {
      border-color: var(--green-light);
    }

    .prod-img-wrap {
      width: 50%;
      height: 500px;
      margin-left: 200px;
      background: #e3f2fd;
      border-radius: 10px;

      display: flex;
      align-items: center;
      justify-content: center;

      overflow: hidden;
    }

    .prod-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;

    }

    .prod-name {
      font-size: 20px;
      font-weight: 500;
      color: var(--text);
      line-height: 1.4;
    }

    .prod-price {
      font-family: 'DM Serif Display', serif;
      font-size: 35px;
      color: var(--price);
    }

    .prod-actions {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-cart {
      flex: 1;
      max-width: 450px;
      height: 50px;
      padding: 8px 20px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 5SSSSS00;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
    }

    .btn-cart:hover {
      border-color: var(--green);
      background: #f0faf5;
    }

    .btn-buy {
      width: 350px;
      height: 50px;
      padding: 8px 18px;
      border: none;
      border-radius: 8px;
      background: var(--green-btn);
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-buy:hover {
      background: var(--green-dark);
    }

    .btn-wish {
      width: 100px;
      height: 50px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 15px;
      transition: border-color 0.2s;
      flex-shrink: 0;
    }

    .btn-wish:hover {
      border-color: #e74c3c;
    }

    .deals-box {
      background: var(--white);
      border-radius: 12px;
      border: 1px solid var(--border);
      padding: 4px 20px;
      margin-bottom: 24px;
    }

    .deal-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 0;
      border-bottom: 1px solid var(--border);
    }

    .deal-row:last-child {
      border-bottom: none;
    }

    .deal-label {
      font-size: 20px;
      font-weight: 500;
      color: var(--text);
    }

    .btn-deal {
      background: var(--green-btn);
      color: #fff;
      border: none;
      padding: 8px 18px;
      border-radius: 20px;
      font-family: 'DM Sans', sans-serif;
      font-size: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
      white-space: nowrap;
    }

    .btn-deal:hover {
      background: var(--green-dark);
    }

    .faqs {
      margin-bottom: 24px;
    }

    .faq-item {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 14px 18px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      transition: border-color 0.2s;
    }

    .faq-item:hover {
      border-color: var(--green-light);
    }

    .faq-item span {
      font-size: 20px;
      font-weight: 500;
    }

    .faq-chevron {
      color: var(--muted);
      font-size: 10px;
      font-weight: 300;
    }

    .services {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    .svc-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 24px 14px;
      text-align: center;
      cursor: pointer;
      transition: box-shadow 0.2s, transform 0.15s;
    }

    .svc-card:hover {
      box-shadow: 0 4px 16px rgba(46, 139, 87, 0.12);
      transform: translateY(-2px);
    }

    .svc-icon {
      font-size: 30px;
      margin-bottom: 10px;
    }

    .svc-card p {
      font-size: 20px;
      font-weight: 500;
      color: var(--text);
    }

    footer {
      background: var(--green);
      color: #fff;
      padding: 40px 32px 20px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 32px;
      margin-bottom: 28px;
    }

    .footer-logo {
      background-image: url('new-logo.png');
      width: 80px;
      height: 80px;
      display: inline-block;
      background-size: contain;
      background-repeat: no-repeat;
    }

    .footer-brand p {
      font-size: 15px;
      color: rgba(255, 255, 255, 0.75);
      line-height: 1.7;
    }

    .footer-col h4 {
      font-family: 'DM Serif Display', serif;
      font-size: 15px;
      margin-bottom: 12px;
    }

    .footer-col a {
      display: block;
      font-size: 15px;
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      margin-bottom: 6px;
      transition: color 0.2s;
    }

    .footer-col a:hover {
      color: #fff;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      padding-top: 14px;
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      align-items: center;
      justify-content: center;
    }

    .footer-bottom a {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.65);
      text-decoration: none;
      transition: color 0.2s;
    }

    .footer-bottom a:hover {
      color: #fff;
    }

    .footer-copy {
      text-align: center;
      font-size: 11px;
      color: rgba(255, 255, 255, 0.5);
      margin-top: 12px;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(14px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .wrap {
      animation: fadeUp 0.45s ease both;
    }
  </style>
</head>

<body>

  <?php require_once(__DIR__ . '/../components/navbar.php'); ?>

  <div class="slider" id="heroSlider">
    <div class="slides" id="slides">
      <div class="slide slide-1">
        <div class="hero-text">
          <h1>Online Exclusive Deals</h1>
          <button class="hero-btn">Shop Now</button>
        </div>
      </div>
      <div class="slide slide-2">
        <div class="hero-text" style="color:#1a3c5c;">
          <h1 style="color:#1a3c5c;">Guardian Products</h1>
          <button class="hero-btn" style="background:#2a6aad;">Shop Now</button>
        </div>
      </div>
      <div class="slide slide-3">
        <div class="hero-text">
          <h1 style="color:#7a3010;">Skin Care Essentials</h1>
          <button class="hero-btn" style="background:#c0501a;">Shop Now</button>
        </div>
      </div>
    </div>
    <button class="slider-arrow slider-prev" onclick="moveSlide(-1)">&#8249;</button>
    <button class="slider-arrow slider-next" onclick="moveSlide(1)">&#8250;</button>
    <div class="slider-dots" id="sliderDots">
      <button class="active" onclick="goToSlide(0)"></button>
      <button onclick="goToSlide(1)"></button>
      <button onclick="goToSlide(2)"></button>
    </div>
  </div>

  <script>
    let current = 0;
    const total = 3;
    let autoTimer = setInterval(() => moveSlide(1), 4000);

    function moveSlide(dir) {
      current = (current + dir + total) % total;
      updateSlider();
      resetTimer();
    }

    function goToSlide(idx) {
      current = idx;
      updateSlider();
      resetTimer();
    }

    function updateSlider() {
      document.getElementById('slides').style.transform = 'translateX(-' + (current * 100) + '%)';
      document.querySelectorAll('#sliderDots button').forEach((btn, i) => {
        btn.classList.toggle('active', i === current);
      });
    }

    function resetTimer() {
      clearInterval(autoTimer);
      autoTimer = setInterval(() => moveSlide(1), 4000);
    }
  </script>

  <div class="wrap">

    <div class="sec-header">
      <h2 class="sec-title">Categories</h2>
      <?php if ($selected_category > 0): ?>
        <a href="/WellCare%20Project/dashboard/Homepage.php" style="color: var(--green); text-decoration: none; font-size: 14px;">← Back to All</a>
      <?php endif; ?>
    </div>
    <div class="categories">
      <?php foreach ($categories as $category): ?>
      <a href="/WellCare%20Project/dashboard/Homepage.php?category=<?php echo $category['category_id']; ?>" style="text-decoration: none; color: inherit;">
        <div class="cat-card" style="<?php echo $selected_category == $category['category_id'] ? 'border: 2px solid var(--green); box-shadow: 0 4px 16px rgba(46, 139, 87, 0.2);' : ''; ?>">
          <div class="cat-icon">
            <img src="/WellCare%20Project/assets/CategoryImage/<?php echo safe($category['image']); ?>" alt="<?php echo safe($category['name']); ?>">
          </div>
          <p><?php echo safe($category['name']); ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <div class="products">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
        <a href="/WellCare%20Project/dashboard/Product.php?id=<?php echo $product['product_id']; ?>" style="text-decoration: none; color: inherit;">
          <div class="prod-card <?php echo isset($featured) ? '' : 'featured'; $featured = true; ?>">
            <div class="prod-img-wrap" style="background:#e3f2fd;">
              <img src="/WellCare%20Project/assets/ProductsImage/<?php echo safe($product['image']); ?>" alt="<?php echo safe($product['name']); ?>">
            </div>
            <p class="prod-name"><?php echo safe($product['name']); ?></p>
            <p class="prod-price">₱<?php echo formatCurrency($product['price']); ?></p>
            <div class="prod-actions" onclick="event.preventDefault(); event.stopPropagation();">
              <form method="POST" action="/WellCare%20Project/config/add_to_cart.php" style="display: flex; gap: 8px; width: 100%;">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn-cart" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>Add To Cart</button>
                <button class="btn-buy" onclick="window.location.href='/WellCare%20Project/dashboard/Product.php?id=<?php echo $product['product_id']; ?>';" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>Buy</button>
                <button type="button" class="btn-wish" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">♡</button>
              </form>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
          <p>No products found in this category.</p>
        </div>
      <?php endif; ?>
    </div>

    <script>
      function addToWishlist(productId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/WellCare%20Project/config/add_to_wishlist.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'product_id';
        input.value = productId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    </script>
    </div>

    <div class="sec-header">
      <h2 class="sec-title">Online Exclusive Deals</h2>
    </div>
    <div class="deals-box">
      <div class="deal-row"><span class="deal-label">Online Exclusive Deals</span><button class="btn-deal">See all products</button></div>
      <div class="deal-row"><span class="deal-label">Guardian Products</span><button class="btn-deal">See all products</button></div>
      <div class="deal-row"><span class="deal-label">Skin Care Essential</span><button class="btn-deal">See all products</button></div>
    </div>

    <div class="sec-header">
      <h2 class="sec-title">FAQs</h2>
    </div>
    <div class="faqs">
      <div class="faq-item"><span>Shipping &amp; Delivery</span><span class="faq-chevron">›</span></div>
      <div class="faq-item"><span>Item Replacement</span><span class="faq-chevron">›</span></div>
      <div class="faq-item"><span>Payment Methods</span><span class="faq-chevron">›</span></div>
      <div class="faq-item"><span>Prescription Medicines</span><span class="faq-chevron">›</span></div>
      <div class="faq-item"><span>Discounts</span><span class="faq-chevron">›</span></div>
      <div class="faq-item"><span>Membership</span><span class="faq-chevron">›</span></div>
    </div>

    <div class="sec-header">
      <h2 class="sec-title">Services</h2>
    </div>
    <div class="services">
      <div class="svc-card">
        <p>Subscription</p>
      </div>
      <div class="svc-card">
        <p>Call &amp; Pick up</p>
      </div>
      <div class="svc-card">
        <p>Shipping &amp; Delivery</p>
      </div>
      <div class="svc-card">
        <p>Vaccine Program</p>
      </div>
    </div>

  </div>

  <footer>
    <div class="footer-grid">
      <div class="footer-brand">
        <img class="footer-logo" src="new-logo.png">
        <p>Well Care Pharmacy Inc.<br />Your trusted community pharmacy.<br />We care for you.</p>
      </div>
      <div class="footer-col">
        <h4>About Us</h4>
        <a href="#">Our Story</a>
        <a href="#">Careers</a>
        <a href="#">Blog</a>
      </div>
      <div class="footer-col">
        <h4>Get Help</h4>
        <a href="#">FAQ</a>
        <a href="#">Shipping Info</a>
        <a href="#">Contact Us</a>
      </div>
      <div class="footer-col">
        <h4>Programs</h4>
        <a href="#">Loyalty Card</a>
        <a href="#">Senior Discount</a>
        <a href="#">Vaccine Program</a>
      </div>
    </div>
    <div class="footer-bottom">
      <a href="#">Return policy</a>
      <a href="#">Privacy policy</a>
      <a href="#">Terms of service</a>
      <a href="#">Contact information</a>
      <a href="#">Refund policy</a>
    </div>
    <p class="footer-copy">© 2026, Well Care Pharmacy</p>
  </footer>

</body>

</html>