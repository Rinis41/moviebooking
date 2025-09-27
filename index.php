<?php
require 'pdo.php';
session_start();

$username = $_SESSION['username'] ?? null;

// Fetch movies from database
$stmt = $pdo->query("SELECT title, img, genres, imdb FROM movies ORDER BY id DESC");
$trending = $stmt->fetchAll();

function render_movie_card($m) {
    $title = htmlspecialchars($m['title']);
    $img = htmlspecialchars($m['img']);
    $imdb = htmlspecialchars($m['imdb'] ?? '');
    $tags = '';
    // Handle genres as comma separated string
    $genres = is_array($m['genres']) ? $m['genres'] : explode(',', $m['genres']);
    foreach ($genres as $g) {
        $tags .= "<span class=\"genre-tag\">".htmlspecialchars(trim($g))."</span>";
    }
    return <<<HTML
    <a href="booking.php?movie={$title}" style="text-decoration:none;color:inherit">
        <div class="movie-card">
            <img src="{$img}" alt="{$title}">
            <div class="movie-title">{$title}</div>
            <div class="genre-tags">{$tags}</div>
            <div class="imdb-rating">IMDb {$imdb}</div>
        </div>
    </a>
HTML;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Movie Booking - Home</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body{margin:0;background:#f4f6f8;font-family:Arial;color:#222}
        .header{background:#181c24;color:#fff;padding:10px 24px;display:flex;align-items:center;justify-content:space-between;height:64px}
        .logo{color:#fff;text-decoration:none;font-weight:bold;font-size:1.2rem}
        .hero{display:flex;align-items:center;justify-content:center;padding:40px;background:linear-gradient(90deg,#181c24 60%,#232733)}
        .hero-img{width:260px;border-radius:10px;margin-right:32px;box-shadow:0 8px 32px rgba(0,0,0,0.25)}
        .hero-title{font-size:2rem;color:#fff;margin:0 0 8px}
        .cta-btn{background:#ffb400;color:#181c24;border:none;padding:10px 18px;border-radius:20px;cursor:pointer}
        .section-title{font-weight:700;margin:28px 0 12px 40px}
        .trending-list{display:flex;gap:20px;overflow-x:auto;padding:0 40px 24px}
        .movie-card{background:#fff;border-radius:10px;width:180px;min-width:180px;box-shadow:0 2px 8px rgba(0,0,0,0.07);overflow:hidden}
        .movie-card img{width:100%;height:260px;object-fit:cover}
        .movie-title{padding:8px 10px;font-weight:600;text-align:center}
        .genre-tags{display:flex;gap:6px;flex-wrap:wrap;justify-content:center;padding-bottom:6px}
        .genre-tag{background:#232733;color:#ffb400;border-radius:6px;padding:2px 8px;font-size:0.8rem}
        .imdb-rating{background:#ffe082;border-radius:6px;padding:4px 8px;color:#181c24;font-weight:700;text-align:center;margin:6px auto 12px;width:70px}
        /* simple controls */
        .slider-wrap{display:flex;align-items:center;gap:12px;padding-left:40px}
        button.ctrl{background:#fff;border:1px solid #ddd;padding:6px 8px;border-radius:6px;cursor:pointer}
        @media(max-width:900px){.hero{flex-direction:column;text-align:center}.hero-img{margin:0 0 18px}}
    </style>
</head>
<body>
    <div class="header">
        <a class="logo" href="index.php">🎬 MovieBook</a>
        <div style="display:flex;gap:18px;align-items:center">
            <nav style="display:flex;gap:14px">
                <a href="index.php" style="color:#fff;text-decoration:none">Home</a>
                <a href="index.php" style="color:#fff;text-decoration:none">Movies</a>
                <a href="dashboard.php" style="color:#fff;text-decoration:none">Dashboard</a>
            </nav>
            <div style="color:#ffb400;margin-left:12px">
                <?= $username ? 'Welcome, ' . htmlspecialchars($username) : '<a href="login.php" style="color:#ffb400;text-decoration:none">Login</a>' ?>
            </div>
        </div>
    </div>

    <div class="hero">
        <img class="hero-img" src="images/interstellar.png" alt="Interstellar">
        <div class="hero-content">
            <h1 class="hero-title">Interstellar</h1>
            <p style="color:#e0e0e0;max-width:520px">A team of explorers travel through a wormhole in space in an attempt to ensure humanity's survival.</p>
            <div style="margin-top:14px">
                <a href="booking.php?movie=Interstellar"><button class="cta-btn">Book Now</button></a>
            </div>
        </div>
    </div>

    <div class="section-title">Trending Now</div>
    <div class="slider-wrap">
        <button id="tr-prev" class="ctrl">◀</button>
        <div id="trending-list" class="trending-list">
            <?php foreach ($trending as $m): echo render_movie_card($m); endforeach; ?>
        </div>
        <button id="tr-next" class="ctrl">▶</button>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.cta-btn').forEach(btn=>{
        btn.addEventListener('click', function(){ location.href='dashboard.php'; });
    });
    const list = document.getElementById('trending-list');
    document.getElementById('tr-next').addEventListener('click', ()=> list.scrollBy({ left: 220, behavior: 'smooth' }));
    document.getElementById('tr-prev').addEventListener('click', ()=> list.scrollBy({ left: -220, behavior: 'smooth' }));
});
</script>
</body>
</html>