<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<header>
    <a href="/TransGo/index.php" class="logo"><img src="/TransGo/assets/img/carlogo.png" alt="Logo"></a>

    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
        <li><a href="/TransGo/index.php#home">Home</a></li>
        <li><a href="/TransGo/index.php#ride">Ride</a></li>
        <li><a href="/TransGo/index.php#services">Services</a></li>
        <li><a href="/TransGo/index.php#about">About</a></li>
        <li><a href="/TransGo/index.php#reviews">Reviews</a></li>
    </ul>

    <div class="header-btn">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/TransGo/admin/dashboard.php" class="sign-up">Admin</a>
            <?php else: ?>
                <a href="/TransGo/customer/dashboard.php" class="sign-up">Dashboard</a>
            <?php endif; ?>
            <a href="/TransGo/logout.php" class="sign-in">Logout</a>
        <?php else: ?>
            <a href="/TransGo/register.php" class="sign-up">Sign Up</a>
            <a href="/TransGo/login.php" class="sign-in">Sign In</a>
        <?php endif; ?>
    </div>
</header>
