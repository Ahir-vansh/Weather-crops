<?php
$conn = new mysqli("localhost", "root", "", "your_database_name");
$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}
echo json_encode($tables);
?>
