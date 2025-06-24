<?php
include 'db.php';

$sky = $_POST['sky_status'];
$rain = $_POST['rain_time'];
$info = $_POST['info'];
$by = $_POST['added_by'];

$conn->query("INSERT INTO weather_predictions (sky_status, rain_time, info, added_by) VALUES ('$sky', '$rain', '$info', '$by')");

echo "<script>alert('હવામાન માહિતી ઉમેરાઈ!'); location.href='sell_weather.php';</script>";
