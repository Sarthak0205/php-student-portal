<?php
require_once 'db.php';
$msg = '';
// Mark attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark') {
    $student_id = trim($_POST['student_id'] ?? '');
    $course_id = intval($_POST['course_id'] ?? 0);
    $date = trim($_POST['date'] ?? date('Y-m-d'));
    $status = in_array($_POST['status'] ?? '', ['present','absent']) ? $_POST['status'] : 'absent';

    if ($student_id === '' || $course_id <= 0) {
        $msg = "Provide student ID and course.";
    } else {
        $student = fetchOne("SELECT * FROM students WHERE student_id = ?", [$student_id]);
        if (!$student) {
            $msg = "Student not found. Enroll first.";
        } else {
            $enr = fetchOne("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?", [$student['id'], $course_id]);
            if (!$enr) {
                $msg = "No enrollment found for this course.";
            } else {
                // prevent duplicate for same date
                $exists = fetchOne("SELECT * FROM attendance WHERE enrollment_id = ? AND date = ?", [$enr['id'], $date]);
                if ($exists) {
                    execStmt("UPDATE attendance SET status = ? WHERE id = ?", [$status, $exists['id']]);
                    $msg = "Attendance updated.";
                } else {
                    execStmt("INSERT INTO attendance (enrollment_id, date, status) VALUES (?, ?, ?)", [$enr['id'], $date, $status]);
                    $msg = "Attendance recorded.";
                }
            }
        }
    }
}

// fetch courses for select
$courses = fetchAll("SELECT * FROM courses ORDER BY code");
?>
<!doctype html><html><head>
  <meta charset="utf-8"><title>Attendance</title>
  <link rel="stylesheet" href="style.css">
</head><body>
  <div class="container">
    <h1>Attendance</h1>
    <p><a href="index.php">Back to Dashboard</a></p>

    <?php if($msg): ?><div class="message"><?=htmlspecialchars($msg)?></div><?php endif; ?>

    <section>
      <h2>Mark Attendance</h2>
      <form method="post">
        <input type="hidden" name="action" value="mark">
        <label>Student ID: <input name="student_id" required></label>
        <label>Course:
          <select name="course_id" required>
            <option value="">--select--</option>
            <?php foreach($courses as $c): ?>
              <option value="<?=intval($c['id'])?>"><?=htmlspecialchars($c['code'].' - '.$c['title'])?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>Date: <input type="date" name="date" value="<?=date('Y-m-d')?>"></label>
        <label>Status:
          <select name="status">
            <option value="present">Present</option>
            <option value="absent">Absent</option>
          </select>
        </label>
        <button type="submit">Save</button>
      </form>
    </section>

    <section>
      <h2>View Attendance</h2>
      <form method="get">
        <label>Student ID: <input name="sid" value="<?=htmlspecialchars($_GET['sid'] ?? '')?>"></label>
        <button type="submit">Show</button>
      </form>

      <?php
      if (!empty($_GET['sid'])) {
        $student = fetchOne("SELECT * FROM students WHERE student_id = ?", [$_GET['sid']]);
        if (!$student) {
          echo "<p>No student found.</p>";
        } else {
          echo "<h3>" . htmlspecialchars($student['name']) . " (" . htmlspecialchars($student['student_id']) . ")</h3>";
          $rows = fetchAll("SELECT c.code,c.title,a.date,a.status FROM attendance a
            JOIN enrollments e ON e.id = a.enrollment_id
            JOIN courses c ON c.id = e.course_id
            WHERE e.student_id = ?
            ORDER BY a.date DESC", [$student['id']]);
          if (!$rows) {
            echo "<p>No attendance records.</p>";
          } else {
            echo "<table><thead><tr><th>Date</th><th>Course</th><th>Status</th></tr></thead><tbody>";
            foreach($rows as $r) {
              echo "<tr><td>".htmlspecialchars($r['date'])."</td><td>".htmlspecialchars($r['code'].' - '.$r['title'])."</td><td>".htmlspecialchars($r['status'])."</td></tr>";
            }
            echo "</tbody></table>";
          }
        }
      }
      ?>
    </section>

  </div>
</body></html>
