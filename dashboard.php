<?php
require 'pdo.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $img   = trim($_POST['img'] ?? 'images/placeholder.png');
        $genres= trim($_POST['genres'] ?? '');
        if ($title !== '') {
            $stmt = $pdo->prepare("INSERT INTO movies (title, img, genres) VALUES (?, ?, ?)");
            $stmt->execute([$title, $img, $genres]);
        }
    }
    if ($action === 'delete' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
        $stmt->execute([ (int)$_POST['id'] ]);
    }
    if ($action === 'update' && isset($_POST['id'])) {
        $id    = (int)$_POST['id'];
        $title = trim($_POST['title'] ?? '');
        $img   = trim($_POST['img'] ?? 'images/placeholder.png');
        $genres= trim($_POST['genres'] ?? '');
        if ($title !== '') {
            $stmt = $pdo->prepare("UPDATE movies SET title = ?, img = ?, genres = ? WHERE id = ?");
            $stmt->execute([$title, $img, $genres, $id]);
        }
    }
    header("Location: dashboard.php");
    exit;
}
$editMovie = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $stmt = $pdo->prepare("SELECT id, title, img, genres FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $editMovie = $stmt->fetch();
}
$stmt = $pdo->query("SELECT id, title, img, genres FROM movies ORDER BY id DESC");
$movies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Movies</title>
    <style>
        body{font-family:Arial,margin:18px;background:#f7f8fa;color:#222}
        .wrap{max-width:980px;margin:0 auto}
        .card{border:1px solid #e2e2e2;padding:12px;border-radius:8px;margin-bottom:12px;display:flex;gap:12px;align-items:center;background:#fff}
        .thumb{width:84px;height:120px;object-fit:cover;border-radius:4px;flex-shrink:0}
        .controls{display:flex;flex-direction:column;gap:6px}
        form.inline{display:inline}
        .form-row{display:flex;gap:8px;align-items:center;margin-bottom:8px}
        input[type="text"]{padding:8px;border:1px solid #ccc;border-radius:6px}
        button{padding:8px 10px;border-radius:6px;border:0;background:#007bff;color:#fff;cursor:pointer}
        button.delete{background:#d9534f}
        .small{font-size:0.9rem;color:#666}
    </style>
</head>
<body>
<div class="wrap">
    <a href="index.php" style="display:inline-block;margin-bottom:16px;">
        <button type="button">&larr; Back to Home</button>
    </a>
    <h2>Dashboard — Movies</h2>
    <?php if ($editMovie): ?>
        <h3>Edit Movie</h3>
        <form method="post" class="card" style="align-items:flex-start;flex-direction:column;">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= (int)$editMovie['id'] ?>">
            <div style="width:100%;display:flex;gap:12px;">
                <div style="flex:1;">
                    <div class="form-row">
                        <input type="text" name="title" value="<?= htmlspecialchars($editMovie['title']) ?>" placeholder="Title" required style="width:100%">
                    </div>
                    <div class="form-row">
                        <input type="text" name="img" value="<?= htmlspecialchars($editMovie['img']) ?>" placeholder="images/yourimage.png" style="width:100%">
                    </div>
                    <div class="form-row">
                        <input type="text" name="genres" value="<?= htmlspecialchars($editMovie['genres']) ?>" placeholder="genres comma separated" style="width:100%">
                    </div>
                </div>
                <div style="width:120px;text-align:center;">
                    <img src="<?= htmlspecialchars($editMovie['img']) ?>" alt="" class="thumb">
                </div>
            </div>
            <div style="width:100%;display:flex;gap:8px;justify-content:flex-end;margin-top:8px;">
                <a href="dashboard.php" style="padding:8px 12px;border-radius:6px;background:#6c757d;color:#fff;text-decoration:none;align-self:center;">Cancel</a>
                <button type="submit">Update Movie</button>
            </div>
        </form>
    <?php else: ?>
        <h3>Add Movie</h3>
        <form method="post" style="margin-bottom:18px;display:flex;gap:8px;align-items:center;">
            <input type="hidden" name="action" value="create">
            <input name="title" placeholder="Title" required>
            <input name="img" placeholder="images/yourimage.png (optional)">
            <input name="genres" placeholder="genres comma separated">
            <button type="submit">Add</button>
        </form>
    <?php endif; ?>
    <h3>Movies</h3>
    <?php if (empty($movies)): ?>
        <p class="small">No movies yet.</p>
    <?php else: ?>
        <?php foreach ($movies as $m): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($m['img']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="thumb">
                <div style="flex:1;">
                    <strong><?= htmlspecialchars($m['title']) ?></strong><br>
                    <small class="small"><?= htmlspecialchars($m['genres']) ?></small>
                </div>
                <div class="controls">
                    <a href="dashboard.php?edit=<?= (int)$m['id'] ?>" style="text-decoration:none">
                        <button type="button">Edit</button>
                    </a>
                    <form method="post" class="inline" onsubmit="return confirm('Delete movie?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
                        <button type="submit" class="delete">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>