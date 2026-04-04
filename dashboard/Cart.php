<?php
session_start();
include(__DIR__ . '/../config/db_connection.php');
include(__DIR__ . '/../config/auth_functions.php');
include(__DIR__ . '/../config/helpers.php');

requireLogin();

$user_id = getCurrentUserId();

// Get cart items
$stmt = $conn->prepare("SELECT c.cartID, c.product_id, c.quantity, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? ORDER BY c.added_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.10;
$total = $subtotal + $tax;

// Handle remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $cartID = (int)$_POST['remove_item'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE cartID = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartID, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - Well Care Pharmacy</title>
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

.cart-wrap{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:14px;
  overflow:hidden;
  box-shadow:0 4px 16px rgba(0,0,0,0.05);
}

.cart-top{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:14px 18px;
  border-bottom:1px solid var(--border);
}

.cart-back{
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--green);
  text-decoration:none;
  font-size:14px;
  font-weight:500;
}

.cart-back svg{
  width:18px;
  height:18px;
  stroke:currentColor;
  stroke-width:2;
  fill:none;
}

.cart-tools{
  display:flex;
  align-items:center;
  gap:12px;
}

.cart-tools a{
  text-decoration:none;
  color:var(--green);
  font-size:12px;
}

.cart-tools svg{
  width:18px;
  height:18px;
  stroke:var(--green);
  stroke-width:2;
  fill:none;
  vertical-align:middle;
}

.cart-list{
  background:#fafafa;
}

.cart-item{
  display:grid;
  grid-template-columns:28px 60px 1fr auto;
  gap:14px;
  align-items:center;
  padding:14px 18px;
  border-bottom:1px solid var(--border);
  background:var(--white);
}

.cart-item:last-child{
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

.cart-summary{
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

  .cart-item{
    grid-template-columns:24px 56px 1fr;
  }

  .item-actions{
    grid-column:3;
    justify-content:flex-end;
    padding-left:0;
    margin-top:6px;
  }

  .cart-summary{
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
  <div class="cart-wrap">

    <div class="cart-top">
      <a href="/WellCare%20Project/dashboard/Homepage.php" class="cart-back">
        <svg viewBox="0 0 24 24">
          <path d="M15 5L8 12L15 19"></path>
        </svg>
        <span>Cart</span>
      </a>

      <div class="cart-tools">
        <a href="/WellCare%20Project/wishlist/Wishlist.php" aria-label="Wishlist">
          <svg viewBox="0 0 24 24">
            <path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2 4 4 0 0 1 7 2c0 5.5-7 10-7 10z"></path>
          </svg>
        </a>
      </div>
    </div>

    <div class="cart-list">
      <?php if (!empty($cart_items)): ?>
        <?php foreach ($cart_items as $item): ?>
        <div class="cart-item">
          <div class="item-image">
            <img src="/WellCare%20Project/assets/ProductsImage/<?php echo safe($item['image']); ?>" alt="<?php echo safe($item['name']); ?>">
          </div>

          <div class="item-info">
            <div class="item-name"><?php echo safe($item['name']); ?></div>
            <div class="item-price">₱<?php echo formatCurrency($item['price']); ?> x <?php echo $item['quantity']; ?> = ₱<?php echo formatCurrency($item['price'] * $item['quantity']); ?></div>
          </div>

          <div class="item-actions">
            <form method="POST" style="display: flex; gap: 5px;">
              <input type="hidden" name="remove_item" value="<?php echo $item['cartID']; ?>">
              <button type="submit" class="qty-btn" title="Remove from cart" style="color: #dc3545;">✕</button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="text-align: center; padding: 40px; color: #666;">
          <p>Your cart is empty</p>
          <a href="/WellCare%20Project/dashboard/Homepage.php" class="checkout-btn" style="display: inline-block; text-decoration: none; margin-top: 15px;">Continue Shopping</a>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!empty($cart_items)): ?>
    <div class="cart-summary">
      <div class="summary-left">
        <?php echo count($cart_items); ?> item(s)
      </div>

      <div class="summary-right" style="flex-direction: column; gap: 10px;">
        <div style="display: flex; justify-content: space-between;">
          <span>Subtotal:</span>
          <strong>₱<?php echo formatCurrency($subtotal); ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span>Tax (10%):</span>
          <strong>₱<?php echo formatCurrency($tax); ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 18px; color: var(--price); font-weight: bold;">
          <span>Total:</span>
          <span>₱<?php echo formatCurrency($total); ?></span>
        </div>
        <a href="/WellCare%20Project/dashboard/checkout.php" class="checkout-btn" style="display: block; text-align: center; text-decoration: none;">Proceed to Checkout</a>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

</body>
</html>