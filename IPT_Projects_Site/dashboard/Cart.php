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
      <a href="Homepage.php" class="cart-back">
        <svg viewBox="0 0 24 24">
          <path d="M15 5L8 12L15 19"></path>
        </svg>
        <span>Cart</span>
      </a>

      <div class="cart-tools">
        <a href="#">Edit</a>

        <a href="#" aria-label="Chat">
          <svg viewBox="0 0 24 24">
            <path d="M21 11.5c0 4.1-4 7.5-9 7.5-1.2 0-2.3-.2-3.4-.6L4 20l1.4-3.6C4.5 15 3 13.3 3 11.5 3 7.4 7 4 12 4s9 3.4 9 7.5Z"></path>
          </svg>
        </a>

        <a href="#" aria-label="Wishlist">
          <svg viewBox="0 0 24 24">
            <path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2 4 4 0 0 1 7 2c0 5.5-7 10-7 10z"></path>
          </svg>
        </a>
      </div>
    </div>

    <div class="cart-list">

      <div class="cart-item">
        <div class="check-wrap">
          <input class="item-check" type="checkbox">
        </div>

        <div class="item-image">
          <img src="ProductsImage/Product.png" alt="Biogesic Caplet">
        </div>

        <div class="item-info">
          <div class="item-name">Biogesic Caplet 500mg 30s – Everyday Pain and Fever Support</div>
          <div class="item-price">₱108.00</div>
        </div>

        <div class="item-actions">
          <button class="qty-btn">−</button>
          <div class="qty-box">0</div>
          <button class="qty-btn">+</button>
        </div>
      </div>

      <div class="cart-item">
        <div class="check-wrap">
          <input class="item-check" type="checkbox">
        </div>

        <div class="item-image">
          <img src="ProductsImage/Product2.jpg" alt="Paracetamol Biogesic Syrup">
        </div>

        <div class="item-info">
          <div class="item-name">Paracetamol Biogesic 250mg/5ml Melon-flavored Syrup 60ml</div>
          <div class="item-price">₱156.00</div>
        </div>

        <div class="item-actions">
          <button class="qty-btn">−</button>
          <div class="qty-box">0</div>
          <button class="qty-btn">+</button>
        </div>
      </div>

      <div class="cart-item">
        <div class="check-wrap">
          <input class="item-check" type="checkbox">
        </div>

        <div class="item-image">
          <img src="ProductsImage/Product4.png" alt="Fern-C Gold">
        </div>

        <div class="item-info">
          <div class="item-name">Fern-C Gold 27+3 Pack</div>
          <div class="item-price">₱371.25</div>
        </div>

        <div class="item-actions">
          <button class="qty-btn">−</button>
          <div class="qty-box">0</div>
          <button class="qty-btn">+</button>
        </div>
      </div>

    </div>

    <div class="cart-summary">
      <div class="summary-left">
        <input class="item-check" type="checkbox">
        <span>All</span>
      </div>

      <div class="summary-right">
        <div class="total-price">₱0.00</div>
        <button class="checkout-btn">Check Out (0)</button>
      </div>
    </div>

  </div>
</div>

</body>
</html>