<?php
$conn = new mysqli("localhost", "root", "", "krishi_mitra");

$table = $_GET['table'];
$result = $conn->query("SELECT * FROM `$table`");
$columns = array();
while ($fieldinfo = $result->fetch_field()) {
    $columns[] = $fieldinfo->name;
}

// Display Table
echo "<h2>$table</h2>";
echo "<table class='table' id='dataTable'>";
echo "<tr>";
foreach ($columns as $col) echo "<th>$col</th>";
echo "<th>Actions</th>";
echo "</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach ($columns as $col) {
        echo "<td contenteditable='true' onblur=\"updateRow(this, '$table', '{$row['id']}', '$col')\">{$row[$col]}</td>";
    }
    echo "<td><button onclick=\"deleteRow('$table', '{$row['id']}')\">Delete</button></td>";
    echo "</tr>";
}

// Add New Row
echo "<tr>";
foreach ($columns as $col) {
    if ($col == 'id') continue; // skip auto-increment ID
    echo "<td><input id='new_$col' placeholder='$col'></td>";
}
echo "<td><button onclick=\"addRow('$table')\">Add Row</button></td>";
echo "</tr>";

echo "</table>";
?>
