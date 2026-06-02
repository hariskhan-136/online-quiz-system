<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

// Fetch all questions
$stmt = $pdo->query("SELECT id, question, option_a, option_b, option_c, option_d FROM questions ORDER BY RAND()");
$questions = $stmt->fetchAll();

if (count($questions) == 0) {
    echo "No questions available. Please contact admin.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz - Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Quiz System</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Good luck, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
            <a href="index.php" class="btn btn-sm btn-outline-light">Cancel Quiz</a>
        </div>
    </div>
</nav>

<div class="container main-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="submit.php" method="POST">
                <?php foreach ($questions as $index => $q): ?>
                    <div class="question-block">
                        <h5><?php echo ($index + 1) . ". " . htmlspecialchars($q['question']); ?></h5>
                        
                        <div class="options-container mt-3">
                            <!-- Option A -->
                            <input type="radio" name="q_<?php echo $q['id']; ?>" value="A" id="q_<?php echo $q['id']; ?>_A" class="d-none option-input" required>
                            <label for="q_<?php echo $q['id']; ?>_A" class="option-label">
                                <strong>A.</strong> <?php echo htmlspecialchars($q['option_a']); ?>
                            </label>

                            <!-- Option B -->
                            <input type="radio" name="q_<?php echo $q['id']; ?>" value="B" id="q_<?php echo $q['id']; ?>_B" class="d-none option-input" required>
                            <label for="q_<?php echo $q['id']; ?>_B" class="option-label">
                                <strong>B.</strong> <?php echo htmlspecialchars($q['option_b']); ?>
                            </label>

                            <!-- Option C -->
                            <input type="radio" name="q_<?php echo $q['id']; ?>" value="C" id="q_<?php echo $q['id']; ?>_C" class="d-none option-input" required>
                            <label for="q_<?php echo $q['id']; ?>_C" class="option-label">
                                <strong>C.</strong> <?php echo htmlspecialchars($q['option_c']); ?>
                            </label>

                            <!-- Option D -->
                            <input type="radio" name="q_<?php echo $q['id']; ?>" value="D" id="q_<?php echo $q['id']; ?>_D" class="d-none option-input" required>
                            <label for="q_<?php echo $q['id']; ?>_D" class="option-label">
                                <strong>D.</strong> <?php echo htmlspecialchars($q['option_d']); ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-center mb-5">
                    <button type="submit" class="btn btn-primary-custom btn-lg px-5">Submit Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
