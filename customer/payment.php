<?php
// customer/payment.php
require_once '../includes/db_connect.php';
require_once '../includes/auth_session.php';
require_login();

$user_id = $_SESSION['user_id'] ?? null;
$booking_id = intval($_GET['booking_id'] ?? 0);
$success = '';
$errors = [];
$amount = 0;

// validate booking
if ($booking_id > 0) {
  $stmt = mysqli_prepare($conn, "SELECT total_price, status FROM bookings WHERE booking_id=? AND user_id=?");
  mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $amount, $booking_status);
  if (!mysqli_stmt_fetch($stmt)) {
    $errors[] = "Booking not found or unauthorized.";
  }
  mysqli_stmt_close($stmt);

  if ($booking_status === 'Confirmed') {
    $errors[] = "This booking has already been paid.";
  }
} else {
  $errors[] = "Invalid booking ID.";
}

// handle payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
  $card = trim($_POST['card'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $expiry = trim($_POST['expiry'] ?? '');
  $cvv = trim($_POST['cvv'] ?? '');

  if (empty($card) || empty($name) || empty($expiry) || empty($cvv)) {
    $errors[] = "Please fill in all fields.";
  } elseif (!preg_match('/^[0-9]{16}$/', $card)) {
    $errors[] = "Invalid card number. Must be 16 digits.";
  } else {
    // insert payment record
    $ins = mysqli_prepare($conn, "INSERT INTO payments (booking_id, user_id, amount, card_number, status) VALUES (?, ?, ?, ?, 'Success')");
    mysqli_stmt_bind_param($ins, "iids", $booking_id, $user_id, $amount, $card);
    if (mysqli_stmt_execute($ins)) {
      // update booking status
      mysqli_query($conn, "UPDATE bookings SET status='Confirmed' WHERE booking_id=$booking_id");
      $success = "✅ Payment successful! Thank you for your booking.";
    } else {
      $errors[] = "Payment failed. Please try again.";
    }
    mysqli_stmt_close($ins);
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Make Payment - TransGo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/TransGo/assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #f5f7fb;
      color: #444;
      margin: 0;
    }
    .heading h1 {
      text-align: center;
      color: #004aad;
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    .payment-box {
      max-width: 520px;
      margin: 2rem auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 6px 30px rgba(0,0,0,0.06);
    }
    input {
      width: 100%;
      padding: 10px;
      margin: 6px 0 12px;
      border-radius: 6px;
      border: 1px solid #eee;
      background: #f7f8fa;
    }
    label {
      font-weight: 500;
      color: #333;
    }
    .btn {
      background: #474fa0;
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
      display: inline-block;
      border: none;
      cursor: pointer;
      text-align: center;
      width: 100%;
    }
    .btn:hover {
      background: #004aad;
    }
    .success-box {
      color: green;
      margin-bottom: 15px;
      text-align: center;
      background: #e6ffe6;
      padding: 12px;
      border-radius: 8px;
    }
    .error-box {
      color: #a00;
      background: #ffe6e6;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<section style="padding:100px 100px 80px;">
  <div class="heading"><h1>Make Payment</h1></div>

  <div class="payment-box">
    <?php if ($errors): ?>
      <div class="error-box">
        <?php foreach ($errors as $e) echo "<div>{$e}</div>"; ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success-box"><?php echo $success; ?></div>
      <div style="text-align:center;">
        <a href="dashboard.php" class="btn" style="width:auto;">Go to Dashboard</a>
      </div>
    <?php elseif (empty($errors)): ?>
      <form method="post" action="payment.php?booking_id=<?php echo htmlspecialchars($booking_id); ?>">
        <div style="text-align:center; margin-bottom:10px; font-weight:600; color:#004aad;">
          Total Payment: RM <?php echo number_format($amount, 2); ?>
        </div>

        <label>Cardholder Name</label>
        <input type="text" name="name" placeholder="Name" required>

        <label>Card Number</label>
        <input type="text" name="card" placeholder="1234567890123456" maxlength="16" required>

        <div style="display:flex; gap:10px;">
          <div style="flex:1;">
            <label>Expiry Date</label>
            <input type="text" name="expiry" placeholder="MM/YY" required>
          </div>
          <div style="flex:1;">
            <label>CVV</label>
            <input type="password" name="cvv" placeholder="123" maxlength="3" required>
          </div>
        </div>

        <button class="btn" type="submit">Confirm Payment</button>
      </form>
    <?php endif; ?>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<script src="/TransGo/assets/js/main.js"></script>
</body>
</html>
