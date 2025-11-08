<?php
// customer/dashboard.php
require_once '../includes/db_connect.php';
require_once '../includes/auth_session.php';
require_login();
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Bookings | TransGo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

  <style>
    body {
      background: #f5f7fb;
      font-family: "Poppins", sans-serif;
      color: #444;
      margin: 0;
    }

    section {
      padding: 100px 80px 80px;
    }

    .heading {
      text-align: center;
    }

    .heading h1 {
      font-size: 2rem;
      font-weight: 600;
      color: #004aad;
      margin-bottom: 10px;
    }

    .btn-primary {
      background: #474fa0;
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
      display: inline-block;
      width: auto;
    }

    .btn-primary:hover {
      background: #004aad;
    }

    .table-container {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 6px 30px rgba(0,0,0,0.05);
      overflow-x: auto;
      max-width: 1000px;
      margin: 1.5rem auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
    }

    th {
      background: #f2f2f2;
      padding: 12px;
      text-transform: uppercase;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }

    td {
      padding: 12px;
      border-top: 1px solid #eee;
      font-size: 0.95rem;
    }

    td img {
      width: 90px;
      height: 55px;
      border-radius: 6px;
      object-fit: cover;
      vertical-align: middle;
      margin-right: 10px;
    }

    .no-booking {
      text-align: center;
      padding: 20px;
      color: #777;
    }

    @media (max-width: 768px) {
      section {
        padding: 80px 30px;
      }
      table {
        font-size: 0.85rem;
      }
      td img {
        width: 70px;
        height: 45px;
      }
    }
  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<section>
  <div class="heading">
    <span>My Booking</span>
    <h1>Records of Booking</h1>
  </div>

  <div style="max-width:1000px; margin:0 auto; text-align:right;">
    <a href="../index.php#services" class="btn-primary">Browse Cars</a>
  </div>

  <div class="table-container">
    <?php
      $sql = "SELECT b.booking_id, b.start_date, b.end_date, b.total_price, b.status, c.car_name, c.image
              FROM bookings b
              JOIN cars c ON b.car_id = c.car_id
              WHERE b.user_id = ?
              ORDER BY b.created_at DESC";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "i", $user_id);
      mysqli_stmt_execute($stmt);
      $res = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($res) === 0) {
        echo "<p class='no-booking'>No bookings yet.</p>";
      } else {
        echo "<table>";
        echo "<thead><tr><th>Car</th><th>From</th><th>To</th><th>Total (RM)</th><th>Status</th><th>Action</th></tr></thead><tbody>";

        while ($r = mysqli_fetch_assoc($res)) {
          echo "<tr>";
          echo "<td style='text-align:left;'>
                  <img src='../assets/img/".htmlspecialchars($r['image'])."' alt='Car Image'> 
                  ".htmlspecialchars($r['car_name'])."
                </td>";
          echo "<td>".htmlspecialchars($r['start_date'])."</td>";
          echo "<td>".htmlspecialchars($r['end_date'])."</td>";
          echo "<td>".number_format($r['total_price'],2)."</td>";
          echo "<td>".htmlspecialchars($r['status'])."</td>";

          // ✅ Only show "Make Payment" button if booking is still Pending
          echo "<td>";
          if (strtolower($r['status']) === 'pending') {
            echo "<a href='payment.php?booking_id=".urlencode($r['booking_id'])."' 
                    class='btn-primary' 
                    style=\"
                      background:#004aad;
                      color:#fff;
                      padding:8px 14px;
                      border-radius:8px;
                      text-decoration:none;
                      font-weight:500;
                      display:inline-block;
                      transition:0.3s;
                    \"
                    onmouseover=\"this.style.background='#003080'\"
                    onmouseout=\"this.style.background='#004aad'\">
                    Make Payment
                  </a>";
          } else {
            echo "<span style='color:#777;'>—</span>";
          }
          echo "</td>";

          echo "</tr>";
        }

        echo "</tbody></table>";
      }
      mysqli_stmt_close($stmt);
    ?>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body>
</html>
