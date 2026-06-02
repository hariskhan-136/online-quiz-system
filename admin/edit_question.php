<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];

    $stmt = $pdo->prepare("UPDATE questions SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_answer=? WHERE id=?");
    if ($stmt->execute([$question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $id])) {
        $msg = '<div class="alert alert-success">Question updated successfully! <a href="index.php">Go back</a></div>';
    } else {
        $msg = '<div class="alert alert-danger">Error updating question.</div>';
    }
}

// Fetch existing question
$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$q = $stmt->fetch();

if (!$q) {
    echo "Question not found!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Quiz System - Admin Area</a>
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-sm btn-outline-light">Back to Dashboard</a>
        </div>
    </div>
</nav>

<div class="container main-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="custom-card">
                <div class="card-header">Edit Question</div>
                <div class="card-body">
                    <?php echo $msg; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Question Text</label>
                            <textarea name="question" class="form-control" rows="3" required><?php echo htmlspecialchars($q['question']); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Option A</label>
                                <input type="text" name="option_a" class="form-control" value="<?php echo htmlspecialchars($q['option_a']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Option B</label>
                                <input type="text" name="option_b" class="form-control" value="<?php echo htmlspecialchars($q['option_b']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Option C</label>
                                <input type="text" name="option_c" class="form-control" value="<?php echo htmlspecialchars($q['option_c']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Option D</label>
                                <input type="text" name="option_d" class="form-control" value="<?php echo htmlspecialchars($q['option_d']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Correct Answer</label>
                            <select name="correct_answer" class="form-control" required>
                                <option value="A" <?php echo ($q['correct_answer'] == 'A') ? 'selected' : ''; ?>>A</option>
                                <option value="B" <?php echo ($q['correct_answer'] == 'B') ? 'selected' : ''; ?>>B</option>
                                <option value="C" <?php echo ($q['correct_answer'] == 'C') ? 'selected' : ''; ?>>C</option>
                                <option value="D" <?php echo ($q['correct_answer'] == 'D') ? 'selected' : ''; ?>>D</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100">Update Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
