<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header('Location: dashboard/Homepage.php');
  exit();
}

include(__DIR__ . '/../config/db_connection.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $emailOrUsername = trim($_POST['email_or_username']);
  $password = trim($_POST['password']);

  // =========================
  // EMAIL/USERNAME VALIDATION
  // =========================
  if (empty($emailOrUsername)) {
    $error = "Email or username is required.";
  }

  // =========================
  // PASSWORD VALIDATION
  // =========================
  elseif (empty($password)) {
    $error = "Password is required.";
  } elseif (strlen($password) < 8 || strlen($password) > 20) {
    $error = "Password must be 8–20 characters.";
  } elseif (!preg_match('/[A-Z]/', $password)) {
    $error = "Password must contain at least one uppercase letter.";
  } elseif (!preg_match('/[a-z]/', $password)) {
    $error = "Password must contain at least one lowercase letter.";
  } elseif (!preg_match('/[0-9]/', $password)) {
    $error = "Password must contain at least one number.";
  } elseif (preg_match('/[;:\'",\/|]/', $password)) {
    $error = "Password contains invalid special characters.";
  } elseif (preg_match('/\s/', $password)) {
    $error = "Password must not contain spaces.";
  }

  // =========================
  // LOGIN LOGIC
  // =========================
  else {

    // Determine if input is email or username
    $isEmail = filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL);
    
    // Use prepared statement to prevent SQL injection
    if ($isEmail) {
      // Search by email
      $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    } else {
      // Search by username
      $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    }
    
    if (!$stmt) {
      $error = "Database error. Please try again later.";
    } else {
      $stmt->bind_param("s", $emailOrUsername);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
          // Regenerate session ID to prevent session fixation attacks
          session_regenerate_id(true);
          $_SESSION['user_id'] = $user['user_id'];
          header('Location: /WellCare%20Project/dashboard/Homepage.php');
          exit();
        } else {
          $error = "Invalid email/username or password.";
        }
      } else {
        $error = "Invalid email/username or password.";
      }
      $stmt->close();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In – Well Care Pharmacy</title>
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

    html,
    body {
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
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
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
      from {
        opacity: 0;
        transform: translateY(18px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
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

    .btn-signin {
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

    .btn-signin:hover {
      background: var(--accent-hover);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
      transform: translateY(-1px);
    }

    .btn-signin:active {
      transform: translateY(0);
      box-shadow: none;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 24px 0 20px;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    .divider span {
      font-size: 12px;
      color: var(--text-muted);
      font-weight: 500;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .signup-row {
      text-align: center;
      font-size: 13.5px;
      color: var(--text-muted);
    }

    .signup-row a {
      color: var(--link);
      font-weight: 600;
      text-decoration: none;
      border-bottom: 1px solid transparent;
      transition: border-color 0.2s ease;
    }

    .signup-row a:hover {
      border-bottom-color: var(--link);
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="brand">
      <img src="../assets/new-logo.png" alt="logo" class="brand-logo" />
      <span class="brand-name">Well Care Pharmacy</span>
    </div>
  </nav>

  <div class="page-center">
    <div class="card">
      <h1 class="heading">Sign in</h1>


      <form method="post">
        <?php if (!empty($error)): ?>
        <div style="background-color: #ffebee; color: #c62828; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #c62828;">
          <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        <div class="field">
          <label for="email_or_username">Email or Username</label>
          <input type="text" id="email_or_username" name="email_or_username" placeholder="you@example.com or johndoe" autocomplete="username" value="<?php echo htmlspecialchars($emailOrUsername ?? ''); ?>" required />
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required />
        </div>

        <button class="btn-signin" type="submit">Sign In</button>

      </form>



      <div class="divider">
        <span>or</span>
      </div>

      <p class="signup-row">
        Don't have an account? <a href="Sign_up.php">Sign Up</a>
      </p>
    </div>
  </div>

</body>

</html>