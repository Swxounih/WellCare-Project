<?php
session_start();
include(__DIR__ . '/../config/db_connection.php');
include(__DIR__ . '/../config/auth_functions.php');
include(__DIR__ . '/../config/helpers.php');

requireLogin();

$user_id = getCurrentUserId();

// Get wishlist items
$stmt = $conn->prepare("SELECT w.wishlist_id, w.product_id, p.name, p.price, p.image FROM wishlist w JOIN products p ON w.product_id = p.product_id WHERE w.user_id = ? ORDER BY w.added_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlist_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle remove from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $wishlist_id = (int)$_POST['remove_item'];
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE wishlist_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $wishlist_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Handle add from wishlist to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['add_to_cart'];
    
    // Check if product already in cart
    $stmt = $conn->prepare("SELECT cartID, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($existing) {
        $new_qty = $existing['quantity'] + 1;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cartID = ?");
        $stmt->bind_param("ii", $new_qty, $existing['cartID']);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, 1, NOW())");
        $stmt->bind_param("ii", $user_id, $product_id);
    }
    $stmt->execute();
    $stmt->close();
    
    header("Location: " . $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') ? '&' : '?') . "added=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wishlist - Well Care Pharmacy</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

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
  padding:24px 28px 40px;
}

.page-title{
  font-size:26px;
  color:var(--text);
  margin-bottom:12px;
}

/* UPDATED */
.wishlist-wrap{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:14px;
  overflow:hidden;
  box-shadow:0 4px 16px rgba(0,0,0,0.05);
}

.wishlist-top{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:14px 18px;
  border-bottom:1px solid var(--border);
}

.wishlist-back{
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--green);
  text-decoration:none;
  font-size:14px;
  font-weight:500;
}

.wishlist-back svg{
  width:18px;
  height:18px;
  stroke:currentColor;
  stroke-width:2;
  fill:none;
}

.wishlist-tools{
  display:flex;
  align-items:center;
  gap:12px;
}

.wishlist-tools a{
  text-decoration:none;
  color:var(--green);
  font-size:12px;
}

.wishlist-tools svg{
  width:18px;
  height:18px;
  stroke:var(--green);
  stroke-width:2;
  fill:none;
  vertical-align:middle;
}

.wishlist-list{
  background:#fafafa;
}

.wishlist-item{
  display:grid;
  grid-template-columns:28px 60px 1fr auto;
  gap:14px;
  align-items:center;
  padding:14px 18px;
  border-bottom:1px solid var(--border);
  background:var(--white);
}

.wishlist-item:last-child{
  border-bottom:none;
}

.check-wrap{
  display:flex;
  align-items:center;
  justify-content:center;
}

.item-check{
  width:16px;
  height:16px;
  accent-color:var(--green);
  cursor:pointer;
}

.item-image{
  width:60px;
  height:60px;
  border-radius:8px;
  border:1px solid var(--border);
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
}

.item-image img{
  max-width:100%;
  max-height:100%;
  object-fit:contain;
  display:block;
}

.item-info{
  min-width:0;
}

.item-name{
  font-size:14px;
  line-height:1.4;
  color:var(--black);
  margin-bottom:8px;
}

.item-price{
  font-family:'DM Serif Display',serif;
  font-size:16px;
  color:var(--price);
}

.item-actions{
  display:flex;
  align-items:center;
  gap:10px;
  padding-left:10px;
}

.qty-btn{
  border:none;
  background:transparent;
  color:var(--green);
  font-size:24px;
  line-height:1;
  cursor:pointer;
  width:18px;
  height:18px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.qty-box{
  width:20px;
  height:22px;
  border:1px solid var(--green-light);
  border-radius:6px;
  display:flex;
  align-items:center;
  justify-content:center;
  color:var(--green);
  font-size:12px;
  background:#fff;
}

.wishlist-summary{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:16px;
  padding:16px 18px;
  background:var(--white);
  border-top:1px solid var(--border);
}

.summary-left{
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--green);
  font-size:13px;
}

.summary-right{
  display:flex;
  align-items:center;
  gap:16px;
}

.total-price{
  font-family:'DM Serif Display',serif;
  font-size:22px;
  color:var(--price);
  white-space:nowrap;
}

.checkout-btn{
  height:38px;
  padding:0 18px;
  border:none;
  border-radius:22px;
  background:var(--green-btn);
  color:#fff;
  font-family:'DM Sans',sans-serif;
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:background 0.2s;
}

.checkout-btn:hover{
  background:var(--green-dark);
}

@media(max-width:700px){
  .page{
    padding:18px 14px 24px;
  }

  .wishlist-item{
    grid-template-columns:24px 56px 1fr;
  }

  .item-actions{
    grid-column:3;
    justify-content:flex-end;
    padding-left:0;
    margin-top:6px;
  }

  .wishlist-summary{
    flex-direction:column;
    align-items:stretch;
  }

  .summary-right{
    justify-content:space-between;
  }
}
</style>
</head>
<body>

<div class="page">

  <div class="wishlist-wrap">

    <div class="wishlist-top">
      <a href="/WellCare%20Project/dashboard/Homepage.php" class="wishlist-back">
        <svg viewBox="0 0 24 24">
          <path d="M15 5L8 12L15 19"></path>
        </svg>
        <span>Wishlist</span>
      </a>

      <div class="wishlist-tools">
        <a href="#">Edit</a>

        <a href="#" aria-label="Chat">
          <svg viewBox="0 0 24 24">
            <path d="M21 11.5c0 4.1-4 7.5-9 7.5-1.2 0-2.3-.2-3.4-.6L4 20l1.4-3.6C4.5 15 3 13.3 3 11.5 3 7.4 7 4 12 4s9 3.4 9 7.5Z"></path>
          </svg>
        </a>

      </div>
    </div>

    <div class="wishlist-list">
      <?php if (!empty($wishlist_items)): ?>
        <?php foreach ($wishlist_items as $item): ?>
        <div class="wishlist-item">
          <div class="item-image">
            <img src="/WellCare%20Project/assets/ProductsImage/<?php echo safe($item['image']); ?>" alt="<?php echo safe($item['name']); ?>">
          </div>

          <div class="item-info">
            <div class="item-name"><?php echo safe($item['name']); ?></div>
            <div class="item-price">₱<?php echo formatCurrency($item['price']); ?></div>
          </div>

          <div class="item-actions">
            <form method="POST" style="display: flex; gap: 8px;">
              <button type="submit" name="add_to_cart" value="<?php echo $item['product_id']; ?>" class="qty-btn" title="Add to Cart" style="color: #28a745; font-size: 20px;">🛒</button>
              <button type="submit" name="remove_item" value="<?php echo $item['wishlist_id']; ?>" class="qty-btn" title="Remove from Wishlist" style="color: #dc3545; font-size: 20px;">✕</button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="text-align: center; padding: 40px; color: #666;">
          <p>Your wishlist is empty</p>
          <a href="/WellCare%20Project/dashboard/Homepage.php" class="checkout-btn" style="display: inline-block; text-decoration: none; margin-top: 15px;">Continue Shopping</a>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!empty($wishlist_items)): ?>
    <div class="wishlist-summary">
      <div class="summary-left">
        <?php echo count($wishlist_items); ?> item(s) in wishlist
      </div>

      <div class="summary-right">
        <a href="/WellCare%20Project/dashboard/Cart.php" class="checkout-btn" style="text-decoration: none;">View Cart</a>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

</body>
</html>