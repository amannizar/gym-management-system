<?php
session_start();
require_once "backend/db.php";

if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['member_id'];
$member_name = $_SESSION['member_name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'] ?? 0;
    $message = $_POST['message'] ?? '';
    
    // Only save if action is submit, otherwise skip
    if (isset($_POST['submit_feedback'])) {
        $stmt = $conn->prepare("INSERT INTO feedback (member_id, rating, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $member_id, $rating, $message);
        $stmt->execute();
    }
    
    // In both cases, log out after
    header("Location: logout.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: var(--bg-color);
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .feedback-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.05);
            text-align: center;
        }
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 10px;
            font-size: 2rem;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            color: #444;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="feedback-card">
                <h3 class="mb-2 fw-bold text-primary">Logging out?</h3>
                <p class="text-white-50 mb-4">How was your session today, <?= htmlspecialchars($member_name) ?>?</p>
                
                <form method="POST">
                    <div class="mb-4">
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" required/><label for="star5" class="fas fa-star"></label>
                            <input type="radio" id="star4" name="rating" value="4"/><label for="star4" class="fas fa-star"></label>
                            <input type="radio" id="star3" name="rating" value="3"/><label for="star3" class="fas fa-star"></label>
                            <input type="radio" id="star2" name="rating" value="2"/><label for="star2" class="fas fa-star"></label>
                            <input type="radio" id="star1" name="rating" value="1"/><label for="star1" class="fas fa-star"></label>
                        </div>
                    </div>
                    
                    <div class="mb-4 text-start">
                        <label class="form-label text-white-50">Any suggestions for us? (Optional)</label>
                        <textarea class="form-control bg-dark text-white border-secondary" name="message" rows="3" placeholder="Tell us what we can improve..."></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" name="submit_feedback" class="btn btn-primary fw-bold py-2">Submit & Logout</button>
                        <button type="submit" name="skip_feedback" class="btn btn-outline-secondary py-2" formnovalidate>Skip & Logout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
