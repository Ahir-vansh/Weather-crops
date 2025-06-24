<?php
session_start();
include 'db.php';

// Simulate fetching name after login (from session or DB)
$userName = "ખેડૂત ભાઈ"; // default
$role = $_SESSION['role'] ?? 'farmer'; // or 'weatherman'

if ($role === 'farmer') {
    $query = $conn->query("SELECT first_name FROM farmers WHERE id = '{$_SESSION['id']}' LIMIT 1");
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $userName = $row['first_name'];
    }
} elseif ($role === 'weatherman') {
    $query = $conn->query("SELECT full_name FROM weatherman WHERE id = '{$_SESSION['id']}' LIMIT 1");
    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $userName = $row['full_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="gu">

<head>
    <meta charset="UTF-8">
    <title>Welcome - Krishi Mitra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to bottom right, #e8f5e9, #f1f8e9);
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }

        .welcome-box {
            text-align: center;
            padding: 30px;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
            border-radius: 20px;
            margin-bottom: 40px;
            animation: slideIn 1s ease-in-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .img-card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .img-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(0, 128, 0, 0.3);
        }

        .img-card img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .row {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .img-card {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 1024px) {
            .welcome-box {
                padding: 25px;
                font-size: 18px;
            }

            .img-card {
                border-radius: 12px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .welcome-box {
                padding: 20px;
                font-size: 16px;
            }

            .row {
                flex-direction: column;
                align-items: center;
            }

            .img-card {
                width: 100%;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .welcome-box {
                padding: 15px;
                font-size: 15px;
            }

            .img-card {
                border-radius: 10px;
            }

            .img-card img {
                height: auto;
            }
        }
    </style>
</head>

<body>

    <div class="welcome-box">
        <h2>🙏 આપનું સ્વાગત છે, <?= htmlspecialchars($userName) ?>!</h2>
        <p>આપની વિગતો નીચે દર્શાવવામાં આવી છે.</p>
    </div>

    <div class="container">
        <div class="row">
            <!-- Image 1 (850x690) -->
            <div class="col-md-6">
                <div class="img-card">
                    <img src="images/img1.jpg" alt="Crop 1" width="850" height="690">
                </div>
            </div>

            <!-- Image 2 (300x250) -->
            <div class="col-md-6">
                <div class="img-card">
                    <img src="images/img2.jpg" alt="Crop 2" width="300" height="250">
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Image 3 (1080x900) -->
            <div class="col-12">
                <div class="img-card">
                    <img src="images/img3.jpg" alt="Weather" width="1080" height="900">
                </div>
            </div>
        </div>
    </div>

</body>

</html>