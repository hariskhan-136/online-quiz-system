<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch all questions
$q_stmt = $pdo->query("SELECT * FROM questions ORDER BY id DESC");
$questions = $q_stmt->fetchAll();

// Fetch all user results
$r_stmt = $pdo->query("
    SELECT r.*, u.name, u.email 
    FROM results r 
    JOIN users u ON r.user_id = u.id 
    ORDER BY r.created_at DESC
");
$results = $r_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Quiz System - Admin Area</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Admin: <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container main-container">
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="custom-card text-center p-4">
                <h2 class="text-primary"><?php echo count($questions); ?></h2>
                <p class="text-muted mb-0">Total Questions</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="custom-card text-center p-4">
                <h2 class="text-success"><?php echo count($results); ?></h2>
                <p class="text-muted mb-0">Total Quizzes Taken</p>
            </div>
        </div>
    </div>

    <!-- Questions Management -->
    <div class="custom-card mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Questions</span>
            <a href="add_question.php" class="btn btn-sm btn-primary-custom">
                <i class="bi bi-plus-lg"></i> Add New Question
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="45%">Question</th>
                            <th width="35%">Options</th>
                            <th width="5%">Ans</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $q): ?>
                        <tr>
                            <td><?php echo $q['id']; ?></td>
                            <td><?php echo htmlspecialchars($q['question']); ?></td>
                            <td class="small">
                                A: <?php echo htmlspecialchars($q['option_a']); ?><br>
                                B: <?php echo htmlspecialchars($q['option_b']); ?><br>
                                C: <?php echo htmlspecialchars($q['option_c']); ?><br>
                                D: <?php echo htmlspecialchars($q['option_d']); ?>
                            </td>
                            <td><strong><?php echo $q['correct_answer']; ?></strong></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="edit_question.php?id=<?php echo $q['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete_question.php?id=<?php echo $q['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this question?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($questions) == 0): ?>
                            <tr><td colspan="5" class="text-center">No questions found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Results -->
    <div class="custom-card">
        <div class="card-header">
            <span>User Results</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Date Taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $r): 
                            $percentage = ($r['score'] / $r['total_questions']) * 100;
                            $statusClass = $percentage >= 50 ? 'text-success' : 'text-danger';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['name']); ?></td>
                            <td><?php echo htmlspecialchars($r['email']); ?></td>
                            <td><strong><?php echo $r['score']; ?></strong> / <?php echo $r['total_questions']; ?></td>
                            <td class="fw-bold <?php echo $statusClass; ?>"><?php echo round($percentage, 1); ?>%</td>
                            <td><?php echo date('M j, Y g:i A', strtotime($r['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($results) == 0): ?>
                            <tr><td colspan="5" class="text-center">No results found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
