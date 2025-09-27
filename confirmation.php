<?php
session_start();
require_once 'db.php';
$movie_posters = [
    'Interstellar' => 'images/interstellar.png',
    'Fight Club' => 'images/fightclub.png',
    'Oppenheimer' => 'images/oppenheimer.jpg',
    'Godzilla Minus One' => 'images/godzilla.png',
    'Scarface' => 'images/scarface.png',
    'Echoes of the Cosmos' => 'images/interstellar.png'
];
$movie_title = isset($_GET['movie']) ? htmlspecialchars($_GET['movie']) : '';
$showtime = isset($_GET['showtime']) ? htmlspecialchars($_GET['showtime']) : '';
$poster = isset($movie_posters[$movie_title]) ? $movie_posters[$movie_title] : 'images/interstellar.png';
$user_id = $_SESSION['user_id'] ?? null;
$movie = $_GET['movie'] ?? '';
$showtime = $_GET['showtime'] ?? '';
if ($user_id && $movie && $showtime) {
    $stmt = $conn->prepare('SELECT id FROM tickets WHERE user_id = ? AND movie = ? AND showtime = ?');
    $stmt->bind_param('iss', $user_id, $movie, $showtime);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        $stmt = $conn->prepare('INSERT INTO tickets (user_id, movie, showtime) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $user_id, $movie, $showtime);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation | MovieBook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f7f8fa;
            color: #222e3a;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
        }
        .header {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 36px;
        }
        .logo {
            font-weight: bold;
            font-size: 1.5rem;
            color: #1a2340;
            text-decoration: none;
        }
        .nav {
            display: flex;
            gap: 24px;
        }
        .nav a {
            color: #222e3a;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .nav a:hover {
            color: #007bff;
        }
        .profile {
            color: #222e3a;
            font-size: 1.3rem;
            text-decoration: none;
            margin-left: 18px;
        }
        .container {
            max-width: 900px;
            margin: 36px auto 0 auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 36px 32px 32px 32px;
        }
        .confirm-section {
            text-align: center;
            margin-bottom: 32px;
        }
        .confirm-section h1 {
            font-size: 2.2rem;
            color: #007bff;
            margin-bottom: 12px;
        }
        .confirm-section p {
            font-size: 1.15rem;
            color: #222e3a;
            margin-bottom: 24px;
        }
        .qr-img {
            width: 120px;
            margin: 0 auto 18px auto;
            display: block;
        }
        .details-section {
            display: flex;
            gap: 32px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }
        .movie-poster {
            width: 140px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        }
        .details-list {
            flex: 1;
            min-width: 220px;
        }
        .details-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .details-list li {
            font-size: 1.08rem;
            margin-bottom: 12px;
            color: #222e3a;
        }
        .details-list li strong {
            color: #007bff;
            font-weight: 600;
            margin-right: 8px;
        }
        .actions {
            text-align: center;
            margin-bottom: 32px;
        }
        .actions button {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 1rem;
            font-weight: 500;
            margin: 0 12px 12px 0;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .actions button:hover {
            background: #0056b3;
            box-shadow: 0 4px 16px rgba(0,123,255,0.10);
        }
        .footer {
            background: #fff;
            margin-top: 48px;
            padding: 18px 0;
            border-top: 1px solid #e2e2e2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 24px;
        }
        .footer-links {
            display: flex;
            gap: 24px;
            font-size: 1rem;
        }
        .footer-links a {
            color: #222e3a;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-links a:hover {
            color: #007bff;
        }
        .footer-social {
            display: flex;
            gap: 16px;
            font-size: 1.3rem;
        }
        .footer-social a {
            color: #222e3a;
            transition: color 0.2s;
        }
        .footer-social a:hover {
            color: #007bff;
        }
        @media (max-width: 700px) {
            .container {
                padding: 18px 8px;
            }
            .details-section {
                flex-direction: column;
                gap: 18px;
                align-items: flex-start;
            }
            .movie-poster {
                width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a class="logo" href="index.php">MovieBook</a>
        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="mytickets.php">My Tickets</a>
        </nav>
        <a href="#" class="profile"><i class="fas fa-user-circle"></i></a>
    </div>
    <div class="container">
        <div class="confirm-section">
            <?php if ($showtime): ?>
                <h1>Booking Confirmed!</h1>
                <p>Your tickets for <strong><?= $movie_title ?></strong> are secured. Enjoy the show!</p>
            <?php else: ?>
                <h1 style="color:red">Booking Not Complete</h1>
                <p>Please go back and select a showtime to complete your booking for <strong><?= $movie_title ?></strong>.</p>
            <?php endif; ?>
            <img src="images/Booking Confirmation.png" alt="E-Ticket QR" class="qr-img">
        </div>
        <div class="details-section">
            <img src="<?= htmlspecialchars($poster) ?>" alt="Movie Poster" class="movie-poster">
            <div class="details-list">
                <ul>
                    <li><strong>Movie Name:</strong> <?= $movie_title ?></li>
                    <li><strong>Cinema:</strong> MovieBook Grand Plaza - Hall 3</li>
                    <li><strong>Showtime:</strong> <?= $showtime ? $showtime : '<span style="color:red">No showtime selected</span>' ?></li>
                    <?php if ($showtime): ?>
                        <li><strong>Seats:</strong> A1, A2, A3</li>
                        <li><strong>Total Paid:</strong> $36.00</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="actions">
            <?php if ($showtime): ?>
                <button>Download E-Ticket</button>
                <button>Add to Calendar</button>
                <button>Share Booking</button>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer">
        <div class="footer-links">
            <a href="#">Quick Links</a>
            <a href="#">Support</a>
            <a href="#">Company</a>
        </div>
        <div class="footer-social">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</body>
</html>
