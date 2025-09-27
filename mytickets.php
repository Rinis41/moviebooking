<?php
session_start();
require_once 'db.php';
// Assume user id is stored in session as $_SESSION['user_id']
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}
// Handle ticket deletion
if (isset($_GET['delete'])) {
    $ticket_id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM tickets WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $ticket_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: mytickets.php');
    exit;
}
// Fetch tickets
$stmt = $conn->prepare('SELECT * FROM tickets WHERE user_id = ? ORDER BY booked_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tickets = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets | CineTickets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #fff; color: #222e3a; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; }
        .container { max-width: 900px; margin: 36px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 36px 32px; }
        h2 { font-size: 2rem; margin-bottom: 24px; color: #1a2340; }
        .ticket-list { list-style: none; padding: 0; }
        .ticket-item { display: flex; align-items: center; justify-content: space-between; background: #f7f8fa; border-radius: 10px; margin-bottom: 18px; padding: 18px 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .ticket-info { flex: 1; }
        .ticket-title { font-size: 1.2rem; font-weight: 600; color: #007bff; }
        .ticket-details { font-size: 1rem; color: #222e3a; margin-top: 6px; }
        .delete-btn { background: #e25555; color: #fff; border: none; border-radius: 8px; padding: 8px 18px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .delete-btn:hover { background: #b32d2d; }
        .no-tickets { color: #888; font-size: 1.1rem; text-align: center; margin-top: 32px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Tickets</h2>
        <?php if (count($tickets) === 0): ?>
            <div class="no-tickets">You have not booked any tickets yet.</div>
        <?php else: ?>
            <ul class="ticket-list">
                <?php foreach ($tickets as $ticket): ?>
                    <li class="ticket-item">
                        <div class="ticket-info">
                            <div class="ticket-title">Movie: <?= htmlspecialchars($ticket['movie']) ?></div>
                            <div class="ticket-details">
                                Showtime: <?= htmlspecialchars($ticket['showtime']) ?><br>
                                Booked At: <?= htmlspecialchars($ticket['booked_at']) ?>
                            </div>
                        </div>
                        <a href="mytickets.php?delete=<?= $ticket['id'] ?>" onclick="return confirm('Delete this ticket?')">
                            <button class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <button onclick="window.history.back();">Go Back</button>
    </div>
</body>
</html>
