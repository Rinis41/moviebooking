<?php
$movie_title = isset($_GET['movie']) ? htmlspecialchars($_GET['movie']) : 'Echoes of the Cosmos';
$movie_posters = [
    'Interstellar' => 'images/interstellar.png',
    'Fight Club' => 'images/fightclub.png',
    'Oppenheimer' => 'images/oppenheimer.jpg',
    'Godzilla Minus One' => 'images/godzilla.png',
    'Scarface' => 'images/scarface.png',
    'Echoes of the Cosmos' => 'images/interstellar.png',
];
$movie_descriptions = [
    'Interstellar' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
    'Fight Club' => 'An insomniac office worker and a devil-may-care soap maker form an underground fight club that evolves into something much more.',
    'Oppenheimer' => 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.',
    'Godzilla Minus One' => 'Japan, devastated after WWII, faces a new threat in the form of Godzilla.',
    'Scarface' => 'In 1980 Miami, a determined Cuban immigrant takes over a drug cartel and succumbs to greed.',
    'Echoes of the Cosmos' => 'A breathtaking journey through space and time, Echoes of the Cosmos follows a team of explorers as they unravel the mysteries of the universe and confront the echoes of their own pasts.',
];
$movie_cast = [
    'Interstellar' => ['Matthew McConaughey', 'Anne Hathaway', 'Jessica Chastain'],
    'Fight Club' => ['Brad Pitt', 'Edward Norton', 'Helena Bonham Carter'],
    'Oppenheimer' => ['Cillian Murphy', 'Emily Blunt', 'Matt Damon'],
    'Godzilla Minus One' => ['Ryunosuke Kamiki', 'Minami Hamabe', 'Yuki Yamada'],
    'Scarface' => ['Al Pacino', 'Michelle Pfeiffer', 'Steven Bauer'],
    'Echoes of the Cosmos' => ['Anya Sharma', 'Jaxson Cole', 'Mia Chen', 'Liam Park', 'Sofia Russo'],
];
$poster = isset($movie_posters[$movie_title]) ? $movie_posters[$movie_title] : 'images/interstellar.png';
$description = isset($movie_descriptions[$movie_title]) ? $movie_descriptions[$movie_title] : $movie_descriptions['Echoes of the Cosmos'];
$cast = isset($movie_cast[$movie_title]) ? $movie_cast[$movie_title] : $movie_cast['Echoes of the Cosmos'];

// Movie data (replace with dynamic PHP if needed)
$movie = [
    'title' => $movie_title,
    'rating' => 4.7,
    'duration' => '2h 15min',
    'genres' => ['Sci-Fi', 'Adventure', 'Drama'],
    'description' => $description,
    'cast' => $cast,
    'poster' => $poster
];
$dates = [
    'Today',
    'Tomorrow',
    'Wed, May 15'
];
$showtimes = [
    'Today' => [ ['10:00 AM', true], ['01:30 PM', true], ['04:45 PM', false], ['07:00 PM', true] ],
    'Tomorrow' => [ ['09:30 AM', true], ['12:00 PM', true], ['03:15 PM', true], ['08:00 PM', false] ],
    'Wed, May 15' => [ ['11:00 AM', true], ['02:30 PM', false], ['06:00 PM', true] ]
];
$selected_date = $_GET['date'] ?? 'Today';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Movie | CineTickets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #fff; color: #222e3a; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; }
        .header { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; align-items: center; justify-content: space-between; padding: 18px 36px; }
        .logo { font-weight: bold; font-size: 1.5rem; color: #1a2340; text-decoration: none; }
        .nav { display: flex; gap: 24px; }
        .nav a { color: #222e3a; text-decoration: none; font-weight: 500; font-size: 1rem; transition: color 0.2s; }
        .nav a:hover { color: #007bff; }
        .header-icons { display: flex; gap: 18px; align-items: center; }
        .header-icons a { color: #222e3a; font-size: 1.25rem; text-decoration: none; transition: color 0.2s; }
        .header-icons a:hover { color: #007bff; }
        .container { max-width: 1100px; margin: 36px auto 0 auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 36px 32px 32px 32px; }
        .main-info { display: flex; gap: 40px; flex-wrap: wrap; margin-bottom: 38px; }
        .movie-poster { width: 260px; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.10); }
        .movie-details { flex: 1; min-width: 260px; }
        .movie-title { font-size: 2.1rem; font-weight: 700; margin-bottom: 10px; color: #1a2340; }
        .movie-rating { color: #ffb400; font-size: 1.1rem; font-weight: 600; margin-right: 18px; }
        .movie-duration { color: #666; font-size: 1rem; margin-right: 18px; }
        .genre-tags { display: flex; gap: 10px; margin-bottom: 14px; }
        .genre-tag { background: #f0f4fa; color: #007bff; border-radius: 20px; padding: 4px 16px; font-size: 0.98rem; font-weight: 500; box-shadow: 0 1px 4px rgba(0,123,255,0.07); }
        .movie-desc { font-size: 1.08rem; color: #222e3a; margin-bottom: 16px; }
        .movie-cast { font-size: 1rem; color: #222e3a; margin-bottom: 8px; }
        .movie-cast strong { color: #007bff; margin-right: 8px; }
        .showtime-section { margin-bottom: 38px; }
        .showtime-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 18px; color: #1a2340; }
        .date-tabs { display: flex; gap: 12px; margin-bottom: 18px; }
        .date-tab { background: #f7f8fa; color: #222e3a; border-radius: 8px 8px 0 0; padding: 10px 24px; font-size: 1rem; font-weight: 500; border: 1px solid #e2e2e2; border-bottom: none; cursor: pointer; transition: background 0.2s, color 0.2s; }
        .date-tab.selected { background: #fff; color: #007bff; border-bottom: 2px solid #007bff; }
        .showtime-btns { display: flex; gap: 16px; flex-wrap: wrap; }
        .showtime-btn { background: #f7f8fa; color: #222e3a; border: 1px solid #e2e2e2; border-radius: 8px; padding: 12px 24px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: background 0.2s, color 0.2s, box-shadow 0.2s; margin-bottom: 10px; }
        .showtime-btn:hover { background: #007bff; color: #fff; box-shadow: 0 2px 8px rgba(0,123,255,0.10); }
        .showtime-btn.inactive { background: #f0f0f0; color: #aaa; border: 1px solid #e2e2e2; cursor: not-allowed; opacity: 0.7; }
        .actions { text-align: center; margin-bottom: 32px; }
        .confirm-btn { background: #007bff; color: #fff; border: none; border-radius: 8px; padding: 12px 28px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: background 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .confirm-btn:hover { background: #0056b3; }
        .footer { background: #fff; margin-top: 48px; padding: 18px 0; border-top: 1px solid #e2e2e2; display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 24px; position: relative; }
        .footer-links { display: flex; gap: 24px; font-size: 1rem; }
        .footer-links a { color: #222e3a; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #007bff; }
        .footer-social { display: flex; gap: 16px; font-size: 1.3rem; }
        .footer-social a { color: #222e3a; transition: color 0.2s; }
        .footer-social a:hover { color: #007bff; }
        .footer-label { position: absolute; right: 18px; bottom: 8px; font-size: 0.95rem; color: #888; }
        @media (max-width: 900px) {
            .container { padding: 18px 8px; }
            .main-info { flex-direction: column; gap: 18px; align-items: flex-start; }
            .movie-poster { width: 100%; max-width: 260px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <a class="logo" href="index.php">MovieBook</a>
        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="#">My Tickets</a>
        </nav>
        <div class="header-icons">
            <a href="#"><i class="fas fa-user-circle"></i></a>
            <a href="#"><i class="fas fa-cog"></i></a>
        </div>
    </div>
    <div class="container">
        <div class="main-info">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="Movie Poster" class="movie-poster">
            <div class="movie-details">
                <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
                <span class="movie-rating"><i class="fas fa-star"></i> <?= $movie['rating'] ?></span>
                <span class="movie-duration"><i class="fas fa-clock"></i> <?= $movie['duration'] ?></span>
                <div class="genre-tags">
                    <?php foreach ($movie['genres'] as $g): ?>
                        <span class="genre-tag"><?= htmlspecialchars($g) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="movie-desc"><?= htmlspecialchars($movie['description']) ?></div>
                <div class="movie-cast"><strong>Starring:</strong> <?= implode(', ', $movie['cast']) ?></div>
            </div>
        </div>
        <div class="showtime-section">
            <div class="showtime-title">Select Showtime</div>
            <div class="date-tabs">
                <?php foreach ($dates as $d): ?>
                    <a href="?movie=<?= urlencode($movie['title']) ?>&date=<?= urlencode($d) ?>" class="date-tab<?= ($selected_date==$d)?' selected':'' ?>"><?= htmlspecialchars($d) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="showtime-btns">
                <?php foreach ($showtimes[$selected_date] as [$time, $active]): ?>
                    <?php if ($active): ?>
                        <a href="confirmation.php?movie=<?= urlencode($movie['title']) ?>&showtime=<?= urlencode($selected_date . ', ' . $time) ?>">
                            <button class="showtime-btn"><?= htmlspecialchars($time) ?></button>
                        </a>
                    <?php else: ?>
                        <button class="showtime-btn inactive" disabled><?= htmlspecialchars($time) ?></button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="actions">
            <a href="confirmation.php"><button class="confirm-btn">Confirm Ticket</button></a>
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
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
        <div class="footer-label">Made with <span style="color:#e25555;">&#10084;</span></div>
    </div>
</body>
</html>
