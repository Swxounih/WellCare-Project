<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Purchases - Well Care Pharmacy</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

:root{
  --green:#2e8b57;
  --green-dark:#1a5c38;
  --green-btn:#3aaa6a;
  --bg:#f0f0f0;
  --white:#ffffff;
  --border:#ddd;
}

body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
}

.page{
  width:100%;
  min-height:100vh;
  display:flex;
  flex-direction:column;
}

.purchases-wrap{
  width:100%;
  min-height:100vh;
  background:var(--white);
  display:flex;
  flex-direction:column;
}

/* HEADER */
.purchases-top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:20px 40px;
}

.purchases-back{
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--green);
  text-decoration:none;
  font-weight:600;
}

.purchases-back svg{
  width:22px;
  height:22px;
  stroke:var(--green);
  stroke-width:2.5;
  fill:none;
}

/* ICONS */
.purchases-tools{
  display:flex;
  gap:14px;
}

.purchases-tools a{
  width:42px;
  height:42px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.purchases-tools svg{
  width:28px;
  height:28px;
  stroke:var(--green-dark);
  stroke-width:2.5;
  fill:none;
}

/* TABS */
.purchases-tabs{
  display:flex;
  gap:24px;
  padding:0 40px;
  border-bottom:1px solid var(--border);
}

.purchase-tab{
  height:40px;
  display:flex;
  align-items:center;
  color:var(--green);
  text-decoration:none;
  font-size:14px;
  position:relative;
}

.purchase-tab.active::after{
  content:"";
  position:absolute;
  bottom:-1px;
  left:0;
  right:0;
  height:2px;
  background:var(--green);
}

/* CONTENT */
.purchases-content{
  flex:1;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:22px;
  color:#000;
}

/* FOOTER */
footer{
  width:100%;
  background:var(--green-btn);
  color:#fff;
  padding:30px 0;
}

.footer-container{
  padding:0 40px;
}

.footer-grid{
  display:grid;
  grid-template-columns:2fr 1fr 1fr;
  gap:40px;
}

/* FIXED PART */
.footer-col{
  display:flex;
  flex-direction:column;
}

.footer-col h4{
  font-family:'DM Serif Display',serif;
  margin-bottom:12px;
  font-size:18px;
}

/* FORCE LINKS TO STACK */
.footer-col a,
.footer-col p{
  display:block;
  width:100%;
  font-size:13px;
  color:#fff;
  text-decoration:none;
  margin-bottom:6px;
}

/* BOTTOM LINKS */
.footer-bottom{
  border-top:1px solid rgba(255,255,255,0.5);
  margin-top:20px;
  padding-top:12px;
  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
  font-size:12px;
}

.footer-copy{
  text-align:center;
  margin-top:12px;
  font-size:12px;
}

</style>
</head>

<body>

<div class="page">

<div class="purchases-wrap">

  <div class="purchases-top">
    <a href="Homepage.php" class="purchases-back">
      <svg viewBox="0 0 24 24">
        <path d="M15 5L8 12L15 19"></path>
      </svg>
      My Purchases
    </a>

    <div class="purchases-tools">
      <a href="#">
        <svg viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="7"></circle>
          <path d="M20 20L17 17"></path>
        </svg>
      </a>

      <a href="#">
        <svg viewBox="0 0 24 24">
          <path d="M21 11.5c0 4.1-4 7.5-9 7.5-1.2 0-2.3-.2-3.4-.6L4 20l1.4-3.6C4.5 15 3 13.3 3 11.5 3 7.4 7 4 12 4s9 3.4 9 7.5Z"></path>
        </svg>
      </a>
    </div>
  </div>

  <div class="purchases-tabs">
    <a href="#" class="purchase-tab">All</a>
    <a href="#" class="purchase-tab">To Pay</a>
    <a href="#" class="purchase-tab active">To Ship</a>
    <a href="#" class="purchase-tab">To Receive</a>
    <a href="#" class="purchase-tab">Completed</a>
    <a href="#" class="purchase-tab">Return/Refund</a>
    <a href="#" class="purchase-tab">Cancelled</a>
  </div>

  <div class="purchases-content">
    No Order Yet
  </div>

  <footer>
    <div class="footer-container">

      <div class="footer-grid">

        <div class="footer-col">
          <h4>Well Care Pharmacy Inc.</h4>
          <p>#12 Maramba, Lingayen</p>
          <p>Pangasinan</p>
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
        <span>Refund policy</span>
        <span>Privacy policy</span>
        <span>Terms of service</span>
        <span>Contact information</span>
        <span>Shipping policy</span>
      </div>

      <div class="footer-copy">
        © 2026, Well Care Pharmacy
      </div>

    </div>
  </footer>

</div>
</div>

</body>
</html>