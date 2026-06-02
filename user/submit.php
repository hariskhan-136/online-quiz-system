<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$score = 0;
$total_questions = 0;
$review_data = [];

// Fetch all questions to verify answers
$stmt = $pdo->query("SELECT * FROM questions");
$all_questions = $stmt->fetchAll();

foreach ($all_questions as $q) {
    $q_id = $q['id'];
    $total_questions++;
    
    $user_answer = isset($_POST['q_' . $q_id]) ? $_POST['q_' . $q_id] : null;
    $correct_answer = $q['correct_answer'];
    
    $is_correct = false;
    if ($user_answer === $correct_answer) {
        $score++;
        $is_correct = true;
    }
    
    // Store data for review
    $review_data[] = [
        'question' => $q['question'],
        'option_a' => $q['option_a'],
        'option_b' => $q['option_b'],
        'option_c' => $q['option_c'],
        'option_d' => $q['option_d'],
        'correct_answer' => $correct_answer,
        'user_answer' => $user_answer,
        'is_correct' => $is_correct
    ];
}

// Save result to database
$insert = $pdo->prepare("INSERT INTO results (user_id, score, total_questions) VALUES (?, ?, ?)");
$insert->execute([$user_id, $score, $total_questions]);

$percentage = ($score / $total_questions) * 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result - Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Quiz System</a>
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-sm btn-outline-light">Back to Dashboard</a>
        </div>
    </div>
</nav>

<div class="container main-container">
    
    <!-- Score Card -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <div class="custom-card text-center p-4">
                <h3 class="mb-4">Quiz Completed!</h3>
                <div class="display-1 fw-bold <?php echo $percentage >= 50 ? 'text-success' : 'text-danger'; ?>">
                    <?php echo $score; ?><span class="fs-3 text-muted">/<?php echo $total_questions; ?></span>
                </div>
                <p class="lead mt-3">You scored <?php echo round($percentage, 1); ?>%</p>
                <a href="index.php" class="btn btn-primary-custom mt-3">Go to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Review Section -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h4 class="mb-4 text-center">Answer Review</h4>
            
            <?php foreach ($review_data as $index => $r): ?>
                <div class="review-block <?php echo $r['is_correct'] ? 'review-correct' : 'review-wrong'; ?>">
                    <h5><?php echo ($index + 1) . ". " . htmlspecialchars($r['question']); ?></h5>
                    
                    <div class="mt-3">
                        <div class="p-2 mb-2 rounded <?php echo ($r['correct_answer'] == 'A') ? 'bg-success text-white' : (($r['user_answer'] == 'A' && !$r['is_correct']) ? 'bg-danger text-white' : 'bg-light'); ?>">
                            A. <?php echo htmlspecialchars($r['option_a']); ?>
                        </div>
                        <div class="p-2 mb-2 rounded <?php echo ($r['correct_answer'] == 'B') ? 'bg-success text-white' : (($r['user_answer'] == 'B' && !$r['is_correct']) ? 'bg-danger text-white' : 'bg-light'); ?>">
                            B. <?php echo htmlspecialchars($r['option_b']); ?>
                        </div>
                        <div class="p-2 mb-2 rounded <?php echo ($r['correct_answer'] == 'C') ? 'bg-success text-white' : (($r['user_answer'] == 'C' && !$r['is_correct']) ? 'bg-danger text-white' : 'bg-light'); ?>">
                            C. <?php echo htmlspecialchars($r['option_c']); ?>
                        </div>
                        <div class="p-2 mb-2 rounded <?php echo ($r['correct_answer'] == 'D') ? 'bg-success text-white' : (($r['user_answer'] == 'D' && !$r['is_correct']) ? 'bg-danger text-white' : 'bg-light'); ?>">
                            D. <?php echo htmlspecialchars($r['option_d']); ?>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <?php if ($r['is_correct']): ?>
                            <span class="badge bg-success badge-custom"><i class="bi bi-check-circle"></i> Correct</span>
                        <?php else: ?>
                            <span class="badge bg-danger badge-custom"><i class="bi bi-x-circle"></i> Incorrect</span>
                            <span class="ms-2 text-muted small">You selected: <?php echo $r['user_answer'] ? $r['user_answer'] : 'None'; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
