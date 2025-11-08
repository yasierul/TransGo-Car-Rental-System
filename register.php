<?php
// register.php
session_start();
require_once 'includes/db_connect.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "Please fill all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "Email already registered. Try logging in.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'customer';
            $insert = mysqli_prepare($conn, "INSERT INTO users (name,email,password,role) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "ssss", $name, $email, $hash, $role);
            if (mysqli_stmt_execute($insert)) {
                $success = "Registration successful. You can now log in.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            mysqli_stmt_close($insert);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register | TransGo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

  <style>
    body {
      background: #f5f7fb;
      font-family: "Poppins", sans-serif;
      margin: 0;
      color: #444;
    }

    .register-container {
      max-width: 450px;
      margin: 120px auto;
      background: #fff;
      padding: 40px 30px;
      border-radius: 10px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .register-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #004aad;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
      background: #f7f8fa;
    }

    .error-msg {
      background: #ffe0e0;
      color: #c0392b;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      text-align: center;
      font-size: 0.95rem;
    }

    .success-msg {
      background: #e6ffec;
      color: #2e8b57;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      text-align: center;
      font-size: 0.95rem;
    }

    /* ✅ TransGo blue button */
    .btn-primary {
      background: #474fa0;
      color: #fff;
      padding: 12px 20px;
      border: none;
      border-radius: 0.5rem;
      font-size: 1rem;
      font-weight: 500;
      width: 100%;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: #004aad;
    }

    .register-footer {
      text-align: center;
      margin-top: 15px;
      font-size: 0.95rem;
    }

    .register-footer a {
      color: #004aad;
      text-decoration: none;
      font-weight: 500;
    }

    .register-footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <div class="register-container">
    <h2>Create Your TransGo Account</h2>

    <?php if ($errors): ?>
      <div class="error-msg">
        <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success-msg"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Your name" required>
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="you@example.com" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter password" required>
      </div>

      <div class="form-group">
        <label for="confirm">Confirm Password</label>
        <input type="password" name="confirm" id="confirm" placeholder="Re-enter password" required>
      </div>

      <!-- ✅ Butang biru TransGo -->
      <button class="btn-primary" type="submit">Create Account</button>
    </form>

    <div class="register-footer">
      <p>Already have an account? <a href="login.php">Sign In</a></p>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
  <script src="assets/js/main.js"></script>
</body>
</html>
