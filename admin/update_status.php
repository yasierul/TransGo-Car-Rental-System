<?php
// admin/update_status.php
require_once __DIR__ . '/../includes/auth_session.php';
require_once __DIR__ . '/../includes/db_connect.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $id = intval($_POST['booking_id']);
    $status = $_POST['status'];
    $allowed = ['Pending','Confirmed','Cancelled'];
    if (!in_array($status, $allowed)) $status = 'Pending';

    $stmt = mysqli_prepare($conn, "UPDATE bookings SET status = ? WHERE booking_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: manage_bookings.php');
exit;
