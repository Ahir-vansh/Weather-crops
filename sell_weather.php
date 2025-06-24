<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="gu">

<head>
    <meta charset="UTF-8">
    <title>Sell Crop & Weather Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <style>
        body {
            font-family: sans-serif;
            background: #f4fff7;
            padding-bottom: 80px;
        }

        header,
        footer {
            background: #388e3c;
            color: white;
            padding: 15px;
            text-align: center;
        }

        a.home-link {
            color: #fff;
            text-decoration: underline;
        }

        .container {
            max-width: 900px;
            margin: auto;
            margin-top: 30px;
        }

        .form-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .card {
            margin-bottom: 20px;
        }

        .preview-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <header>
        <h2>🌾 ખેતી વેચાણ અને હવામાન માહિતી</h2>
        <a class="home-link" href="index.html">🏠 હોમ પેજ પર જાઓ</a>
    </header>

    <div class="container">

        <!-- Farmer Crop Sell Form -->
        <div class="form-section">
            <h4>ખેડૂત માટે: પાક વેચાણ ફોર્મ</h4>
            <form action="upload_crop.php" method="POST" enctype="multipart/form-data">
                <input class="form-control my-2" type="text" name="crop_name" placeholder="પાકનું નામ" required>
                <input class="form-control my-2" type="number" name="min_price" placeholder="ઘાટું ભાવ (₹)" required>
                <input class="form-control my-2" type="text" name="quality" placeholder="ગુણવત્તા (A/B/C)" required>
                <input class="form-control my-2" type="text" name="village" placeholder="ગામનું નામ" required>
                <label>મોબાઇલ નંબર:</label><br>
                <input class="form-control my-2" type="text" name="mobile" required>

                <input class="form-control my-2" type="file" name="crop_images[]" multiple required>
                <button class="btn btn-success mt-2" type="submit">પાક માહિતી અપલોડ કરો</button>
            </form>
        </div>

        <!-- Weather Officer Form -->
        <div class="form-section">
            <h4>હવામાન અધિકારી માટે: આગાહી ઉમેરો</h4>
            <form action="upload_weather.php" method="POST">
                <input class="form-control my-2" type="text" name="sky_status" placeholder="આકાશની સ્થિતિ (જેમ કે ધુપ ભરેલું)" required>
                <input class="form-control my-2" type="text" name="rain_time" placeholder="વરસાદ શરૂ થવાનો સમય (જેમ કે સાંજે ૫ વાગે)" required>
                <textarea class="form-control my-2" name="info" placeholder="અન્ય માહિતી લખો..." required></textarea>
                <input class="form-control my-2" type="text" name="added_by" placeholder="નામ (અધિકારીનું)" required>
                <button class="btn btn-primary mt-2" type="submit">હવામાન માહિતી ઉમેરો</button>
            </form>
        </div>

        <!-- Show Crop Cards -->
        <div class="form-section">
            <h4>🧺 વેચાણ માટે પાક</h4>
            <div class="row">
                <?php
                $crops = $conn->query("SELECT * FROM crop_market ORDER BY id DESC");
                while ($crop = $crops->fetch_assoc()):
                    $images = explode(',', $crop['images']);
                ?>
                    <div class="col-md-6">
                        <div class="card p-3">
                            <h5><?= htmlspecialchars($crop['crop_name']) ?> (₹<?= $crop['min_price'] ?>)</h5>
                            <p><strong>ગુણવત્તા:</strong> <?= htmlspecialchars($crop['quality']) ?> | <strong>ગામ:</strong> <?= htmlspecialchars($crop['village']) ?></p>
                            <p><strong>મોબાઇલ:</strong> <?= htmlspecialchars($crop['mobile']) ?></p>
                            <?php foreach ($images as $img): ?>
                                <?php if (!empty(trim($img))): ?>
                                    <img src="uploads/<?= htmlspecialchars(trim($img)) ?>" class="preview-img" onerror="this.src='no-image.png';">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Show Weather Predictions -->
        <div class="form-section">
            <h4>🌤️ હવામાન આગાહીઓ</h4>
            <?php
            $weathers = $conn->query("SELECT * FROM weather_predictions ORDER BY id DESC");
            while ($row = $weathers->fetch_assoc()):
            ?>
                <div class="card p-3">
                    <p><strong>આકાશ:</strong> <?= htmlspecialchars($row['sky_status']) ?></p>
                    <p><strong>વરસાદ:</strong> <?= htmlspecialchars($row['rain_time']) ?></p>
                    <p><strong>માહિતી:</strong> <?= htmlspecialchars($row['info']) ?></p>
                    <p><em>Added by: <?= htmlspecialchars($row['added_by']) ?></em></p>
                </div>
            <?php endwhile; ?>
        </div>

    </div>

    <footer>
        <p>&copy; 2025 Krishi Mitra - દરેક ખેડૂત માટે ટેકનોલોજી</p>
    </footer>

</body>
</html>
