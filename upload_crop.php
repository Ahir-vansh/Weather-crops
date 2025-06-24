<?php
include 'db.php';

$crop = $_POST['crop_name'];
$price = $_POST['min_price'];
$quality = $_POST['quality'];
$village = $_POST['village'];
$mobile = $_POST['mobile'];

$uploadedNames = [];

foreach ($_FILES['crop_images']['tmp_name'] as $key => $tmp_name) {
    $filename = time() . '_' . basename($_FILES['crop_images']['name'][$key]);
    move_uploaded_file($tmp_name, "uploads/" . $filename);
    $uploadedNames[] = $filename;
}

$allImages = implode(',', $uploadedNames);

$conn->query("INSERT INTO crop_market (crop_name, min_price, quality, village, mobile, images)
              VALUES ('$crop', '$price', '$quality', '$village', '$mobile', '$allImages')");

echo "<script>alert('પાક ઉમેરાયો!'); location.href='sell_weather.php';</script>";
?>
