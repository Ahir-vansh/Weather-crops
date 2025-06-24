<?php
include 'db.php';

$crop = $_POST['crop'];
$man = $_POST['price_man'];
$kg = $_POST['price_kg'];
$quintal = $_POST['price_quintal'];
$village = $_POST['village'];
$district = $_POST['district'];

// Optional basic validation
if ($crop && $man && $kg && $quintal && $village && $district) {
    $stmt = $conn->prepare("INSERT INTO crop_prices (crop, price_man, price_kg, price_quintal, village, district) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddsss", $crop, $man, $kg, $quintal, $village, $district);
    
    if ($stmt->execute()) {
        echo "<script>alert('✔️ Data added successfully!'); window.location.href='farmer.html';</script>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "<script>alert('❗ Please fill in all fields'); window.history.back();</script>";
}

$conn->close();
?>
