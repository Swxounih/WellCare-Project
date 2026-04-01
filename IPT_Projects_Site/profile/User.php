<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Page - Well Care Pharmacy</title>
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

html,body{
  width:100%;
  min-height:100%;
}

body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
  color:var(--text);
  display:flex;
  flex-direction:column;
  min-height:100vh;
  margin:0;
}

.page{
  flex:1;
  display:flex;
  flex-direction:column;
  min-height:100vh;
}

.user-header{
  background:var(--white);
  padding:28px 28px 18px;
  border-bottom:1px solid var(--border);
}

.top-bar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:20px;
}

.back-btn{
  font-size:28px;
  color:var(--green);
  text-decoration:none;
  line-height:1;
}

.top-icons{
  display:flex;
  align-items:center;
  gap:22px;
}

.top-icons a{
  color:#7a7a7a;
  display:flex;
  align-items:center;
  justify-content:center;
  text-decoration:none;
  transition:0.2s;
}

.top-icons a:hover{
  color:var(--green);
}

.top-icons svg{
  width:26px;
  height:26px;
  stroke:currentColor;
  stroke-width:1.8;
  fill:none;
}

.user-top{
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:space-between;
  gap:40px;
}

.profile{
  display:flex;
  align-items:center;
  gap:16px;
  flex-shrink:0;
}

.profile-img{
  width:60px;
  height:60px;
  border-radius:50%;
  background:var(--green-light);
}

.profile-name{
  font-size:22px;
  font-weight:600;
  color:var(--green);
  white-space:nowrap;
}

.status-row{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:80px;
  flex:1;
}

.status-item{
  text-align:center;
  color:var(--green);
}

.status-item svg{
  width:48px;
  height:48px;
  stroke:var(--green);
  stroke-width:2;
  fill:none;
  margin-bottom:8px;
  transition:0.2s;
}

.status-item:hover svg{
  stroke:var(--green-dark);
  transform:scale(1.08);
}

.status-item p{
  font-size:14px;
}

.section{
  flex:1;
  padding:20px 26px 18px;
}

.section-title{
  color:var(--green);
  margin-bottom:16px;
  font-family:'DM Serif Display', serif;
  font-weight: 800px;
  font-size:25px;
}

.products{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:26px;
}

.product-card{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:18px;
  padding:24px;
  min-height:350px;
  border-radius:20px;
  transition:0.2s;
}

.product-card:hover{
  transform:translateY(-4px);
  box-shadow:0 8px 24px rgba(0,0,0,0.08);
}

.thumb{
  height:170px;
  display:flex;
  align-items:center;
  justify-content:center;
  margin-bottom:14px;
}

.thumb img{
  max-width:100%;
  max-height:100%;
  object-fit:contain;
  display:block;
}

.card-name{
  font-size:15px;
  margin-bottom:10px;
}

.card-price{
  font-family:'DM Serif Display',serif;
  font-size:18px;
  color:var(--price);
  margin-bottom:12px;
}

.card-actions{
  display:flex;
  gap:10px;
}

.btn-cart{
  flex:1;
  height:42px;
  border:1px solid var(--border);
  border-radius:20px;
  font-size:13px;
  background:#fff;
  cursor:pointer;
}

.btn-buy{
  height:38px;
  padding:0 18px;
  border:none;
  border-radius:20px;
  background:var(--green-btn);
  color:#fff;
  height:42px;
  font-size:13px;
  cursor:pointer;
}

footer{
  margin-top:auto;
  background:var(--green);
  color:#fff;
  width:100vw;
  position:relative;
  left:50%;
  transform:translateX(-50%);
  padding:40px 0 20px;
}

.footer-container{
  width:100%;
  max-width:100%;
  margin:0 auto;
  padding:0 26px;
}

.footer-grid{
  display:grid;
  grid-template-columns:2fr 1fr 1fr;
  gap:30px;
  margin-bottom:20px;
}

.footer-col h4{
  font-family:'DM Serif Display',serif;
  margin-bottom:10px;
  font-size:22px;
}

.footer-col a,
.footer-col p{
  font-size:13px;
  color:#fff;
  text-decoration:none;
  margin-bottom:6px;
  display:block;
  line-height:1.5;
}

.footer-bottom{
  border-top:1px solid rgba(255,255,255,0.3);
  padding-top:10px;
  text-align:center;
  font-size:12px;
}

@media(max-width:1100px){
  .products{
    grid-template-columns:repeat(2,1fr);
  }

  .user-top{
    flex-direction:column;
    align-items:flex-start;
  }

  .status-row{
    width:100%;
    justify-content:space-between;
    gap:30px;
  }
}

@media(max-width:700px){
  .products{
    grid-template-columns:1fr;
  }

  .footer-grid{
    grid-template-columns:1fr;
  }

  .status-row{
    flex-wrap:wrap;
    justify-content:flex-start;
  }

  .profile-name{
    white-space:normal;
  }
}
</style>
</head>
<body>

<div class="page">

  <div class="user-header">
    <div class="top-bar">
      <a href="Homepage.php" class="back-btn">←</a>

      <div class="top-icons">

        <a href="#" aria-label="Wishlist">
          <svg viewBox="0 0 24 24">
            <path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2 4 4 0 0 1 7 2c0 5.5-7 10-7 10z"/>
          </svg>
        </a>

        <a href="#" aria-label="Orders">
          <svg viewBox="0 0 24 24">
            <path d="M6 8h12l-1 11H7L6 8z"/>
            <path d="M9 8a3 3 0 0 1 6 0"/>
          </svg>
        </a>

        <a href="#" aria-label="Cart">
          <svg viewBox="0 0 24 24">
            <circle cx="9" cy="19" r="1.5"/>
            <circle cx="17" cy="19" r="1.5"/>
            <path d="M3 4h2l2.2 10h10.8l2-7H7.5"/>
          </svg>
        </a>

        <a href="#" aria-label="Profile">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c2-4 6-5 8-5s6 1 8 5"/>
          </svg>
        </a>

      </div>
    </div>

    <div class="user-top">
      <div class="profile">
        <div class="profile-img"></div>
        <div class="profile-name">Yasmien De Guzman</div>
      </div>

      <div class="status-row">
        <div class="status-item">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="7" width="18" height="10" rx="2"></rect>
            <line x1="3" y1="11" x2="21" y2="11"></line>
          </svg>
          <p>To Pay</p>
        </div>

        <div class="status-item">
          <svg viewBox="0 0 24 24">
            <path d="M3 7l9-4 9 4-9 4-9-4z"/>
            <path d="M3 7v10l9 4 9-4V7"/>
            <path d="M12 11v10"/>
          </svg>
          <p>To Ship</p>
        </div>

        <div class="status-item">
          <svg viewBox="0 0 24 24">
            <rect x="1" y="7" width="15" height="10"></rect>
            <path d="M16 10h4l3 3v4h-7z"></path>
            <circle cx="5" cy="19" r="1.5"></circle>
            <circle cx="18" cy="19" r="1.5"></circle>
          </svg>
          <p>To Receive</p>
        </div>

        <div class="status-item">
          <svg viewBox="0 0 24 24">
            <path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2 4 4 0 0 1 7 2c0 5.5-7 10-7 10z"></path>
          </svg>
          <p>Wishlist</p>
        </div>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="section-title">You may also like</div>

    <div class="products">
      <div class="product-card">
        <div class="thumb"><img src="ProductsImage/Product2.jpg" alt="Paracetamol Biogesic Syrup"></div>
        <div class="card-name">Paracetamol Biogesic Syrup</div>
        <div class="card-price">₱156.00</div>
        <div class="card-actions">
          <button class="btn-cart">Add To Cart</button>
          <button class="btn-buy">Buy</button>
        </div>
      </div>

      <div class="product-card">
        <div class="thumb"><img src="ProductsImage/Product3.png" alt="Cetaphil Baby Wash"></div>
        <div class="card-name">Cetaphil Baby Wash</div>
        <div class="card-price">₱606.00</div>
        <div class="card-actions">
          <button class="btn-cart">Add To Cart</button>
          <button class="btn-buy">Buy</button>
        </div>
      </div>

      <div class="product-card">
        <div class="thumb"><img src="ProductsImage/Product4.png" alt="Fern-C Gold"></div>
        <div class="card-name">Fern-C Gold</div>
        <div class="card-price">₱371.25</div>
        <div class="card-actions">
          <button class="btn-cart">Add To Cart</button>
          <button class="btn-buy">Buy</button>
        </div>
      </div>

      <div class="product-card">
        <div class="thumb"><img src="ProductsImage/Product6.jpg" alt="Bioflu Tablets"></div>
        <div class="card-name">Bioflu Tablets</div>
        <div class="card-price">₱136.00</div>
        <div class="card-actions">
          <button class="btn-cart">Add To Cart</button>
          <button class="btn-buy">Buy</button>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="footer-container">
      <div class="footer-grid">
        <div class="footer-col">
          <h4>Well Care Pharmacy Inc.</h4>
          <p>#12 Maramba, Lingayen</p>
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
        © 2026, Well Care Pharmacy
      </div>
    </div>
  </footer>

</div>

</body>
</html>