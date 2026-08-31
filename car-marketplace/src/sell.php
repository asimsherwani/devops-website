<?php
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sell Your Car | DriveDeal.pk</title>

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

        <a href="sell.php" class="sell-btn">
            + List Your Car
        </a>

    </div>
</header>


<section class="sell-page">

<div class="container">

    <div class="sell-page-heading">

        <p class="small-heading">
            SELL WITH DRIVEDEAL.PK
        </p>

        <h1>Sell Your Car</h1>

        <p>
            Enter your vehicle details and reach potential
            buyers across Pakistan.
        </p>

    </div>


    <form
        action="submit-car.php"
        method="POST"
        enctype="multipart/form-data"
        class="car-listing-form"
    >

        <div class="form-section">

            <h2>Seller Information</h2>

            <div class="form-grid">

                <div class="form-group">
                    <label>Full Name *</label>

                    <input
                        type="text"
                        name="owner_name"
                        required
                        maxlength="100"
                    >
                </div>


                <div class="form-group">

                    <label>Email Address *</label>

                    <input
                        type="email"
                        name="owner_email"
                        required
                        maxlength="150"
                    >

                </div>


                <div class="form-group">

                    <label>Mobile Number *</label>

                    <input
                        type="tel"
                        name="owner_phone"
                        placeholder="03XX-XXXXXXX"
                        required
                        maxlength="30"
                    >

                </div>

            </div>

        </div>


        <div class="form-section">

            <h2>Vehicle Information</h2>

            <div class="form-grid">


                <div class="form-group">

                    <label>Make *</label>

                    <input
                        type="text"
                        name="make"
                        placeholder="Toyota"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Model *</label>

                    <input
                        type="text"
                        name="model"
                        placeholder="Corolla Altis"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Year *</label>

                    <input
                        type="number"
                        name="year"
                        min="1950"
                        max="2030"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Price (PKR) *</label>

                    <input
                        type="number"
                        name="price"
                        min="1"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Mileage (KM) *</label>

                    <input
                        type="number"
                        name="mileage"
                        min="0"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Engine *</label>

                    <input
                        type="text"
                        name="engine"
                        placeholder="1.6L"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Transmission *</label>

                    <select name="transmission" required>

                        <option value="">Select Transmission</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                        <option value="CVT">CVT</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Fuel Type *</label>

                    <select name="fuel_type" required>

                        <option value="">Select Fuel Type</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Electric">Electric</option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Color *</label>

                    <input
                        type="text"
                        name="color"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>City *</label>

                    <input
                        type="text"
                        name="city"
                        placeholder="Islamabad"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Registration *</label>

                    <input
                        type="text"
                        name="registration"
                        placeholder="Islamabad"
                        required
                    >

                </div>

            </div>

        </div>


        <div class="form-section">

            <h2>Description & Remarks</h2>

            <div class="form-group">

                <label>Vehicle Description *</label>

                <textarea
                    name="description"
                    rows="5"
                    required
                    placeholder="Describe the condition, features and history of your vehicle..."
                ></textarea>

            </div>


            <div class="form-group">

                <label>Additional Remarks</label>

                <textarea
                    name="remarks"
                    rows="4"
                    placeholder="Any additional information for buyers..."
                ></textarea>

            </div>

        </div>


        <div class="form-section">

            <h2>Car Photos</h2>

            <div class="upload-box">

                <label>
                    Upload Car Pictures *
                </label>

                <input
                    type="file"
                    name="car_images[]"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    required
                >

                <p>
                    Upload clear JPG, PNG or WEBP pictures of your vehicle.
                </p>

            </div>

        </div>


        <div class="submit-area">

            <button type="submit" class="submit-car-btn">
                List My Car
            </button>

            <p>
                By submitting your listing, you confirm that
                the provided vehicle information is accurate.
            </p>

        </div>

    </form>

</div>

</section>


<footer>

    <div class="copyright">

        DriveDeal.pk , Buy Smart. Sell Fast. Drive Happy.

    </div>

</footer>


</body>
</html>