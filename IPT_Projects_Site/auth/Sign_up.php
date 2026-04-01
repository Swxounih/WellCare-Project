<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up – Well Care Pharmacy</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --bg: #ffffff;
      --card: #ffffff;
      --text-primary: #1dc203;
      --text-muted: #888888;
      --border: #e0e0e0;
      --border-focus: #111111;
      --accent: #1dc203;
      --accent-hover: #2d2d2d;
      --input-bg: #fafafa;
      --link: #2c2cf7;
    }

    html, body {
      height: 100%;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background-color: var(--bg);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      width: 100%;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      padding: 20px 32px;
      animation: fadeDown 0.5s ease both;
    }

    @keyframes fadeDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .brand-name {
      font-family: 'DM Serif Display', serif;
      font-size: 15px;
      color: #1dc203;
      letter-spacing: 0.01em;
    }

    .brand-logo {
      height: 52px;
      width: auto;
      object-fit: contain;
    }

    .page-center {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .card {
      background: var(--card);
      border-radius: 16px;
      padding: 48px 44px 44px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
      animation: fadeUp 0.5s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .heading {
      font-family: 'DM Serif Display', serif;
      font-size: 34px;
      color: var(--text-primary);
      text-align: center;
      margin-bottom: 32px;
      letter-spacing: -0.01em;
    }

    .field {
      margin-bottom: 18px;
    }

    .field label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 7px;
      letter-spacing: 0.01em;
    }

    .field input {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 13px 16px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      color: var(--text-primary);
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .field input:focus {
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.07);
      background: #fff;
    }

    .field input::placeholder {
      color: #c0c0c0;
    }

    .btn-signup {
      width: 100%;
      background: var(--accent);
      color: #ffffff;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      padding: 15px;
      margin-top: 8px;
      cursor: pointer;
      letter-spacing: 0.02em;
      transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    }

    .btn-signup:hover {
      background: var(--accent-hover);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
      transform: translateY(-1px);
    }

    .btn-signup:active {
      transform: translateY(0);
      box-shadow: none;
    }

    .signin-row {
      text-align: center;
      font-size: 13.5px;
      color: var(--text-muted);
      margin-top: 20px;
    }

    .signin-row a {
      color: var(--link);
      font-weight: 600;
      text-decoration: none;
      border-bottom: 1px solid transparent;
      transition: border-color 0.2s ease;
    }

    .signin-row a:hover {
      border-bottom-color: var(--link);
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="brand">
      <img src="assets/New-logo.png" alt="logo" class="brand-logo"/>
      <span class="brand-name">Well Care Pharmacy</span>
    </div>
  </nav>

  <div class="page-center">
    <div class="card">
      <h1 class="heading">Sign up</h1>

      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="johndoe" autocomplete="username"/>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" autocomplete="email"/>
      </div>

      <div class="field">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" placeholder="+1 (555) 000-0000" autocomplete="tel"/>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="new-password"/>
      </div>

      <div class="field">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="••••••••" autocomplete="new-password"/>
      </div>

      <button class="btn-signup" type="button">Sign Up</button>

      <p class="signin-row">
        Already have an account? <a href="Sign_in.php">Sign In</a>
      </p>
    </div>
  </div>

</body>
</html>