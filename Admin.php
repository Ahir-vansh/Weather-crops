<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Krishi Mitra</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f0f8f5;
      display: flex;
      height: 100vh;
      overflow: hidden;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .sidebar {
      width: 250px;
      background: #2e7d32;
      color: white;
      padding: 20px;
      height: 100vh;
      overflow-y: auto;
      animation: slideInLeft 1s ease-in-out;
    }
    @keyframes slideInLeft {
      from { transform: translateX(-100%); }
      to { transform: translateX(0); }
    }
    .sidebar a {
      display: block;
      color: white;
      padding: 10px;
      margin: 5px 0;
      background: #388e3c;
      border-radius: 5px;
      text-decoration: none;
      transition: background 0.3s;
    }
    .sidebar a:hover, .sidebar a.active {
      background: #66bb6a;
      font-weight: bold;
    }
    .main {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      animation: slideInRight 1s ease-in-out;
    }
    @keyframes slideInRight {
      from { transform: translateX(100%); }
      to { transform: translateX(0); }
    }
    th, td { text-align: center; vertical-align: middle; }
    .animated-alert {
      animation: shake 0.3s ease-in-out;
    }
    @keyframes shake {
      0% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      50% { transform: translateX(5px); }
      75% { transform: translateX(-5px); }
      100% { transform: translateX(0); }
    }
    @media screen and (max-width: 1024px) {
  .sidebar {
    width: 200px;
    padding: 15px;
  }
  .sidebar a {
    font-size: 14px;
    padding: 8px;
  }
  .main {
    padding: 15px;
  }
}

@media screen and (max-width: 768px) {
  body {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    height: auto;
    padding: 10px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    position: relative;
    animation: none;
  }

  .sidebar a {
    margin: 5px;
    flex: 1 1 auto;
    text-align: center;
  }

  .main {
    width: 100%;
    padding: 15px;
    animation: none;
  }
}

@media screen and (max-width: 480px) {
  .sidebar a {
    font-size: 12px;
    padding: 6px;
  }

  .main {
    padding: 10px;
  }

  th, td {
    font-size: 12px;
    padding: 6px;
  }
}

  </style>
</head>
<body>
  <div class="sidebar">
    <h3>📋 Tables</h3>
    <div id="tableList">
      <?php
        $res = $conn->query("SHOW TABLES");
        while ($row = $res->fetch_array()) {
          $t = $row[0];
          echo "<a href='#' onclick=\"loadTable('$t', this)\">$t</a>";
        }
      ?>
    </div>
  </div>

  <div class="main">
    <h3 id="tableTitle">Select a table to view its data</h3>
    <div id="tableContainer"></div>
  </div>

  <script>
    function loadTable(table, el) {
      document.querySelectorAll(".sidebar a").forEach(a => a.classList.remove("active"));
      if (el) el.classList.add("active");

      fetch("load_table.php?table=" + table)
        .then(res => res.text())
        .then(html => {
          document.getElementById("tableContainer").innerHTML = html;
          document.getElementById("tableTitle").innerText = "📄 Table: " + table;
        });
    }

    function toggleEdit(rowId) {
      const row = document.getElementById("row_" + rowId);
      const inputs = row.querySelectorAll("td[data-col]");
      const editBtn = document.getElementById("edit_" + rowId);

      if (editBtn.innerText === "Edit") {
        inputs.forEach(cell => {
          const value = cell.innerText;
          cell.innerHTML = `<input class='form-control' value="${value}" />`;
        });
        editBtn.innerText = "Save";
      } else {
        const updated = {};
        inputs.forEach(cell => {
          const col = cell.getAttribute("data-col");
          const value = cell.querySelector("input").value;
          updated[col] = value;
        });
        updated.table = row.getAttribute("data-table");
        updated.id = rowId;

        fetch("update_row.php", {
          method: "POST",
          body: new URLSearchParams(updated)
        }).then(res => res.text()).then(msg => {
          alert(msg);
          loadTable(updated.table);
        });
      }
    }

    function deleteRow(table, id) {
      if (!confirm("Delete this row?")) return;
      fetch("delete_row.php", {
        method: "POST",
        body: new URLSearchParams({ table, id })
      }).then(res => res.text()).then(msg => {
        alert(msg);
        loadTable(table);
      });
    }

    function addRow(table) {
      const inputs = document.querySelectorAll("input[id^='new_']");
      const data = { table, columns: {} };
      inputs.forEach(input => {
        const key = input.id.replace("new_", "");
        data.columns[key] = input.value;
      });

      fetch("add_row.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      }).then(res => res.text()).then(msg => {
        alert(msg);
        loadTable(table);
      });
    }
  </script>
</body>
</html>