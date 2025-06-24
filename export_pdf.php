<?php
require_once('tcpdf/tcpdf.php'); // Include the TCPDF library

include 'db.php'; // Your DB connection file

$table = $_POST['table'] ?? '';
if (!$table) {
  die("Table name not provided.");
}

// Fetch table data
$result = $conn->query("SELECT * FROM `$table`");
if (!$result) {
  die("Error loading table.");
}

// Start building HTML
$html = "<h2>Exported Table: $table</h2><table border='1' cellpadding='5'><thead><tr>";
while ($field = $result->fetch_field()) {
  $html .= "<th>{$field->name}</th>";
}
$html .= "</tr></thead><tbody>";

while ($row = $result->fetch_assoc()) {
  $html .= "<tr>";
  foreach ($row as $cell) {
    $html .= "<td>" . htmlspecialchars($cell) . "</td>";
  }
  $html .= "</tr>";
}
$html .= "</tbody></table>";

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Krishi Mitra');
$pdf->SetTitle('Exported Table - ' . $table);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("$table.pdf", 'D'); // 'D' = download
?>
