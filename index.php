<?php include('includes/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransGo | Rent It Now!</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>
<body>

<?php include('includes/header.php'); ?>

<section class="home" id="home">
    <div class="text">
        <h1>LOOKING FOR <span>RENT</span><br>A CAR?</h1>
        <p>Discover affordable and reliable car rental services with just a few clicks.<br>Choose from a wide range of vehicles that suit your journey.</p>
        <div class="app-stores">
            <a href="https://www.apple.com/my/app-store/" target="_blank">
  <img src="assets/img/ios.png" alt="Download on App Store">
</a>

            <a href="https://play.google.com/store/apps?hl=en&pli=1" target="_blank">
  <img src="assets/img/play.png" alt="Get it on Google Play">
</a>

        </div>
    </div>

    <div class="form-container">
        <form action="">
            <div class="input-box">
                <span>Location</span>
                <input type="search" placeholder="Search Places">
            </div>
            <div class="input-box">
                <span>Pick Up Date</span>
                <input type="date">
            </div>
            <div class="input-box">
                <span>Return Date</span>
                <input type="date">
            </div>
            <a href="/TransGo/index.php#services" class="btn">Browse</a>

        </form>
    </div>
</section>

<section class="ride" id="ride">
    <div class="heading">
        <span>How It Works</span>
        <h1>Rent With 3 Easy Steps</h1>
    </div>
    <div class="ride-container">
        <div class="box">
            <i class='bx bxs-map'></i>
            <h2>Choose a Location</h2>
            <p>Find the nearest rental branch or pick the city where you want to start your journey.</p>
        </div>

        <div class="box">
            <i class='bx bxs-calendar-check'></i>
            <h2>Pick Up Date</h2>
            <p>Select the date and time that works best for your travel schedule.</p>
        </div>

        <div class="box">
            <i class='bx bxs-calendar-star'></i>
            <h2>Book A Car</h2>
            <p>Browse our wide range of vehicles and secure the perfect ride in just a few clicks.</p>
        </div>
    </div>
</section>

<section class="services" id="services">
    <div class="heading">
        <span>Best Services</span>
        <h1>Explore Our Top Deals<br>From Top Rated Dealers</h1>
    </div>
    <div class="services-container">
        <?php
        // Fetch cars from DB (if added later)
        $sql = "SELECT * FROM cars LIMIT 8";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($car = mysqli_fetch_assoc($result)) {
                echo "
                <div class='box'>
                    <div class='box-img'><img src='assets/img/{$car['image']}' alt='{$car['car_name']}'></div>
                    <p>{$car['brand']}</p>
                    <h3>{$car['car_name']}</h3>
                    <h2>RM {$car['price_per_day']} <span>/day</span></h2>
                    <a href='customer/booking.php?car_id={$car['car_id']}' class='btn'>Rent Now</a>
                </div>";
            }
        } else {
            // fallback: static cards if no DB data yet
            for ($i=1; $i<=8; $i++) {
                echo "
                <div class='box'>
                    <div class='box-img'><img src='assets/img/c{$i}.jpg' alt='Car'></div>
                    <p>2024</p>
                    <h3>Sample Car {$i}</h3>
                    <h2>RM 0000 <span>/day</span></h2>
                    <a href='#' class='btn'>Rent Now</a>
                </div>";
            }
        }
        ?>
    </div>
</section>

<section class="about" id="about">
    <div class="heading">
        <span>About Us</span>
        <h1>Best Customer Experience</h1>
    </div>
    <div class="about-container">
        <div class="about-img">
            <img src="assets/img/myvi.png" alt="">
        </div>
        <div class="about-text">
            <span>About Us</span>
            <p>We are committed to making car rental simple, affordable, and reliable. Our mission is to provide customers with a smooth rental experience from choosing the right vehicle to driving away with confidence.</p>
            <p>At TransGo, we believe renting a car should be fast, easy, and worry-free. That’s why we’ve built a service that gives you the freedom to drive anytime, anywhere without hidden costs or hassle.</p>
            <a href="register.php" class="btn">Register With Us</a>

        </div>
    </div>
</section>

<section class="reviews" id="reviews">
    <div class="heading">
        <span>Reviews</span>
        <h1>What Our Customers Say</h1>
    </div>
    <div class="reviews-container">
        <div class="box">
            <div class="rev-img">
             <i class='bx bxs-user-circle'></i>
            </div>
            <h2>Olivia Rodrigo</h2>
            <div class="stars">
                <i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                 <i class='bx bxs-star-half'></i>
            </div>
            <p>Booking was quick and easy. The car was clean and reliable, making my trip completely hassle-free. Highly recommend!</p>
        </div>

        <div class="box">
            <div class="rev-img">
             <i class='bx bxs-user-circle'></i>
            </div>
            <h2>Anuar Yaakob</h2>
            <div class="stars">
                <i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                 <i class='bx bxs-star-half'></i>
            </div>
            <p>I had a wonderful holiday thanks to their car rental. Pick-up was fast, and the car ran perfectly throughout my trip.</p>
        </div>

        <div class="box">
            <div class="rev-img">
             <i class='bx bxs-user-circle'></i>
            </div>
            <h2>M. Hayabusa</h2>
            <div class="stars">
                <i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                 <i class='bx bxs-star-half'></i>
            </div>

            <p>Great service at an affordable price. The staff were friendly and made the whole process smooth and simple.</p>
        </div>
    </div>
</section>

<section class="newsletter">
    <h2>Subscribe To Newsletter</h2>
    <div class="box">
        <input type="text" placeholder="Enter Your Email">
        <a href="#" class="btn">Subscribe</a>
    </div>
</section>

<?php include('includes/footer.php'); ?>
<script src="assets/js/main.js"></script>
</body>
</html>
