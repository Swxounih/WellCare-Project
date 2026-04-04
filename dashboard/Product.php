<?php
session_start();
include(__DIR__ . '/../config/db_connection.php');
include(__DIR__ . '/../config/auth_functions.php');
include(__DIR__ . '/../config/helpers.php');

requireLogin();

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product details
$stmt = $conn->prepare("SELECT p.product_id, p.name, p.description, p.price, p.stock, p.image, p.category_id, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: /WellCare%20Project/dashboard/Homepage.php");
    exit;
}

// Get "You May Also Like" recommendations (same category, different product)
$stmt = $conn->prepare("SELECT product_id, name, price, image FROM products WHERE category_id = ? AND product_id != ? LIMIT 4");
$stmt->bind_param("ii", $product['category_id'], $product_id);
$stmt->execute();
$result = $stmt->get_result();
$recommendations = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle Add to Cart
$cart_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = getCurrentUserId();
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
    $stmt->bind_param("iiii", $user_id, $product_id, $quantity, $quantity);
    if ($stmt->execute()) {
        $cart_message = "✅ Added to cart successfully!";
    }
    $stmt->close();
}

// Handle Add to Wishlist
$wishlist_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    $user_id = getCurrentUserId();
    
    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, added_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        $wishlist_message = "❤️ Added to wishlist!";
    } else {
        $wishlist_message = "Already in wishlist";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo safe($product['name']); ?> - Well Care Pharmacy</title>
    <style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --green:#2e8b57;
  --green-dark:#1a5c38;
  --green-light:#4caf7d;
  --green-btn:#3aaa6a;
  --bg:#f0f0f0;
  --white:#ffffff;
  --text:#111;
  --muted:#777;
  --border:#ddd;
  --price:#2e8b57;
}
html,body{height:100%;}
body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
  color:var(--text);
}

.page{
  width:100%;
  min-height:100vh;
  background:var(--bg);
}

.product-page{
  width:100%;
  background:var(--bg);
}

.product-container{
  width:100%;
  padding:20px 32px 0;
}

.product-card-wrap{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 2px 10px rgba(0,0,0,0.04);
}

.product-head{
  padding:28px 32px 30px;
  border-bottom:1px solid var(--border);
}

.back-title{
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--green);
  font-size:28px;
  font-weight:500;
  margin-bottom:20px;
}

.back-title a{
  color:var(--green);
  text-decoration:none;
  display:flex;
  align-items:center;
  justify-content:center;
}

.hero{
  display:grid;
  grid-template-columns:340px 1fr;
  gap:34px;
  align-items:center;
}

.main-image-box{
  width:100%;
  height:320px;
  background:#f7f7f7;
  border-radius:12px;
  border:1px solid var(--border);
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
}

.main-image-box img{
  width:100%;
  height:100%;
  object-fit:contain;
  display:block;
}

.product-info h1{
  font-family:'DM Serif Display', serif;
  font-size:28px;
  line-height:1.25;
  font-weight:800;
  color:var(--black);
  margin-bottom:8px;
  max-width:850px;
}

.price{
  font-family:'DM Serif Display',serif;
  font-size:42px;
  color:var(--price);
  margin-bottom:14px;
}

.wishlist-btn{
  width:auto;
  min-width:170px;
  height:44px;
  border:1px solid var(--green-light);
  border-radius:24px;
  background:#fff;
  color:var(--green);
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  cursor:pointer;
  font-size:14px;
  transition:border-color 0.2s,background 0.2s,color 0.2s;
  margin-bottom:16px;
}

.wishlist-btn:hover{
  border-color:var(--green);
  background:#f0faf5;
}

.about-title{
  font-size:18px;
  font-weight:600;
  color:var(--green-dark);
  margin-bottom:8px;
}

.about-text{
  font-size:14px;
  line-height:1.6;
  color:var(--muted);
  max-width:720px;
  margin-bottom:22px;
}

.buy-row{
  display:flex;
  align-items:center;
  gap:12px;
}

.qty-wrap{
  display:flex;
  align-items:center;
  gap:10px;
}

.qty-btn{
  width:38px;
  height:38px;
  border:none;
  background:transparent;
  color:var(--green);
  font-size:28px;
  cursor:pointer;
  display:flex;
  align-items:center;
  justify-content:center;
}

.qty-box{
  width:44px;
  height:40px;
  border:1px solid var(--border);
  border-radius:8px;
  background:#fff;
  color:var(--text);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:16px;
}

.cart-btn{
  min-width:160px;
  height:44px;
  border:1px solid var(--green-light);
  border-radius:24px;
  background:#fff;
  color:var(--green);
  font-family:'DM Sans',sans-serif;
  font-size:14px;
  font-weight:600;
  cursor:pointer;
  transition:border-color 0.2s,background 0.2s;
}

.cart-btn:hover{
  border-color:var(--green);
  background:#f0faf5;
}

.related{
  padding:24px 32px 32px;
}

.related-title{
  font-size:28px;
  font-weight:800;
  font-family:'DM Serif Display', serif;
  color:var(--green);
  margin-bottom:18px;
}

.related-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:16px;
}

.product-item{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:18px;
  padding:16px;
  transition:box-shadow 0.2s,transform 0.15s,border-color 0.2s;
}

.product-item:hover{
  box-shadow:0 6px 24px rgba(0,0,0,0.08);
  transform:translateY(-2px);
}

.product-item.active{
  border:2px solid #2d8cff;
}

.thumb{
  width:100%;
  height:170px;
  border-radius:12px;
  background:#f7f7f7;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
  margin-bottom:12px;
}

.thumb img{
  width:100%;
  height:100%;
  object-fit:contain;
  display:block;
}

.card-name{
  font-size:15px;
  line-height:1.45;
  font-family:'DM Serif Display', serif;
  color:var(--text);
  min-height:42px;
  margin-bottom:8px;
}

.card-price{
  font-family:'DM Serif Display',serif;
  font-size:28px;
  color:var(--price);
  margin-bottom:10px;
}

.card-actions{
  display:flex;
  align-items:center;
  gap:8px;
}

.mini-cart{
  flex:1;
  height:36px;
  border:1px solid var(--border);
  border-radius:20px;
  background:#fff;
  font-family:'DM Sans',sans-serif;
  font-size:12px;
  cursor:pointer;
  color:var(--green);
  transition:border-color 0.2s,background 0.2s;
}

.mini-cart:hover{
  border-color:var(--green);
  background:#f0faf5;
}

.mini-buy{
  width:78px;
  height:36px;
  border:none;
  border-radius:20px;
  background:var(--green-btn);
  color:#fff;
  font-family:'DM Sans',sans-serif;
  font-size:12px;
  font-weight:600;
  cursor:pointer;
  transition:background 0.2s;
}

.mini-buy:hover{
  background:var(--green-dark);
}

.mini-wish{
  width:36px;
  height:36px;
  border:1px solid var(--border);
  border-radius:50%;
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  font-size:14px;
  color:var(--muted);
  transition:border-color 0.2s,color 0.2s;
}

.mini-wish:hover{
  border-color:#e74c3c;
  color:#e74c3c;
}

footer{
  background:var(--green);
  color:#fff;
  padding:40px 32px 20px;
  margin-top:20px;
}

.footer-grid{
  display:grid;
  grid-template-columns:2fr 1fr 1fr;
  gap:32px;
  margin-bottom:28px;
}

.footer-brand h4,
.footer-col h4{
  font-family:'DM Serif Display',serif;
  font-size:22px;
  margin-bottom:12px;
}

.footer-brand p{
  font-size:14px;
  color:rgba(255,255,255,0.82);
  line-height:1.7;
}

.footer-col a{
  display:block;
  font-size:14px;
  color:rgba(255,255,255,0.78);
  text-decoration:none;
  margin-bottom:8px;
  transition:color 0.2s;
}

.footer-col a:hover{
  color:#fff;
}

.footer-bottom{
  border-top:1px solid rgba(255,255,255,0.2);
  padding-top:14px;
  display:flex;
  flex-wrap:wrap;
  gap:16px;
  align-items:center;
  justify-content:center;
}

.footer-bottom a{
  font-size:12px;
  color:rgba(255,255,255,0.7);
  text-decoration:none;
  transition:color 0.2s;
}

.footer-bottom a:hover{
  color:#fff;
}

.footer-copy{
  text-align:center;
  font-size:12px;
  color:rgba(255,255,255,0.8);
  margin-top:12px;
}

@keyframes fadeUp{
  from{opacity:0;transform:translateY(14px);}
  to{opacity:1;transform:translateY(0);}
}

.product-container{
  animation:fadeUp 0.45s ease both;
}

@media (max-width:1100px){
  .hero{
    grid-template-columns:1fr;
    align-items:start;
  }

  .related-grid{
    grid-template-columns:repeat(2,1fr);
  }
}

@media (max-width:700px){
  .product-container,
  .product-head,
  .related,
  footer{
    padding-left:20px;
    padding-right:20px;
  }

  .related-grid{
    grid-template-columns:1fr;
  }

  .footer-grid{
    grid-template-columns:1fr;
  }

  .footer-bottom{
    flex-direction:column;
  }
}
</style>
</head>
<body>
<!-- Include Navigation Bar -->
<!-- <?php include(__DIR__ . '/../components/navbar.php'); ?> -->

<!-- nav here -->
    <div class="page">
  <div class="product-page">
    <div class="product-container">
      <div class="product-card-wrap">
        <section class="product-head">
          <div class="back-title">
            <a href="Homepage.php"></a>
            <span>Product</span>
          </div>

          <div class="hero">
            <div class="main-image-box">
              <img src="/WellCare%20Project/assets/ProductsImage/<?php echo safe($product['image']); ?>" alt="<?php echo safe($product['name']); ?>">
            </div>

            <div class="product-info">
              <h1><?php echo safe($product['name']); ?></h1>
              <div class="price">₱<?php echo formatCurrency($product['price']); ?></div>

              <!-- Messages -->
              <?php if ($cart_message): ?>
                <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 14px;">
                  <?php echo $cart_message; ?>
                </div>
              <?php endif; ?>
              <?php if ($wishlist_message): ?>
                <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 14px;">
                  <?php echo $wishlist_message; ?>
                </div>
              <?php endif; ?>

              <form method="POST" style="display: contents;">
                <button type="submit" name="add_to_wishlist" class="wishlist-btn">♡ Add to Wishlist</button>

                <div class="about-title">About the Product</div>
                <p class="about-text">
                  <?php echo nl2br(safe($product['description'])); ?>
                </p>

                <div style="font-size: 14px; color: <?php echo $product['stock'] > 0 ? '#28a745' : '#dc3545'; ?>; margin-bottom: 15px;">
                  <?php echo $product['stock'] > 0 ? '✓ ' . $product['stock'] . ' in stock' : '✗ Out of stock'; ?>
                </div>

                <div class="buy-row">
                  <div class="qty-wrap">
                    <input type="number" name="quantity" class="qty-box" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 44px; height: 40px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; text-align: center;" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                  </div>
                  <button type="submit" name="add_to_cart" class="cart-btn" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>Add To Cart</button>
                </div>
              </form>
            </div>
          </div>
        </section>

        <section class="related">
          <div class="related-title">You may also like</div>

          <div class="related-grid">
            <?php if (!empty($recommendations)): ?>
              <?php foreach ($recommendations as $rec): ?>
              <div class="product-item">
                <div class="thumb"><img src="/WellCare%20Project/assets/ProductsImage/<?php echo safe($rec['image']); ?>" alt="<?php echo safe($rec['name']); ?>"></div>
                <div class="card-name"><?php echo safe($rec['name']); ?></div>
                <div class="card-price">₱<?php echo formatCurrency($rec['price']); ?></div>
                <div class="card-actions">
                  <a href="/WellCare%20Project/dashboard/Product.php?id=<?php echo $rec['product_id']; ?>" style="flex: 1; text-decoration: none;">
                    <button class="mini-cart" style="width: 100%;">View</button>
                  </a>
                  <button class="mini-wish" onclick="addToWishlist(<?php echo $rec['product_id']; ?>)" title="Add to Wishlist">♡</button>
                </div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="grid-column: 1/-1; text-align: center; color: #666; padding: 20px;">No other products in this category</p>
            <?php endif; ?>
          </div>
        </section>
      </div>
    </div>

    <footer>
      <div class="footer-grid">
        <div class="footer-brand">
          <h4>Well Care Pharmacy Inc.</h4>
          <p>#12 Maramba, Lingayen, Pangasinan</p>
          <p>091234567891</p>
          <p>wellcare@gmail.com</p>
        </div>

        <div class="footer-col">
          <h4>About Us</h4>
          <a href="#">About Wellcare Pharmacy</a>
          <a href="#">Careers</a>
          <a href="#">News & Blogs</a>
          <a href="#">Wellcare Pharmacy Brands</a>
          <a href="#">Store locations</a>
        </div>

        <div class="footer-col">
          <h4>Get Help</h4>
          <a href="#">Order Status</a>
          <a href="#">FAQs</a>
          <a href="#">Privacy Notices</a>
          <a href="#">Contact Us</a>
        </div>
      </div>

      <div class="footer-bottom">
        <a href="#">Refund policy</a>
        <a href="#">Privacy policy</a>
        <a href="#">Terms of service</a>
        <a href="#">Contact information</a>
        <a href="#">Shipping policy</a>
      </div>

      <div class="footer-copy">© 2026, Well Care Pharmacy</div>
    </footer>
  </div>
</div>
</body>
</html>