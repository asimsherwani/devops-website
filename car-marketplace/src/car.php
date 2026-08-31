<?php

require 'db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    http_response_code(404);
    die("Vehicle not found.");
}

/* Get all uploaded pictures for this car */

$carImages = [];

$imageStmt = $conn->prepare(
    "SELECT image_path
     FROM car_images
     WHERE car_id = ?
     ORDER BY id ASC"
);

if ($imageStmt) {
    $imageStmt->bind_param("i", $id);
    $imageStmt->execute();

    $imageResult = $imageStmt->get_result();

    while ($imageRow = $imageResult->fetch_assoc()) {
        $carImages[] = $imageRow['image_path'];
    }
}

/*
 * Existing demo cars do not have records in car_images,
 * so use their existing main image.
 */
if (empty($carImages) && !empty($car['image'])) {
    $carImages[] = $car['image'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?> | DriveDeal.pk
</title>

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
        <a href="index.php#cars">Buy Cars</a>
        <a href="sell.php">Sell Your Car</a>
        <a href="index.php#about">About</a>
    </nav>

    <a href="index.php#cars" class="sell-btn">
        ← Back to Cars
    </a>

</div>

</header>


<section class="detail-section">

<div class="container">
<?php if (isset($_GET['listed']) && $_GET['listed'] === '1'): ?>

        <div class="listing-success">
            ✓ Your car has been listed successfully on DriveDeal.pk.
        </div>

    <?php endif; ?>

    <a href="index.php#cars" class="back-link">
        ← Back to vehicle listings
    </a>

    <a href="index.php#cars" class="back-link">
        ← Back to vehicle listings
    </a>


    <div class="detail-grid">

<div class="detail-image">

    <img
        id="mainCarImage"
        src="<?= htmlspecialchars($carImages[0]) ?>"
        alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>"
    >

</div>

<?php if (count($carImages) > 1): ?>

    <div class="car-gallery">

        <?php foreach ($carImages as $image): ?>

            <img
                src="<?= htmlspecialchars($image) ?>"
                alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>"
                onclick="document.getElementById('mainCarImage').src=this.src"
            >

        <?php endforeach; ?>

    </div>

<?php endif; ?>

        <div class="detail-info">

            <p class="small-heading">
                VEHICLE DETAILS
            </p>

            <h1>
                <?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>
            </h1>

            <p class="detail-location">
                📍 <?= htmlspecialchars($car['city']) ?>
            </p>


            <div class="detail-price">

                <small>ASKING PRICE</small>

                <h2>
                    PKR <?= number_format($car['price']) ?>
                </h2>

            </div>


            <div class="detail-spec-grid">

                <div>
                    <small>YEAR</small>
                    <strong>
                        <?= htmlspecialchars($car['year']) ?>
                    </strong>
                </div>

                <div>
                    <small>MILEAGE</small>
                    <strong>
                        <?= number_format($car['mileage']) ?> km
                    </strong>
                </div>

                <div>
                    <small>ENGINE</small>
                    <strong>
                        <?= htmlspecialchars($car['engine']) ?>
                    </strong>
                </div>

                <div>
                    <small>TRANSMISSION</small>
                    <strong>
                        <?= htmlspecialchars($car['transmission']) ?>
                    </strong>
                </div>

                <div>
                    <small>FUEL TYPE</small>
                    <strong>
                        <?= htmlspecialchars($car['fuel_type']) ?>
                    </strong>
                </div>

                <div>
                    <small>COLOR</small>
                    <strong>
                        <?= htmlspecialchars($car['color']) ?>
                    </strong>
                </div>

                <div>
                    <small>REGISTRATION</small>
                    <strong>
                        <?= htmlspecialchars($car['registration']) ?>
                    </strong>
                </div>

                <div>
                    <small>LOCATION</small>
                    <strong>
                        <?= htmlspecialchars($car['city']) ?>
                    </strong>
                </div>

            </div>

        </div>

    </div>


    <div class="detail-bottom">

        <div class="description-box">

            <p class="small-heading">
                DESCRIPTION
            </p>

            <h2>
                About This Vehicle
            </h2>

            <p>
                <?= htmlspecialchars($car['description']) ?>
            </p>
            <?php if (!empty($car['remarks'])): ?>

    <div class="seller-remarks">

        <h3>Seller Remarks</h3>

        <p>
            <?= nl2br(htmlspecialchars($car['remarks'])) ?>
        </p>

    </div>

<?php endif; ?>


            <p class="demo-warning">
                This website is a Docker demonstration application.
                All vehicle prices, seller names and contact information
                shown here are dummy data for testing purposes.
            </p>

        </div>


        <div class="seller-card">

            <p class="small-heading">
                SELLER INFORMATION
            </p>

            <h2>
                <?= htmlspecialchars($car['owner_name']) ?>
            </h2>

            <p>
                Private Seller
            </p>


            <div class="seller-contact">

                <span>
                    📍 <?= htmlspecialchars($car['city']) ?>
                </span>

                <span>
                    📞 <?= htmlspecialchars($car['owner_phone']) ?>
                </span>
                <?php if (!empty($car['owner_email'])): ?>

    <span>
        ✉ <?= htmlspecialchars($car['owner_email']) ?>
    </span>

<?php endif; ?>

            </div>


            <a href="tel:<?= htmlspecialchars($car['owner_phone']) ?>">
                Contact Seller
            </a>
           
            <?php if (!empty($car['owner_email'])): ?>

    <a
        href="mailto:<?= htmlspecialchars($car['owner_email']) ?>"
        class="email-seller-btn"
    >
        Email Seller
    </a>

<?php endif; ?>

        </div>

    </div>

</div>

</section>


<footer>

<div class="copyright">

© <?= date("Y") ?> DriveDeal.pk , Buy Smart. Sell Fast. Drive Happy.
    Docker Car Marketplace Demo

</div>

</footer>

</body>

</html>