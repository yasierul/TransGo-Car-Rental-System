<?php
// customer/booking.php
require_once '../includes/db_connect.php';
require_once '../includes/auth_session.php';
require_login();

$user_id = $_SESSION['user_id'] ?? null;

$car_id = intval($_GET['car_id'] ?? 0);
$errors = [];
$success = '';

if ($car_id <= 0) {
    $errors[] = "Invalid car selected.";
} else {
    // load car details
    $stmt = mysqli_prepare($conn, "SELECT car_id, car_name, brand, price_per_day, availability, image FROM cars WHERE car_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $car_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $c_id, $car_name, $brand, $price_per_day, $availability, $image);
    if (!mysqli_stmt_fetch($stmt)) {
        $errors[] = "Car not found.";
    }
    mysqli_stmt_close($stmt);
}

// handle booking form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if (empty($start_date) || empty($end_date)) {
        $errors[] = "Please fill start and end dates.";
    } else {
        $d1 = DateTime::createFromFormat('Y-m-d', $start_date);
        $d2 = DateTime::createFromFormat('Y-m-d', $end_date);
        if (!$d1 || !$d2) {
            $errors[] = "Invalid dates.";
        } else {
            $interval = $d1->diff($d2);
            $days = (int)$interval->format('%a') + 1; // inclusive
            if ($days <= 0) {
                $errors[] = "Return date must be on/after start date.";
            } else {
                $total = $days * floatval($price_per_day);
                $ins = mysqli_prepare($conn, "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($ins, "iissd", $user_id, $car_id, $start_date, $end_date, $total);
                if (mysqli_stmt_execute($ins)) {
                    $success = "✅ Booking successful! Total RM " . number_format($total, 2) . " — Status: Pending.";
                } else {
                    $errors[] = "Failed to create booking.";
                }
                mysqli_stmt_close($ins);
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Book Car - TransGo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/TransGo/assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section style="padding:100px 100px 80px;">
  <div class="heading">
    <span>Booking</span>
    <h1>Confirm Your Car Rental</h1>
  </div>

  <?php if ($errors): ?>
    <div style="max-width:800px;margin:1rem auto;color:#a00;background:#ffe6e6;padding:10px;border-radius:6px;">
      <?php foreach($errors as $e) echo "<div>{$e}</div>"; ?>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
  <div style="
    max-width:800px;
    margin:1rem auto;
    color:green;
    text-align:center;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 30px rgba(0,0,0,0.06);
    font-family:'Poppins',sans-serif;
  ">
    <div style="font-size:1.1rem; margin-bottom:12px;"><?php echo $success; ?></div>
    <a href="payment.php"
       class="btn"
       style="
         background:#004aad;
         color:#fff;
         padding:10px 18px;
         border-radius:8px;
         text-decoration:none;
         font-weight:500;
         display:inline-block;
         transition:0.3s;
       "
       onmouseover="this.style.background='#474fa0'"
       onmouseout="this.style.background='#004aad'">
       Make Payment
    </a>
  </div>
<?php endif; ?>




  <?php if (empty($errors) || !empty($car_name)): ?>
    <div style="max-width:900px; margin:2rem auto; display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
      
      <!-- Car details -->
      <div style="background:#fff;padding:20px;border-radius:12px; box-shadow:0 6px 30px rgba(0,0,0,0.06);">
        <img src="/TransGo/assets/img/<?php echo htmlspecialchars($image ?: 'c1.jpg'); ?>" 
             style="width:100%;height:260px;object-fit:cover;border-radius:8px;" alt="">
        <h2 style="margin:15px 0 5px;"><?php echo htmlspecialchars($car_name); ?></h2>
        <p style="color:#777; margin-bottom:10px;"><?php echo htmlspecialchars($brand); ?></p>
        <h3 style="color:#004aad;">RM <?php echo number_format($price_per_day,2); ?> 
          <span style="font-size:0.85rem;color:#666;">/ day</span></h3>
      </div>

      <!-- Booking form -->
      <div style="background:#fff;padding:20px;border-radius:12px; box-shadow:0 6px 30px rgba(0,0,0,0.06);">
        <form method="post" id="bookingForm">
          <label style="font-weight:500;">Pick-Up Date</label>
          <input type="date" name="start_date" id="start_date" required 
                 style="width:100%; padding:10px; margin:6px 0; border-radius:6px; border:1px solid #eee; background:#f7f8fa">

          <label style="font-weight:500;">Return Date</label>
          <input type="date" name="end_date" id="end_date" required 
                 style="width:100%; padding:10px; margin:6px 0 14px; border-radius:6px; border:1px solid #eee; background:#f7f8fa">

          <div style="margin-bottom:16px; font-size:1rem;">
            <strong>Days:</strong> <span id="daysCount">0</span><br>
            <strong>Total:</strong> RM <span id="totalPrice">0.00</span>
          </div>

          <button class="btn-primary" type="submit">Confirm Booking</button>
        </form>
      </div>

    </div>
  <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>

<script src="/TransGo/assets/js/main.js"></script>
<script>
  // Booking price calculation
  const pricePerDay = <?php echo json_encode((float)$price_per_day); ?>;
  const startInput = document.querySelector('#start_date');
  const endInput = document.querySelector('#end_date');
  const daysCountEl = document.querySelector('#daysCount');
  const totalPriceEl = document.querySelector('#totalPrice');

  function calc() {
    const s = startInput.value;
    const e = endInput.value;
    if (!s || !e) {
      daysCountEl.textContent = '0';
      totalPriceEl.textContent = '0.00';
      return;
    }
    const d1 = new Date(s);
    const d2 = new Date(e);
    const diff = Math.floor((d2 - d1) / (1000 * 60 * 60 * 24));
    const days = diff >= 0 ? diff + 1 : 0;
    daysCountEl.textContent = days;
    totalPriceEl.textContent = (days * pricePerDay).toFixed(2);
  }

  startInput && startInput.addEventListener('change', calc);
  endInput && endInput.addEventListener('change', calc);
</script>
</body>
</html>
