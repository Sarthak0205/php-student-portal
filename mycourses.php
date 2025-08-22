<?php
require_once 'db.php';
$student_id_input = trim($_GET['student_id'] ?? '');
$student = null;
$enrollments = [];

if ($student_id_input !== '') {
    $student = fetchOne("SELECT * FROM students WHERE student_id = ?", [$student_id_input]);
    if ($student) {
        $enrollments = fetchAll("SELECT e.id as enroll_id, c.* FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE e.student_id = ?", [$student['id']]);
    }
}
?>
<!doctype html><html><head>
  <meta charset="utf-8"><title>My Enrollments</title>
  <link rel="stylesheet" href="style.css">
</head><body>
  <div class="container">
    <h1>My Enrollments</h1>
    <p><a href="index.php">Back to Dashboard</a></p>

    <form method="get">
      <label>Enter Student ID: <input name="student_id" value="<?=htmlspecialchars($student_id_input)?>"></label>
      <button type="submit">Lookup</button>
    </form>

    <?php if($student === null && $student_id_input !== ''): ?>
      <p>No student found for ID <?=htmlspecialchars($student_id_input)?></p>
    <?php elseif($student): ?>
      <h2><?=htmlspecialchars($student['name'])?> (<?=htmlspecialchars($student['student_id'])?>)</h2>
      <?php if(!$enrollments): ?>
        <p>No enrollments yet.</p>
      <?php else: ?>
        <table>
          <thead><tr><th>Code</th><th>Title</th><th>Enrolled At</th></tr></thead>
          <tbody>
          <?php foreach($enrollments as $en): ?>
            <tr>
              <td><?=htmlspecialchars($en['code'])?></td>
              <td><?=htmlspecialchars($en['title'])?></td>
              <td><!-- fetch enrolled_at -->
                <?=htmlspecialchars(fetchOne("SELECT enrolled_at FROM enrollments WHERE student_id = ? AND course_id = ?", [$student['id'], $en['id']])['enrolled_at'])?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body></html>
