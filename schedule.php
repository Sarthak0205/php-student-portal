<?php
require_once 'db.php';
$courses = fetchAll("SELECT * FROM courses ORDER BY code");
?>
<!doctype html><html><head>
  <meta charset="utf-8"><title>Schedules</title>
  <link rel="stylesheet" href="style.css">
</head><body>
  <div class="container">
    <h1>Course Schedules</h1>
    <p><a href="index.php">Back to Dashboard</a></p>

    <?php foreach($courses as $c): ?>
      <section class="course">
        <h2><?=htmlspecialchars($c['code'].' - '.$c['title'])?></h2>
        <p><?=htmlspecialchars($c['description'])?></p>
        <?php
          $sched = fetchAll("SELECT * FROM schedules WHERE course_id = ? ORDER BY id", [$c['id']]);
          if (!$sched) {
            echo "<p><em>No schedule available.</em></p>";
          } else {
            echo "<ul>";
            foreach($sched as $s) {
              echo "<li>" . htmlspecialchars($s['day'] . " | " . $s['time'] . " | " . $s['venue']) . "</li>";
            }
            echo "</ul>";
          }
        ?>
      </section>
    <?php endforeach; ?>
  </div>
</body></html>
