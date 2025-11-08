<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/auth_session.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_admin();

// get counts
$carsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM cars"))['cnt'] ?? 0;
$usersCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users"))['cnt'] ?? 0;
$bookingsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bookings"))['cnt'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard | TransGo</title>
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

    .dashboard-container {
      padding: 100px 80px 60px;
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

    .stats-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      margin-top: 40px;
    }

    .stat-card {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      text-align: center;
      width: 240px;
      transition: transform 0.25s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-card i {
      font-size: 40px;
      color: #004aad;
    }

    .stat-card h2 {
      font-size: 2rem;
      margin: 10px 0 5px;
      color: #004aad;
    }

    .stat-card p {
      color: #777;
      font-weight: 500;
    }

    /* --- BUTTON STYLING --- */
    .btn-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 25px;
      margin-top: 50px;
      flex-wrap: nowrap;
    }

    .btn-primary {
      background: #474fa0;
      color: #fff;
      padding: 12px 24px;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      white-space: nowrap;
      width: auto !important;           /* ✅ force natural width */
      display: inline-block !important; /* ✅ make it fit content */
    }

    .btn-primary:hover {
      background: #004aad;
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
      .dashboard-container {
        padding: 80px 30px;
      }
      .stat-card {
        width: 100%;
      }
      .btn-container {
        flex-direction: column;
        gap: 15px;
      }
      .btn-primary {
        width: auto !important;
      }
    }
  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="dashboard-container">
  <div class="heading">
    <span>Admin Panel</span>
    <h1>Dashboard Overview</h1>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <i class='bx bxs-car'></i>
      <h2><?php echo (int)$carsCount; ?></h2>
      <p>Cars</p>
    </div>

    <div class="stat-card">
      <i class='bx bxs-user'></i>
      <h2><?php echo (int)$usersCount; ?></h2>
      <p>Users</p>
    </div>

    <div class="stat-card">
      <i class='bx bxs-book'></i>
      <h2><?php echo (int)$bookingsCount; ?></h2>
      <p>Bookings</p>
    </div>
  </div>

  <div class="btn-container">
    <a href="manage_bookings.php" class="btn-primary">Manage Bookings</a>
    <a href="manage_cars.php" class="btn-primary">Manage Cars</a>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body>
</html>
