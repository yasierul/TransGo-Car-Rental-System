<?php
// admin/delete_booking.php
require_once __DIR__ . '/../includes/auth_session.php';
require_once __DIR__ . '/../includes/db_connect.php';

require_admin();

if (isset($_GET['booking_id'])) {
    $id = intval($_GET['booking_id']);
    // delete booking
    $stmt = mysqli_prepare($conn, "DELETE FROM bookings WHERE booking_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: manage_bookings.php');
exit;
