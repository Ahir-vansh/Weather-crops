<?php
include 'db.php';

$name = $_GET['name'] ?? '';
$cols = intval($_GET['cols'] ?? 0);
if (!$name || $cols <= 0) die("Invalid input.");

$sql = "CREATE TABLE `$name` (id INT AUTO_INCREMENT PRIMARY KEY";
for ($i = 1; $i <= $cols; $i++) {
  $sql .= ", col$i VARCHAR(255)";
}
$sql .= ")";
echo $conn->query($sql) ? "Table $name created!" : "Error: " . $conn->error;
?>
