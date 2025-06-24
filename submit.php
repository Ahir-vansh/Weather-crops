<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "krishi_mitra";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$role = $_POST['role'];

if ($role == "farmer") {
  $stmt = $conn->prepare("INSERT INTO farmers (first_name, middle_name, last_name, age, village, state, district, city, address, farm_index, survey_no, ac_no, email, mobile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssissssssssss", $_POST['first_name'], $_POST['middle_name'], $_POST['last_name'], $_POST['age'], $_POST['village'], $_POST['state'], $_POST['district'], $_POST['city'], $_POST['address'], $_POST['farm_index'], $_POST['survey_no'], $_POST['ac_no'], $_POST['email'], $_POST['mobile']);
} else {
  $stmt = $conn->prepare("INSERT INTO weathermen (full_name, age, id_no, officer_type, address, email, mobile) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sisssss", $_POST['full_name'], $_POST['age'], $_POST['id_no'], $_POST['officer_type'], $_POST['address'], $_POST['email'], $_POST['mobile']);
}

if ($stmt->execute()) {
  echo "<script>alert('Login Successful!');window.location='index.html';</script>";
} else {
  echo "Error: " . $stmt->error;
}
?>
