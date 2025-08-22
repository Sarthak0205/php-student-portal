<?php
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enroll') {
    $student_id = trim($_POST['student_id'] ?? '');
    $student_name = trim($_POST['student_name'] ?? '');
    $course_id = intval($_POST['course_id'] ?? 0);

    if ($student_id === '' || $student_name === '' || $course_id <= 0) {
        $message = "Please provide student ID, name and select a course.";
    } else {
        // ensure student exists
        $s = fetchOne("SELECT * FROM students WHERE student_id = ?", [$student_id]);
        if (!$s) {
            execStmt("INSERT INTO students (student_id, name) VALUES (?, ?)", [$student_id, $student_name]);
            $s = fetchOne("SELECT * FROM students WHERE student_id = ?", [$student_id]);
        }
        // check existing enrollment
        $exists = fetchOne("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?", [$s['id'], $course_id]);
        if ($exists) {
            $message = "You are already enrolled in this course.";
        } else {
            execStmt("INSERT INTO enrollments (student_id, course_id, enrolled_at) VALUES (?, ?, ?)", [$s['id'], $course_id, date('Y-m-d H:i:s')]);
            $message = "Enrollment successful.";
        }
    }
}

// fetch courses
$courses = fetchAll("SELECT * FROM courses ORDER BY code");
?>
<!doctype html>
<html><head>
  <meta charset="utf-8"><title>Courses</title>
  <link rel="stylesheet" href="style.css">
</head><body>
  <div class="container">
    <h1>Courses</h1>
    <p><a href="index.php">Back to Dashboard</a></p>

    <?php if($message): ?>
      <div class="message"><?=htmlspecialchars($message)?></div>
    <?php endif; ?>

    <table>
      <thead><tr><th>Code</th><th>Title</th><th>Description</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach($courses as $c): ?>
        <tr>
          <td><?=htmlspecialchars($c['code'])?></td>
          <td><?=htmlspecialchars($c['title'])?></td>
          <td><?=htmlspecialchars($c['description'])?></td>
          <td>
            <form method="post" style="display:inline">
              <input type="hidden" name="action" value="enroll">
              <input type="hidden" name="course_id" value="<?=intval($c['id'])?>">
              <input name="student_id" placeholder="Student ID (eg. S123)" required>
              <input name="student_name" placeholder="Full name" required>
              <button type="submit">Enroll</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body></html>
