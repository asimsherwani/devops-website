<?php
require 'db.php';

$make = $_GET['make'] ?? '';
$city = $_GET['city'] ?? '';

$sql = "SELECT * FROM cars WHERE 1=1";
$params = [];
$types = "";

if ($make !== '') {
    $sql .= " AND make = ?";
    $params[] = $make;
    $types .= "s";
}

if ($city !== '') {
    $sql .= " AND city = ?";
    $params[] = $city;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DriveDeal.pk | Premium Car Marketplace</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <div class="container nav">

        <a href="index.php" class="brand-logo">
    <img src="logo.png" alt="DriveDeal.pk">
</a>

        <nav>
            <a href="index.php">Home</a>
            <a href="#cars">Buy Cars</a>
            <a href="sell.php">Sell Your Car</a>
            <a href="#about">About</a>
        </nav>

        <a href="#sell.php" class="sell-btn">
                + List Your Car
        </a>

    </div>
</header>


<section class="hero">

    <div class="container hero-content">

        <p class="hero-small">
            PAKISTAN'S PREMIUM CAR MARKETPLACE
        </p>

        <h1>
            Find Your Perfect
            <span>Drive.</span>
        </h1>

        <p class="hero-text">
            Discover quality vehicles from trusted sellers.
            Compare specifications, prices and vehicle details
            to find the right car for you.
        </p>


        <form class="search-box" method="GET">

            <select name="make">

                <option value="">All Makes</option>

                <option value="Toyota"
                    <?= $make === 'Toyota' ? 'selected' : '' ?>>
                    Toyota
                </option>

                <option value="Honda"
                    <?= $make === 'Honda' ? 'selected' : '' ?>>
                    Honda
                </option>

                <option value="KIA"
                    <?= $make === 'KIA' ? 'selected' : '' ?>>
                    KIA
                </option>

                <option value="Suzuki"
                    <?= $make === 'Suzuki' ? 'selected' : '' ?>>
                    Suzuki
                </option>

                <option value="Hyundai"
                    <?= $make === 'Hyundai' ? 'selected' : '' ?>>
                    Hyundai
                </option>

            </select>


            <select name="city">

                <option value="">All Cities</option>

                <option value="Islamabad"
                    <?= $city === 'Islamabad' ? 'selected' : '' ?>>
                    Islamabad
                </option>

                <option value="Lahore"
                    <?= $city === 'Lahore' ? 'selected' : '' ?>>
                    Lahore
                </option>

                <option value="Karachi"
                    <?= $city === 'Karachi' ? 'selected' : '' ?>>
                    Karachi
                </option>

            </select>


            <button type="submit">
                Search Cars
            </button>

            <a class="reset-search" href="index.php">
                Reset
            </a>

        </form>

    </div>

</section>


<section class="stats">

    <div class="container stats-grid">

        <div>
            <h2>500+</h2>
            <p>Cars Listed</p>
        </div>

        <div>
            <h2>350+</h2>
            <p>Happy Buyers</p>
        </div>

        <div>
            <h2>200+</h2>
            <p>Verified Sellers</p>
        </div>

        <div>
            <h2>15+</h2>
            <p>Cities Covered</p>
        </div>

    </div>

</section>


<section id="cars" class="cars-section">

<div class="container">

    <div class="section-heading">

        <div>
            <p class="small-heading">
                FEATURED VEHICLES
            </p>

            <h2>
                Find Your Next Car
            </h2>
        </div>

        <p class="vehicle-count">
            <?= $result->num_rows ?> vehicles found
        </p>

    </div>


    <div class="car-grid">

    <?php if ($result->num_rows > 0): ?>

        <?php while ($car = $result->fetch_assoc()): ?>

        <div class="car-card">

            <div class="car-image">

                <img
                    src="<?= htmlspecialchars($car['image']) ?>?auto=format&fit=crop&w=900&q=80"
                    alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>"
                >

                <div class="year">
                    <?= htmlspecialchars($car['year']) ?>
                </div>

            </div>


            <div class="car-info">

                <div class="location">
                    📍 <?= htmlspecialchars($car['city']) ?>
                </div>

                <h3>
                    <?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>
                </h3>


                <div class="specs">

                    <span>
                        ⚙️ <?= htmlspecialchars($car['transmission']) ?>
                    </span>

                    <span>
                        ⛽ <?= htmlspecialchars($car['fuel_type']) ?>
                    </span>

                    <span>
                        🚘 <?= number_format($car['mileage']) ?> km
                    </span>

                    <span>
                        🔧 <?= htmlspecialchars($car['engine']) ?>
                    </span>

                </div>


                <div class="price">

                    <div>

                        <small>PRICE</small>

                        <h4>
                            PKR <?= number_format($car['price']) ?>
                        </h4>

                    </div>

                    <a href="car.php?id=<?= $car['id'] ?>">
                        View Details
                    </a>

                </div>

            </div>

        </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="no-results">

            <h3>No vehicles found</h3>

            <p>
                Try changing your search filters.
            </p>

            <a href="index.php">
                View All Cars
            </a>

        </div>

    <?php endif; ?>

    </div>

</div>

</section>


<section id="sell" class="sell-section">

<div class="container sell-content">

    <div>

        <p class="small-heading-light">
            READY TO SELL?
        </p>

        <h2>
            Sell Your Car With Confidence
        </h2>

        <span>
            Reach thousands of potential buyers and
            get the best value for your vehicle.
        </span>

    </div>

    <a href="#">
        List Your Car
    </a>

</div>

</section>


<section id="about" class="about-section">

<div class="container about-grid">

    <div>

        <p class="small-heading">
            WHY DRIVEDEAL.PK?
        </p>

        <h2>
            Buying a car should be simple.
        </h2>

        <p>
            DriveDeal.pk is a modern vehicle marketplace designed to connect car buyers and sellers across Pakistan.
        </p>

    </div>


    <div class="features">

        <div>
            <strong>✓ Verified Listings</strong>
            <p>Detailed information about every vehicle.</p>
        </div>

        <div>
            <strong>✓ Detailed Specifications</strong>
            <p>Compare engine, mileage and transmission.</p>
        </div>

        <div>
            <strong>✓ Direct Seller Information</strong>
            <p>View seller information from each listing.</p>
        </div>

    </div>

</div>

</section>


<footer>

<div class="container footer-grid">

    <div>

        <div class="logo">
            Auto<span>Drive</span>
        </div>

        <p>
            A modern demonstration marketplace connecting
            car buyers and sellers across Pakistan.
        </p>

    </div>


    <div>

        <h4>Marketplace</h4>

        <p>Buy Cars</p>
        <p>Sell Cars</p>
        <p>Featured Vehicles</p>

    </div>


    <div>

        <h4>Company</h4>

        <p>About</p>
        <p>Contact</p>
        <p>Privacy Policy</p>

    </div>


    <div>

        <h4>Support</h4>

        <p>Help Center</p>
        <p>Safety Tips</p>
        <p>Terms & Conditions</p>

    </div>

</div>


<div class="copyright">
    © <?= date("Y") ?> DriveDeal.pk , Buy Smart. Sell Fast. Drive Happy.
</div>

</footer>

</body>
</html>