<?php
// admin/manage_bookings.php
require_once __DIR__ . '/../includes/auth_session.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_admin();

// fetch bookings with joined user & car
$sql = "SELECT 
          b.booking_id, b.user_id, b.car_id, b.start_date, b.end_date, b.total_price, b.status, b.created_at,
          u.name AS user_name, u.email AS user_email,
          c.car_name, c.image
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN cars c ON b.car_id = c.car_id
        ORDER BY b.created_at DESC";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Bookings | TransGo Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

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

    .heading span {
      color: #004aad;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 0.95rem;
    }

    .heading h1 {
      font-size: 2rem;
      font-weight: 600;
      margin: 10px 0 0;
    }

    .table-container {
      margin-top: 30px;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 6px 30px rgba(0,0,0,0.05);
      overflow-x: auto;
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
      padding: 10px;
      border-top: 1px solid #eee;
      font-size: 0.95rem;
    }

    td img {
      width: 80px;
      height: 50px;
      border-radius: 6px;
      object-fit: cover;
    }

    select {
      padding: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    /* ✅ Delete button (red solid) */
    .btn-delete {
      background: #e74c3c;
      color: #fff;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
      display: inline-block;
    }

    .btn-delete:hover {
      background: #c0392b;
      box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    /* ✅ Blue button (for back or manage etc) */
    .btn-primary {
      background: #474fa0;
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: #004aad;
    }

    .btn-container {
      text-align: center;
      margin-top: 40px;
    }

    @media (max-width: 768px) {
      section {
        padding: 80px 30px;
      }
      table {
        font-size: 0.85rem;
      }
      th, td {
        padding: 8px;
      }
    }
  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<section>
  <div class="heading">
    <span>Admin Panel</span>
    <h1>Manage Bookings</h1>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Customer</th>
          <th>Car</th>
          <th>Pick-up</th>
          <th>Return</th>
          <th>Days</th>
          <th>Total (RM)</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($res && mysqli_num_rows($res) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($res)):
          $start = $row['start_date'];
          $end = $row['end_date'];
          $d1 = new DateTime($start);
          $d2 = new DateTime($end);
          $days = ((int)$d1->diff($d2)->format('%a')) + 1;
        ?>
        <tr>
          <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
          <td>
            <strong><?php echo htmlspecialchars($row['user_name']); ?></strong><br>
            <span style="color:#777;"><?php echo htmlspecialchars($row['user_email']); ?></span>
          </td>
          <td>
            <div style="display:flex; align-items:center; gap:10px; justify-content:center;">
              <img src="../assets/img/<?php echo htmlspecialchars($row['image'] ?: 'c1.jpg'); ?>">
              <div><?php echo htmlspecialchars($row['car_name']); ?></div>
            </div>
          </td>
          <td><?php echo htmlspecialchars($start); ?></td>
          <td><?php echo htmlspecialchars($end); ?></td>
          <td><?php echo (int)$days; ?></td>
          <td><?php echo number_format($row['total_price'],2); ?></td>
          <td>
            <form method="post" action="update_status.php" style="margin:0;">
              <input type="hidden" name="booking_id" value="<?php echo (int)$row['booking_id']; ?>">
              <select name="status" onchange="this.form.submit()">
                <option value="Pending" <?php echo $row['status']=='Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Confirmed" <?php echo $row['status']=='Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="Cancelled" <?php echo $row['status']=='Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
              </select>
            </form>
          </td>
          <td>
            <a href="delete_booking.php?booking_id=<?php echo (int)$row['booking_id']; ?>" 
               class="btn-delete"
               onclick="return confirm('Delete booking #<?php echo (int)$row['booking_id']; ?>?')">
               Delete
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="9" style="text-align:center; padding:20px;">No bookings found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="btn-container">
    <a href="dashboard.php" class="btn-primary">Back to Dashboard</a>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body>
</html>
