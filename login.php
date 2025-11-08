<?php
// login.php
require_once __DIR__ . '/includes/db_connect.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // redirect ikut role
                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: customer/dashboard.php');
                }
                exit;
            } else {
                $error = 'Invalid password.';
            }
        } else {
            $error = 'User not found.';
        }
        $stmt->close();
    } else {
        $error = 'Please enter both email and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | TransGo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

  <style>
    /* Page layout */
    body {
      background: #f5f7fb;
      font-family: "Poppins", sans-serif;
      margin: 0;
      color: #444;
    }

    .login-container {
      max-width: 400px;
      margin: 120px auto;
      background: #fff;
      padding: 40px 30px;
      border-radius: 10px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .login-container h2 {
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

    /* ✅ TransGo blue button style */
    .btn-primary {
      background: #474fa0; /* Biru utama */
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
      background: #004aad; /* Biru pekat bila hover */
    }

    .login-footer {
      text-align: center;
      margin-top: 15px;
      font-size: 0.95rem;
    }

    .login-footer a {
      color: #004aad;
      text-decoration: none;
      font-weight: 500;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }

  </style>
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <div class="login-container">
    <h2>Sign In to TransGo</h2>

    <?php if ($error): ?>
      <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="you@example.com" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
      </div>

      <!-- ✅ Butang login style TransGo -->
      <button class="btn-primary" type="submit">Sign In</button>
    </form>

    <div class="login-footer">
      <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
  <script src="assets/js/main.js"></script>
</body>
</html>
