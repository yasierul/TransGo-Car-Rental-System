<?php
// admin/manage_cars.php
require_once __DIR__ . '/../includes/auth_session.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_admin();

// add car
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
  $car_name = trim($_POST['car_name']);
  $brand = trim($_POST['brand']);
  $price = floatval($_POST['price_per_day']);
  $image = trim($_POST['image']);

  if ($car_name && $brand && $price > 0) {
    $stmt = mysqli_prepare($conn, "INSERT INTO cars (car_name, brand, price_per_day, availability, image) VALUES (?, ?, ?, 1, ?)");
    mysqli_stmt_bind_param($stmt, "ssds", $car_name, $brand, $price, $image);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  header("Location: manage_cars.php");
  exit;
}

// delete car
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM cars WHERE car_id=$id");
  header("Location: manage_cars.php");
  exit;
}

// fetch cars
$cars = mysqli_query($conn, "SELECT * FROM cars ORDER BY car_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Cars | TransGo Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #f5f7fb;
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
    }

    .heading h1 {
      font-size: 2rem;
      font-weight: 600;
      margin-top: 10px;
    }

    /* Form Add Car */
    form.add-form {
      max-width: 900px;
      background: #fff;
      margin: 30px auto;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 6px 30px rgba(0,0,0,0.05);
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px 30px;
      align-items: end;
    }

    form.add-form h3 {
      grid-column: span 2;
      margin-bottom: 10px;
      color: #004aad;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 5px;
      color: #333;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #eee;
      background: #f7f8fa;
    }

    .btn-primary {
      background: #474fa0;
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
      border: none;
      cursor: pointer;
      width: auto;
      align-self: center;
      justify-self: center;
    }

    .btn-primary:hover {
      background: #004aad;
    }

    /* Car Grid */
    .cars-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      margin-top: 50px;
    }

    .box {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 30px rgba(0,0,0,0.05);
      overflow: hidden;
      text-align: center;
      transition: 0.3s;
      padding-bottom: 20px;
    }

    .box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 35px rgba(0,0,0,0.08);
    }

    .box-img {
      width: 100%;
      height: 160px;
      overflow: hidden;
    }

    .box-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .box p {
      color: #666;
      font-size: 0.9rem;
      margin: 10px 0 0;
    }

    .box h3 {
      color: #004aad;
      margin: 8px 0;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .box h2 {
      font-size: 1rem;
      color: #474fa0;
      margin: 8px 0;
    }

    .box h2 span {
      color: #555;
      font-size: 0.85rem;
      font-weight: 400;
    }

    .btn-delete {
      background: #e74c3c;
      color: #fff;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s;
      font-size: 0.9rem;
      display: inline-block;
      margin-top: 8px;
    }

    .btn-delete:hover {
      background: #c0392b;
    }

    @media (max-width: 768px) {
      section {
        padding: 100px 30px 60px;
      }

      form.add-form {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<section>
  <div class="heading">
    <span>Admin Panel</span>
    <h1>Manage Cars</h1>
  </div>

  <!-- Add Car Form -->
  <form method="post" class="add-form">
    <h3>Add New Car</h3>

    <div>
      <label>Car Name</label>
      <input type="text" name="car_name" required>
    </div>

    <div>
      <label>Brand</label>
      <input type="text" name="brand" required>
    </div>

    <div>
      <label>Price per Day (RM)</label>
      <input type="number" name="price_per_day" step="0.01" required>
    </div>

    <div>
      <label>Image Filename (in /assets/img/)</label>
      <input type="text" name="image" placeholder="example: c1.jpg">
    </div>

    <div style="grid-column: span 2; text-align: center;">
      <button type="submit" name="add_car" class="btn-primary">Add Car</button>
    </div>
  </form>

  <!-- Cars Grid -->
  <div class="cars-grid">
    <?php if (mysqli_num_rows($cars) > 0): ?>
      <?php while ($car = mysqli_fetch_assoc($cars)): ?>
        <div class="box">
          <div class="box-img">
            <img src="../assets/img/<?php echo htmlspecialchars($car['image'] ?: 'c1.jpg'); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>">
          </div>
          <p><?php echo htmlspecialchars($car['brand']); ?></p>
          <h3><?php echo htmlspecialchars($car['car_name']); ?></h3>
          <h2>RM <?php echo number_format($car['price_per_day'], 2); ?> <span>/day</span></h2>
          <a href="?delete=<?php echo $car['car_id']; ?>" class="btn-delete" onclick="return confirm('Delete this car?')">Delete</a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;grid-column:1/-1;">No cars found.</p>
    <?php endif; ?>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body>
</html>
