<?php
include 'db.php';

$table = $_POST['table'];
$id = $_POST['id'];

$sql = "DELETE FROM `$table` WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
echo $stmt->execute() ? "Deleted" : "Failed";
?>
