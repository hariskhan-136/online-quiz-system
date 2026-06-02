<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's past results
$stmt = $pdo->prepare("SELECT * FROM results WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$results = $stmt->fetchAll();

// Get total questions available
$q_stmt = $pdo->query("SELECT COUNT(*) FROM questions");
$total_available = $q_stmt->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Quiz System</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container main-container">
    <div class="row">
        <!-- Start Quiz Section -->
        <div class="col-md-4">
            <div class="custom-card text-center">
                <div class="card-body py-5">
                    <div class="display-4 text-primary mb-3">🎓</div>
                    <h4 class="mb-3">Ready for a Quiz?</h4>
                    <p class="text-muted mb-4">Test your knowledge with our <?php echo $total_available; ?> questions.</p>
                    <?php if ($total_available > 0): ?>
                        <a href="quiz.php" class="btn btn-primary-custom w-100">Start Quiz Now</a>
                    <?php else: ?>
                        <button class="btn btn-secondary w-100" disabled>No questions available</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Past Results Section -->
        <div class="col-md-8">
            <div class="custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Your Past Results</span>
                </div>
                <div class="card-body">
                    <?php if (count($results) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $result): 
                                        $percentage = ($result['score'] / $result['total_questions']) * 100;
                                        $statusClass = $percentage >= 50 ? 'bg-success' : 'bg-danger';
                                        $statusText = $percentage >= 50 ? 'Passed' : 'Failed';
                                    ?>
                                    <tr>
                                        <td><?php echo date('M j, Y g:i A', strtotime($result['created_at'])); ?></td>
                                        <td><strong><?php echo $result['score']; ?></strong> / <?php echo $result['total_questions']; ?></td>
                                        <td><?php echo round($percentage, 1); ?>%</td>
                                        <td><span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <p>You haven't taken any quizzes yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
