<?php
include(__DIR__ . '/../config/db_connection.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Trim and sanitize all inputs
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $civil_status = trim($_POST['civil_status'] ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');

    // =========================
    // VALIDATION & SANITIZATION
    // =========================

    // Fullname validation
    if (empty($fullname)) {
        $error = "Full name is required.";
    } elseif (strlen($fullname) < 2 || strlen($fullname) > 100) {
        $error = "Full name must be 2–100 characters.";
    }
    // Username validation
    elseif (empty($username)) {
        $error = "Username is required.";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = "Username must be 3–50 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $error = "Username can only contain letters, numbers, hyphens, and underscores.";
    }
    // Email validation
    elseif (empty($email)) {
        $error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    // Password validation
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
    // Phone validation (basic format: digits and common separators)
    elseif (empty($phone)) {
        $error = "Phone number is required.";
    } elseif (!preg_match('/^[0-9\s\-\+\(\)]{7,20}$/', $phone)) {
        $error = "Phone number is invalid (7–20 characters, digits and common separators only).";
    }
    // Address validation
    elseif (empty($address)) {
        $error = "Address is required.";
    } elseif (strlen($address) < 5 || strlen($address) > 255) {
        $error = "Address must be 5–255 characters.";
    }
    // Gender validation
    elseif (empty($gender) || !in_array($gender, ['Male', 'Female', 'Other'])) {
        $error = "Please select a valid gender.";
    }
    // Civil status validation
    elseif (empty($civil_status) || !in_array($civil_status, ['Single', 'Married', 'Divorced', 'Widowed', 'Separated'])) {
        $error = "Please select a valid civil status.";
    }
    // Birthdate validation
    elseif (empty($birthdate)) {
        $error = "Birth date is required.";
    } elseif (!strtotime($birthdate)) {
        $error = "Invalid birth date format.";
    } else {
        // Ensure user is 18+ years old
        $birthDateTime = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDateTime)->y;
        if ($age < 18) {
            $error = "You must be at least 18 years old.";
        }
    }

    // If no errors, proceed with database insertion
    if (empty($error)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("
            INSERT INTO users 
            (fullname, username, email, password, phone, address, gender, civil_status, birthdate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            $error = "Database error. Please try again later.";
        } else {
            $stmt->bind_param(
                "sssssssss",
                $fullname,
                $username,
                $email,
                $hashedPassword,
                $phone,
                $address,
                $gender,
                $civil_status,
                $birthdate
            );

            if ($stmt->execute()) {
                header("Location: dashboard/Homepage.php");
                exit();
            } else {
                // Check for specific database errors (e.g., duplicate email/username)
                if (strpos($stmt->error, 'Duplicate entry') !== false) {
                    $error = "Email or username already exists. Please try another.";
                } else {
                    $error = "Registration failed. Please try again later.";
                }
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
      <img src="../assets/new-logo.png" alt="logo" class="brand-logo"/>
      <span class="brand-name">Well Care Pharmacy</span>
    </div>
  </nav>

  <div class="page-center">
    <div class="card">
      <h1 class="heading">Sign up</h1>

      <?php if (!empty($error)): ?>
      <div style="background-color: #ffebee; color: #c62828; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #c62828;">
        <?php echo htmlspecialchars($error); ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="field">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" placeholder="John Doe" autocomplete="name" value="<?php echo htmlspecialchars($fullname ?? ''); ?>" required/>
        </div>
        <div class="field">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="johndoe" autocomplete="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required/>
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="you@example.com" autocomplete="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required/>
        </div>

        <div class="field">
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" placeholder="+1 (555) 000-0000" autocomplete="tel" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required/>
        </div>
        <div class="field">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" placeholder="123 Main St, City, Country" autocomplete="street-address" value="<?php echo htmlspecialchars($address ?? ''); ?>" required/>
        </div>
        <div class="field">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" required style="width: 100%; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 10px; padding: 13px 16px; font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--text-primary); outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;">
            <option value="">Select Gender</option>
            <option value="Male" <?php echo ($gender === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($gender === 'Female') ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo ($gender === 'Other') ? 'selected' : ''; ?>>Other</option>
          </select>
        </div>
        <div class="field">
          <label for="civil_status">Civil Status</label>
          <select id="civil_status" name="civil_status" required style="width: 100%; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 10px; padding: 13px 16px; font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--text-primary); outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;">
            <option value="">Select Civil Status</option>
            <option value="Single" <?php echo ($civil_status === 'Single') ? 'selected' : ''; ?>>Single</option>
            <option value="Married" <?php echo ($civil_status === 'Married') ? 'selected' : ''; ?>>Married</option>
            <option value="Divorced" <?php echo ($civil_status === 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
            <option value="Widowed" <?php echo ($civil_status === 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
            <option value="Separated" <?php echo ($civil_status === 'Separated') ? 'selected' : ''; ?>>Separated</option>
          </select>
        </div>
        <div class="field">
          <label for="birthdate">Birth Date</label>
          <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($birthdate ?? ''); ?>" required/>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="new-password" value="<?php echo htmlspecialchars($password ?? ''); ?>" required/>
        </div>

        <button class="btn-signup" type="submit">Sign Up</button>
      </form>

      <p class="signin-row">
        Already have an account? <a href="Sign_in.php">Sign In</a>
      </p>
    </div>
  </div>

</body>
</html>