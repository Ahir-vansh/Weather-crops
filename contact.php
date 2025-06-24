<?php
$conn = new mysqli("localhost", "root", "", "krishi_mitra");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['form_type'] === 'feedback') {
    $name = trim($_POST['name']);
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

    if ($name && $rating > 0 && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
      $imgOriginal = $_FILES['image']['name'];
      $imgSafeName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $imgOriginal);
      $imgTmp = $_FILES['image']['tmp_name'];
      $target = __DIR__ . "/uploads/" . $imgSafeName;

      if (move_uploaded_file($imgTmp, $target)) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, rating, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $rating, $imgSafeName);
        $stmt->execute();
      } else {
        echo "<script>alert('Image upload failed.')</script>";
      }
    } else {
      echo "<script>alert('Please fill all fields correctly.')</script>";
    }
  } elseif ($_POST['form_type'] === 'query') {
    $qname = trim($_POST['qname']);
    $qemail = trim($_POST['qemail']);
    $qmsg = trim($_POST['qmsg']);

    if ($qname && $qemail && $qmsg) {
      $stmt = $conn->prepare("INSERT INTO queries (name, email, message) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $qname, $qemail, $qmsg);
      $stmt->execute();
    }
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

$feedbacks = $conn->query("SELECT * FROM feedback ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="gu">

<head>
  <meta charset="UTF-8">
  <title>સંપર્ક અને પ્રતિસાદ</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    @media (max-width: 1024px) {

      header,
      footer {
        font-size: 18px;
        padding: 12px;
      }

      section {
        padding: 15px;
      }

      h2 {
        font-size: 22px;
      }

      .card img {
        height: 140px;
      }
    }

    @media (max-width: 768px) {

      header,
      footer {
        font-size: 16px;
        padding: 10px;
      }

      form {
        padding: 10px;
      }

      input,
      textarea {
        font-size: 14px;
        padding: 8px;
      }

      button {
        width: 100%;
        font-size: 16px;
      }

      h2 {
        font-size: 20px;
        text-align: center;
      }
    }

    @media (max-width: 480px) {

      header,
      footer {
        font-size: 15px;
        padding: 8px;
      }

      section {
        padding: 10px;
      }

      form {
        padding: 8px;
      }

      input,
      textarea {
        font-size: 13px;
        padding: 7px;
      }

      .stars label {
        font-size: 20px;
      }

      .card img {
        height: 130px;
      }

      .popup img {
        max-width: 95%;
        max-height: 80%;
      }
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f0f8f5;
    }

    header,
    footer {
      background: #2e7d32;
      color: white;
      text-align: center;
      padding: 15px;
      font-size: 20px;
    }

    section {
      max-width: 1100px;
      margin: auto;
      padding: 20px;
    }

    h2 {
      color: #2e7d32;
    }

    form {
      background: white;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    input,
    textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      padding: 10px 25px;
      background: #388e3c;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #2e7d32;
    }

    .stars label {
      font-size: 24px;
      color: #ccc;
      transition: 0.3s;
      cursor: pointer;
    }

    .stars input:checked~label,
    .stars label:hover,
    .stars label:hover~label {
      color: gold;
    }

    .feedback-block {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: flex-start;
    }

    .card {
      flex: 0 1 calc(33.333% - 15px);
      max-width: calc(33.333% - 15px);
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      text-align: center;
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      cursor: pointer;
    }

    .card strong {
      display: block;
      margin: 10px 0 5px;
      font-size: 18px;
    }

    @media (max-width: 768px) {
      .card {
        flex: 0 1 calc(50% - 15px);
        max-width: calc(50% - 15px);
      }
    }

    @media (max-width: 480px) {
      .card {
        flex: 0 1 100%;
        max-width: 100%;
      }
    }

    .map-responsive {
      overflow: hidden;
      padding-bottom: 56.25%;
      /* 16:9 aspect ratio */
      position: relative;
      height: 0;
    }

    .map-responsive iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }

    .popup {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .popup img {
      max-width: 90%;
      max-height: 90%;
      border: 5px solid white;
      border-radius: 10px;
    }
  </style>
</head>

<body>
  <header>📞 સંપર્ક કરો અને ફીડબેક આપો
    <br>
    <a href="index.html" title="Home"><i class="fas fa-home"></i></a>
  </header>
  <h2>📍 અમારી માહિતી / Contact Details</h2>

  <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
    <p><i class="fas fa-phone-alt"></i> <strong>Phone:</strong> <a href="tel:+9099857789">+91 9099857789</a></p>
    <p><i class="fab fa-whatsapp"></i> <strong>WhatsApp:</strong> <a href="https://wa.me/9099857789" target="_blank">Chat on WhatsApp</a></p>
    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <a href="mailto:contact@krishimitra.in">contact@krishimitra.in</a></p>
    <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Krishi Mitra farm , village Bavalavadar Kutiyana Porbandar 362650</p>

    <div style="margin: 20px 0; font-size: 24px;">
      <a href="https://instagram.com/mr._.ahir._.009" target="_blank" style="margin: 0 10px; color: #E1306C;"><i class="fab fa-instagram"></i></a>
      <a href="https://facebook.com/krishimitra" target="_blank" style="margin: 0 10px; color: #1877f2;"><i class="fab fa-facebook"></i></a>
      <a href="https://youtube.com/@krishimitra" target="_blank" style="margin: 0 10px; color: #FF0000;"><i class="fab fa-youtube"></i></a>
      <a href="https://twitter.com/krishimitra" target="_blank" style="margin: 0 10px; color: #1DA1F2;"><i class="fab fa-twitter"></i></a>
    </div>

    <div class="container mt-4">
      <h2 class="text-center mb-4">Our Location</h2>
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="map-responsive">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3670.007206585605!2d72.50808941503647!3d23.08386378492271!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e84fddf10000b%3A0x9e7fcd2b61e8f297!2sMurlidhar Farm (Kishan Yadav)%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1616410274282!5m2!1sen!2sin"
              loading="lazy"
              allowfullscreen
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </div>
    </div>
    <header>📞 સંપર્ક કરો અને ફીડબેક આપો</header>

    <section>
      <h2>🟢 Feedback Form</h2>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="form_type" value="feedback">
        <input type="text" name="name" placeholder="તમારું નામ" required>

        <div class="stars">
          <label>⭐⭐⭐⭐⭐ રેટિંગ:</label><br>
          <input type="radio" name="rating" id="star5" value="5" required><label for="star5">★</label>
          <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
          <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
          <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
          <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
        </div>

        <label>પ્રોફાઇલ છબી અપલોડ કરો:</label>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">મોકલો</button>
      </form>

      <h2>📩 Query Form</h2>
      <form method="POST">
        <input type="hidden" name="form_type" value="query">
        <input type="text" name="qname" placeholder="તમારું નામ" required>
        <input type="email" name="qemail" placeholder="તમારું ઈમેઈલ" required>
        <textarea name="qmsg" rows="4" placeholder="તમારો પ્રશ્ન લખો" required></textarea>
        <button type="submit">મોકલો</button>
      </form>

      <h2>🧾 Your Feedbacks</h2>
      <div class="feedback-block">
        <?php while ($f = $feedbacks->fetch_assoc()): ?>
          <?php
          $imgPath = 'uploads/' . htmlspecialchars($f['image']);
          $imgDisplay = file_exists($imgPath) ? $imgPath : 'uploads/default.png';
          ?>
          <div class="card">
            <img src="<?= $imgDisplay ?>" alt="Profile" onclick="openPopup(this.src)">
            <strong><?= htmlspecialchars($f['name']) ?></strong>
            <div style="color:gold;">
              <?= str_repeat("★", $f['rating']) ?>
              <?= str_repeat("☆", 5 - $f['rating']) ?>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

    <div class="popup" id="popup" onclick="closePopup()">
      <img id="popupImage" src="" alt="Large View">
    </div>

    <footer>&copy; 2025 Krishi Mitra | Privacy | Terms</footer>

    <script>
      function openPopup(src) {
        document.getElementById("popupImage").src = src;
        document.getElementById("popup").style.display = "flex";
        document.body.style.filter = "blur(2px)";
      }

      function closePopup() {
        document.getElementById("popup").style.display = "none";
        document.body.style.filter = "none";
      }
    </script>

</body>

</html>