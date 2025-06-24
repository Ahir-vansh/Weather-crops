<?php
include 'db.php';

// Fetch all tables in the user_auth database
$tables = [];
$res = $conn->query("SHOW TABLES FROM krishi_mitra");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Krishi Mitra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f0f8f5;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .pin-screen {
            width: 100%;
            height: 100vh;
            background: #388e3c;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .pin-box {
            display: flex;
            gap: 10px;
        }

        .pin-box input {
            width: 40px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border-radius: 5px;
            border: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        #mainApp {
            display: none;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            background: #2e7d32;
            color: white;
            padding: 20px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            margin: 5px 0;
            background: #388e3c;
            border-radius: 5px;
            text-decoration: none;
        }

        .main {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .form-group input {
            padding: 8px;
            margin: 5px;
        }

        button {
            background: #43cea2;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .sidebar a.active {
            background: #66bb6a;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="pin-screen" id="pinScreen">
        <h1>Enter Admin PIN</h1>
        <div class="pin-box">
            <input type="password" maxlength="1" oninput="moveNext(this, 0)">
            <input type="password" maxlength="1" oninput="moveNext(this, 1)">
            <input type="password" maxlength="1" oninput="moveNext(this, 2)">
            <input type="password" maxlength="1" oninput="moveNext(this, 3)">
            <input type="password" maxlength="1" oninput="moveNext(this, 4)">
            <input type="password" maxlength="1" oninput="moveNext(this, 5)">
        </div>
        <br>
        <button onclick="verifyPIN()">Submit</button>
    </div>

    <div id="mainApp">
        <div class="sidebar">
            <h2>Tables</h2>
            <div id="tableLinks">
                <?php foreach ($tables as $t): ?>
                    <a href="#" onclick="loadTable('<?= $t ?>', this)"><?= $t ?></a>
                <?php endforeach; ?>
            </div>
            <hr>
            <h3>Create New Table</h3>
            <input type="text" id="newTableName" placeholder="Table Name">
            <input type="number" id="columnCount" placeholder="No. of Columns">
            <button onclick="createTable()">Create Table</button>
        </div>

        <div class="main" id="dashboard">
            <h2>Welcome to Admin Dashboard</h2>
            <p>Select a table from the left menu to manage its data.</p>
        </div>
    </div>

    <script>
        const pin = ['1', '2', '3', '4', '5', '6'];

        function moveNext(input, index) {
            const inputs = document.querySelectorAll('.pin-box input');
            if (input.value && index < 5) inputs[index + 1].focus();
        }

        function verifyPIN() {
            const inputs = document.querySelectorAll('.pin-box input');
            const entered = Array.from(inputs).map(i => i.value);
            if (entered.join('') === pin.join('')) {
                document.getElementById('pinScreen').style.display = 'none';
                document.getElementById('mainApp').style.display = 'flex';
            } else {
                alert('Incorrect PIN!');
            }
        }

        function loadTable(table, el) {
            fetch(`load_table.php?table=${table}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("dashboard").innerHTML = html;
                    document.querySelectorAll('#tableLinks a').forEach(a => a.classList.remove('active'));
                    if (el) el.classList.add('active');
                });
        }

        function createTable() {
            const name = document.getElementById('newTableName').value;
            const cols = document.getElementById('columnCount').value;
            if (name && cols > 0) {
                fetch(`create_table.php?name=${name}&cols=${cols}`)
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        location.reload();
                    });
            } else {
                alert('Please fill valid table name and column count.');
            }
        }

        function updateRow(cell, table, id, column) {
            const value = cell.textContent;
            fetch('update_row.php', {
                method: 'POST',
                body: new URLSearchParams({ table, id, column, value })
            }).then(res => res.text()).then(msg => console.log(msg));
        }

        function deleteRow(table, id) {
            if (confirm("Are you sure to delete?")) {
                fetch('delete_row.php', {
                    method: 'POST',
                    body: new URLSearchParams({ table, id })
                }).then(res => res.text()).then(msg => {
                    alert(msg);
                    loadTable(table);
                });
            }
        }

        function addRow(table) {
            const inputs = document.querySelectorAll("input[id^='new_']");
            const data = { table, columns: {} };
            inputs.forEach(input => {
                const key = input.id.replace("new_", "");
                data.columns[key] = input.value;
            });
            fetch('add_row.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            }).then(res => res.text()).then(msg => {
                alert(msg);
                loadTable(table);
            });
        }
    </script>
</body>

</html>
