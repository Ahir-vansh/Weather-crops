<?php
include 'db.php';

$table = $_POST['table'];
$columns = $_POST['columns'];

$keys = implode(",", array_keys($columns));
$placeholders = implode(",", array_fill(0, count($columns), "?"));
$values = array_values($columns);

$sql = "INSERT INTO `$table` ($keys) VALUES ($placeholders)";
$stmt = $conn->prepare($sql);
$types = str_repeat("s", count($values));
$stmt->bind_param($types, ...$values);

echo $stmt->execute() ? "Row added" : "Insert failed";
?>
