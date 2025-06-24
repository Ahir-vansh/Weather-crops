<?php
include 'db.php';

$table = $_POST['table'];
$id = $_POST['id'];
$column = $_POST['column'];
$value = $_POST['value'];

$sql = "UPDATE `$table` SET `$column`=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $value, $id);
echo $stmt->execute() ? "Updated" : "Failed";
?>
