<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="gu">

<head>
  <meta charset="UTF-8" />
  <title>Farmer Info</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    @media (max-width: 1024px) {
      .news-item {
        font-size: 15px;
        padding: 10px;
      }

      table th,
      table td {
        font-size: 15px;
      }

      input,
      button {
        font-size: 14px;
      }
    }

    @media (max-width: 768px) {
      nav a {
        display: block;
        margin: 5px 0;
        text-align: center;
      }

      header,
      footer {
        font-size: 16px;
        padding: 8px;
      }

      section {
        padding: 15px;
      }

      .news-item {
        font-size: 14px;
      }

      table th,
      table td {
        font-size: 14px;
        padding: 8px;
      }

      input,
      button {
        padding: 8px;
        font-size: 14px;
      }
    }

    @media (max-width: 480px) {

      header,
      footer {
        font-size: 15px;
        padding: 6px;
      }

      nav a {
        font-size: 14px;
      }

      section {
        padding: 10px;
      }

      .news-item {
        font-size: 13px;
        padding: 8px 10px;
      }

      table {
        font-size: 13px;
      }

      input,
      button {
        font-size: 13px;
        padding: 6px;
      }

      .badge {
        font-size: 10px;
        padding: 2px 6px;
      }
    }

    .fade-in {
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    table th,
    table td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }

    input,
    button {
      padding: 6px;
      width: 100%;
    }

    form {
      margin-top: 20px;
    }

    body {
      font-family: sans-serif;
      background: #f3fdf6;
    }

    header,
    footer {
      background: #388e3c;
      color: white;
      padding: 10px;
      text-align: center;
    }

    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
    }

    nav a:hover {
      text-decoration: underline;
    }

    section {
      padding: 20px;
    }

    .news p {
      margin: 5px 0;
      font-size: 15px;
    }

    .news-container {
      margin-top: 10px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .news-item {
      background: #ffffff;
      border-left: 6px solid #4caf50;
      padding: 10px 15px;
      border-radius: 6px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
      font-size: 16px;
      animation: slideIn 0.6s ease-in-out;
      position: relative;
    }

    .news-item.update {
      border-left-color: #ff9800;
    }

    .news-item.live {
      border-left-color: #e53935;
    }

    .badge {
      background: #388e3c;
      color: white;
      font-size: 12px;
      padding: 3px 8px;
      border-radius: 12px;
      margin-right: 10px;
      font-weight: bold;
      text-transform: uppercase;
    }

    @keyframes slideIn {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <header>
    <h1>🌾 ખેડૂતો માહિતી</h1>
    <nav>
      <a href="index.html">Home</a>
      <a href="weather.html">Weather</a>
      <a href="farmer.php">Farmer Info</a>
      <a href="login.html">Login</a>
    </nav>
  </header>

  <section class="fade-in">
    <h2>📊 Live Market Prices</h2>
    <table class="fade-in">
      <thead>
        <tr>
          <th>પેદાશ / Crop</th>
          <th>1 માન / Man</th>
          <th>1 કિ.ગ્રા / Kg</th>
          <th>1 ક્વિન્ટલ / Quintal</th>
          <th>ગામ / Village</th>
          <th>જિલ્લો / District</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $res = $conn->query("SELECT * FROM crop_prices ORDER BY created_at DESC");
        if ($res->num_rows > 0):
          while ($row = $res->fetch_assoc()):
        ?>
            <tr class="fade-in">
              <td><?= htmlspecialchars($row['crop']) ?></td>
              <td>₹<?= $row['price_man'] ?></td>
              <td>₹<?= $row['price_kg'] ?></td>
              <td>₹<?= $row['price_quintal'] ?></td>
              <td><?= htmlspecialchars($row['village']) ?></td>
              <td><?= htmlspecialchars($row['district']) ?></td>
            </tr>
          <?php endwhile;
        else: ?>
          <tr>
            <td colspan="6">No data yet.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <h3>➕ Add Your Village Market Price</h3>
    <form action="add_price.php" method="POST" class="fade-in">
      <label>પેદાશ / Crop Name:</label>
      <input type="text" name="crop" required />

      <label>1 માન / Price per Man:</label>
      <input type="number" name="price_man" required />

      <label>1 કિ.ગ્રા / Price per Kg:</label>
      <input type="number" step="0.01" name="price_kg" required />

      <label>1 ક્વિન્ટલ / Price per Quintal:</label>
      <input type="number" name="price_quintal" required />

      <label>ગામ / Village:</label>
      <input type="text" name="village" required />

      <label>જિલ્લો / District:</label>
      <input type="text" name="district" required />

      <br><br>
      <button type="submit">📤 Submit Price</button>
    </form>

    <h3>📰 News Updates</h3>
    <div class="news-container fade-in">
      <div class="news-item live">
        <span class="badge">Live</span>
        રાજ્ય સરકારે નવા પેકેજની જાહેરાત કરી...
      </div>
      <div class="news-item update">
        <span class="badge">Update</span>
        ટમેટા ભાવમાં વધારો, માર્કેટમાં ભીડ...
      </div>
    </div>

  </section>

  <footer>
    <p>&copy; 2025 Krishi Mitra | <a href="privacy.html" style="color:white;">ગોપનીયતા નીતિ</a> | <a href="terms.html" style="color:white;">નિયમો અને શરતો</a></p>
  </footer>
</body>

</html>