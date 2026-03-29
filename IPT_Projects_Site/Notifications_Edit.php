<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifications - Well Care Pharmacy</title>

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
  --border:#dfeee6;
}

body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
}

/* PAGE */
.page{
  padding:30px 40px;
}

/* CARD */
.notifications-card{
  background:#fff;
  border:1px solid var(--border);
  border-radius:8px;
  overflow:hidden;
}

/* HEADER */
.notifications-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:16px 20px;
  border-bottom:1px solid var(--border);
}

.notifications-title{
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--green);
}

.notifications-title svg{
  width:18px;
  height:18px;
  stroke:currentColor;
  fill:none;
  stroke-width:2;
}

.notifications-actions{
  display:flex;
  align-items:center;
  gap:14px;
}

.notifications-actions svg{
  width:20px;
  height:20px;
  stroke:var(--green);
  fill:none;
  stroke-width:2;
}

/* LIST */
.notifications-list{
  background:#fafafa;
}

/* ITEM */
.notification-item{
  display:flex;
  align-items:center;
  gap:16px;
  padding:18px 20px;
  border-bottom:1px solid var(--border);
  background:#fff;
}

/* CHECKBOX */
.msg-check{
  width:16px;
  height:16px;
  accent-color:var(--green);
}

/* AVATAR */
.msg-avatar{
  width:60px;
  height:60px;
  border:1px solid var(--green-light);
  border-radius:10px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.msg-avatar svg{
  width:32px;
  height:32px;
  stroke:var(--green);
  fill:none;
  stroke-width:2;
}

/* CONTENT */
.msg-content{
  flex:1;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.msg-name{
  color:var(--green);
  font-size:15px;
  font-weight:500;
}

.msg-date{
  font-size:12px;
  color:var(--green);
}

/* BOTTOM BAR */
.notifications-bottom{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:16px 20px;
  background:#fff;
  border-top:1px solid var(--border);
}

.bottom-left{
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--green);
  font-size:14px;
}

.delete-btn{
  padding:8px 20px;
  border:none;
  border-radius:8px;
  background:var(--green-btn);
  color:#fff;
  font-size:13px;
  cursor:pointer;
}

.delete-btn:hover{
  background:var(--green-dark);
}

/* RESPONSIVE */
@media(max-width:700px){
  .page{padding:20px;}
  .msg-content{flex-direction:column;align-items:flex-start;}
}
</style>
</head>

<body>

<div class="page">

  <div class="notifications-card">

    <!-- HEADER -->
    <div class="notifications-header">
      <div class="notifications-title">
        <svg viewBox="0 0 24 24">
          <path d="M15 5L8 12L15 19"></path>
        </svg>
        <span>Notifications</span>
      </div>

      <div class="notifications-actions">
        <svg viewBox="0 0 24 24">
          <path d="M21 11.5c0 4.1-4 7.5-9 7.5-1.2 0-2.3-.2-3.4-.6L4 20l1.4-3.6C4.5 15 3 13.3 3 11.5 3 7.4 7 4 12 4s9 3.4 9 7.5Z"></path>
        </svg>
      </div>
    </div>

    <!-- LIST -->
    <div class="notifications-list">

      <div class="notification-item">
        <input type="checkbox" class="msg-check">
        <div class="msg-avatar">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c2-4 6-5 8-5s6 1 8 5"/>
          </svg>
        </div>
        <div class="msg-content">
          <div class="msg-name">Yasmien De Guzman</div>
          <div class="msg-date">03/09/26</div>
        </div>
      </div>

      <div class="notification-item">
        <input type="checkbox" class="msg-check">
        <div class="msg-avatar">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c2-4 6-5 8-5s6 1 8 5"/>
          </svg>
        </div>
        <div class="msg-content">
          <div class="msg-name">Arnel Cruz</div>
          <div class="msg-date">03/09/26</div>
        </div>
      </div>

      <div class="notification-item">
        <input type="checkbox" class="msg-check">
        <div class="msg-avatar">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c2-4 6-5 8-5s6 1 8 5"/>
          </svg>
        </div>
        <div class="msg-content">
          <div class="msg-name">Kenji Celestino</div>
          <div class="msg-date">03/09/26</div>
        </div>
      </div>

      <div class="notification-item">
        <input type="checkbox" class="msg-check">
        <div class="msg-avatar">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c2-4 6-5 8-5s6 1 8 5"/>
          </svg>
        </div>
        <div class="msg-content">
          <div class="msg-name">Kurt Palavino</div>
          <div class="msg-date">03/09/26</div>
        </div>
      </div>

    </div>

    <!-- BOTTOM -->
    <div class="notifications-bottom">
      <div class="bottom-left">
        <input type="checkbox" class="msg-check">
        <span>All</span>
      </div>
      <button class="delete-btn">Delete</button>
    </div>

  </div>

</div>

</body>
</html>