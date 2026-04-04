<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account Setting - Well Care Pharmacy</title>
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
  margin:0;
}

.page{
  min-height:100vh;
  display:flex;
  flex-direction:column;
}

.settings-wrap{
  width:100%;
  padding:34px 42px 0;
}

.settings-card{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:0;
  overflow:hidden;
}

.settings-top{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:18px 30px 8px;
}

.page-back{
  display:flex;
  align-items:center;
  gap:10px;
  color:#64a30a;
  text-decoration:none;
  font-size:16px;
  font-weight:500;
}

.page-back svg{
  width:20px;
  height:20px;
  stroke:currentColor;
  stroke-width:2;
  fill:none;
}

.top-icons{
  display:flex;
  align-items:center;
  gap:18px;
}

.top-icons a{
  display:flex;
  align-items:center;
  justify-content:center;
  color:#64a30a;
  text-decoration:none;
  transition:0.2s;
}

.top-icons a:hover{
  color:var(--green-dark);
}

.top-icons svg{
  width:22px;
  height:22px;
  stroke:currentColor;
  stroke-width:1.8;
  fill:none;
}

.settings-content{
  padding:0 32px 16px;
}

.settings-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:34px;
}

.section-title{
  font-size:17px;
  font-weight:600;
  color:#64a30a;
  margin-bottom:8px;
}

.form-group{
  margin-bottom:16px;
}

.form-label{
  display:block;
  font-size:11px;
  color:#000000;
  margin-bottom:3px;
}

.text-input{
  width:100%;
  height:40px;           
  border:1px solid var(--green-light);
  border-radius:6px;
  background:#fff;
  padding:0 12px; 
  outline:none;
  font-family:'DM Sans',sans-serif;
  font-size:14px;      
}

.text-input:focus{
  border-color:#8fbe45;
}

.settings-button{
  width:100%;
  height:40px;           
  border:1px solid var(--green-light);
  border-radius:6px;
  background:#fff;
  padding:0 12px; 
  outline:none;
  font-family:'DM Sans',sans-serif;
  font-size:14px;   
}

.settings-button:hover{
  background:#f7fbef;
}

.support-btn{
  width:100%;
  height:40px;
  border:1px solid var(--border);
  border-radius:4px;
  background:#fff;
  color:#9bc25b;
  font-family:'DM Sans',sans-serif;
  font-size:12px;
  text-align:left;
  padding:0 8px;
  cursor:pointer;
}

.logout-wrap{
  display:flex;
  justify-content:center;
  padding:12px 0 18px;
}

.logout-btn{
  min-width:156px;
  height:31px;
  border:none;
  border-radius:8px;
  background:var(--green-btn);
  color:#fff;
  font-family:'DM Sans',sans-serif;
  font-size:16px;
  font-weight:700;
  cursor:pointer;
  transition:background 0.2s;
}

.logout-btn:hover{
  background:#578d00;
}

footer{
  margin-top:40px;
  background:#3aaa6a;
  color:#fff;
  width:100%;
  padding:18px 0 12px;
}

.footer-container{
  padding-top:30px;
  width:100%;
  padding:0 42px;
}

.footer-grid{
  display:grid;
  grid-template-columns:2fr 1fr 1fr;
  gap:30px;
  margin-bottom:18px;
}

.footer-col h4{
  font-family:'DM Serif Display',serif;
  font-size:20px;
  margin-bottom:8px;
}

.footer-col a,
.footer-col p{
  font-size:15px;
  color:#fff;
  text-decoration:none;
  margin-bottom:4px;
  display:block;
  line-height:1.45;
}

.footer-bottom-links{
  border-top:1px solid rgba(255,255,255,0.7);
  padding-top:10px;
  display:flex;
  justify-content:space-between;
  gap:10px;
  flex-wrap:wrap;
}

.footer-bottom-links a{
  font-size:11px;
  color:#fff;
  text-decoration:none;
}

.footer-copy{
  border-top:1px solid rgba(255,255,255,0.7);
  margin-top:8px;
  padding-top:8px;
  text-align:center;
  font-size:11px;
  font-weight:600;
}

@media(max-width:900px){
  .settings-grid{
    grid-template-columns:1fr;
  }

  .footer-grid{
    grid-template-columns:1fr;
  }

  .footer-bottom-links{
    justify-content:center;
  }
}

@media(max-width:600px){
  .settings-wrap{
    padding:20px 16px 0;
  }

  .settings-top,
  .settings-content{
    padding-left:16px;
    padding-right:16px;
  }

  .footer-container{
    padding:0 16px;
  }
}
</style>
</head>
<body>

<div class="page">
  <!-- nav here -->
  <div class="settings-wrap">
    <div class="settings-card">

      <div class="settings-top">
        <a href="Homepage.php" class="page-back">
          <svg viewBox="0 0 24 24">
            <path d="M15 5L8 12L15 19"></path>
          </svg>
          <span>Account Setting</span>
        </a>

        <div class="top-icons">
          <a href="#" aria-label="Cart">
            <svg viewBox="0 0 24 24">
              <circle cx="9" cy="19" r="1.5"></circle>
              <circle cx="17" cy="19" r="1.5"></circle>
              <path d="M3 4h2l2.2 10h10.8l2-7H7.5"></path>
            </svg>
          </a>

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

      <div class="settings-content">
        <div class="settings-grid">

          <div class="left-col">
            <div class="section-title">Account</div>

            <div class="form-group">
              <label class="form-label">Username</label>
              <input class="text-input" type="text">
            </div>

            <div class="form-group">
              <label class="form-label">Phone</label>
              <input class="text-input" type="text">
            </div>

            <div class="form-group">
              <label class="form-label">Email</label>
              <input class="text-input" type="email">
            </div>

            <div style="height:8px;"></div>

            <div class="section-title">Change Password</div>

            <div class="form-group">
              <label class="form-label">Current Password</label>
              <input class="text-input" type="password">
            </div>

            <div class="form-group">
              <label class="form-label">New Password</label>
              <input class="text-input" type="password">
            </div>

            <div style="height:8px;"></div>

            <div class="section-title">Support</div>

            <div class="form-group">
              <button class="support-btn">Request Account Deletion</button>
            </div>
          </div>

          <div class="right-col">
            <div class="section-title">My Profile</div>

            <div class="form-group">
              <label class="form-label">Name</label>
              <input class="text-input" type="text">
            </div>

            <div class="form-group">
              <label class="form-label">Gender</label>
              <input class="text-input" type="text">
            </div>

            <div class="form-group">
              <label class="form-label">Birthday</label>
              <input class="text-input" type="text">
            </div>

            <div class="form-group">
              <label class="form-label">Address</label>
              <input class="text-input" type="text">
            </div>

            <div style="height:14px;"></div>

            <div class="section-title">Settings</div>

            <div class="form-group">
              <button class="settings-button">Chat Settings</button>
            </div>

            <div class="form-group">
              <button class="settings-button">Notification Settings</button>
            </div>

            <div class="form-group">
              <button class="settings-button">Privacy Settings</button>
            </div>

            <div class="form-group">
              <button class="settings-button">Language</button>
            </div>
          </div>

        </div>

        <div class="logout-wrap">
          <button class="logout-btn">Logout</button>
        </div>
      </div>

    </div>
  </div>

  <footer>
    <div class="footer-container">

      <div class="footer-grid">
        <div class="footer-col">
          <h4>Well Care Pharmacy Inc.</h4>
          <p>#12 Maramba, Lingayen,</p>
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

      <div class="footer-bottom-links">
        <a href="#">Refund policy</a>
        <a href="#">Privacy policy</a>
        <a href="#">Terms of service</a>
        <a href="#">Contact information</a>
        <a href="#">Shipping policy</a>
      </div>

      <div class="footer-copy">© 2026, Well Care Pharmacy</div>

    </div>
  </footer>

</div>

</body>
</html>