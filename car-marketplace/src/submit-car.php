<?php

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sell.php');
    exit;
}

function clean($value)
{
    return trim($value ?? '');
}

$owner_name   = clean($_POST['owner_name']);
$owner_email  = clean($_POST['owner_email']);
$owner_phone  = clean($_POST['owner_phone']);

$make         = clean($_POST['make']);
$model        = clean($_POST['model']);
$year         = (int)($_POST['year'] ?? 0);
$price        = (float)($_POST['price'] ?? 0);
$mileage      = (int)($_POST['mileage'] ?? 0);

$engine       = clean($_POST['engine']);
$transmission = clean($_POST['transmission']);
$fuel_type    = clean($_POST['fuel_type']);
$color        = clean($_POST['color']);
$city         = clean($_POST['city']);
$registration = clean($_POST['registration']);

$description  = clean($_POST['description']);
$remarks      = clean($_POST['remarks']);


/* Validate required fields */

if (
    $owner_name === '' ||
    $owner_email === '' ||
    $owner_phone === '' ||
    $make === '' ||
    $model === '' ||
    $year <= 0 ||
    $price <= 0 ||
    $engine === '' ||
    $transmission === '' ||
    $fuel_type === '' ||
    $color === '' ||
    $city === '' ||
    $registration === '' ||
    $description === ''
) {
    die('Please complete all required fields.');
}

if (!filter_var($owner_email, FILTER_VALIDATE_EMAIL)) {
    die('Please enter a valid email address.');
}


/* Upload directory */

$uploadDirectory = __DIR__ . '/uploads/';

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}


/* Check uploaded pictures */

if (
    !isset($_FILES['car_images']) ||
    empty($_FILES['car_images']['name'][0])
) {
    die('Please upload at least one vehicle picture.');
}


$allowedMimeTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

$uploadedImages = [];

$finfo = new finfo(FILEINFO_MIME_TYPE);

$totalFiles = count($_FILES['car_images']['name']);


/* Maximum 8 images */

if ($totalFiles > 8) {
    die('Maximum 8 vehicle pictures are allowed.');
}


for ($i = 0; $i < $totalFiles; $i++) {

    $tmpName = $_FILES['car_images']['tmp_name'][$i];
    $error   = $_FILES['car_images']['error'][$i];
    $size    = $_FILES['car_images']['size'][$i];

    if ($error !== UPLOAD_ERR_OK) {
        die('One of the uploaded pictures could not be processed.');
    }

    /* Maximum 5 MB per image */

    if ($size > 5 * 1024 * 1024) {
        die('Each vehicle picture must be smaller than 5 MB.');
    }

    $mimeType = $finfo->file($tmpName);

    if (!isset($allowedMimeTypes[$mimeType])) {
        die('Only JPG, PNG and WEBP pictures are allowed.');
    }

    $extension = $allowedMimeTypes[$mimeType];

    $fileName =
        bin2hex(random_bytes(16))
        . '.'
        . $extension;

    $destination = $uploadDirectory . $fileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        die('Unable to save uploaded vehicle picture.');
    }

    $uploadedImages[] = 'uploads/' . $fileName;
}


/* First picture = main listing picture */

$mainImage = $uploadedImages[0];


/* Start database transaction */

$conn->begin_transaction();

try {

    $status = 'approved';

    $sql = "
        INSERT INTO cars
        (
            make,
            model,
            year,
            price,
            mileage,
            engine,
            transmission,
            fuel_type,
            color,
            city,
            registration,
            owner_name,
            owner_email,
            owner_phone,
            description,
            remarks,
            image,
            status
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "ssidisssssssssssss",
        $make,
        $model,
        $year,
        $price,
        $mileage,
        $engine,
        $transmission,
        $fuel_type,
        $color,
        $city,
        $registration,
        $owner_name,
        $owner_email,
        $owner_phone,
        $description,
        $remarks,
        $mainImage,
        $status
    );

    $stmt->execute();

    $carId = $conn->insert_id;


    /* Store all uploaded pictures */

    $imageStatement = $conn->prepare(
        "INSERT INTO car_images (car_id, image_path)
         VALUES (?, ?)"
    );

    if (!$imageStatement) {
        throw new Exception($conn->error);
    }

    foreach ($uploadedImages as $imagePath) {

        $imageStatement->bind_param(
            "is",
            $carId,
            $imagePath
        );

        $imageStatement->execute();
    }


    $conn->commit();


    /* Redirect to newly created vehicle */

    header(
        'Location: car.php?id='
        . $carId
        . '&listed=1'
    );

    exit;

} catch (Throwable $e) {

    $conn->rollback();


    /* Remove pictures if database operation fails */

    foreach ($uploadedImages as $imagePath) {

        $physicalFile =
            __DIR__
            . '/'
            . $imagePath;

        if (file_exists($physicalFile)) {
            unlink($physicalFile);
        }
    }

    http_response_code(500);

    die(
        'Unable to create your vehicle listing. '
        . 'Please try again.'
    );
}