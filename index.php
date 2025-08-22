<?php
// index.php - simple dashboard
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Portal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Student Portal</h1>
    <nav>
      <a href="courses.php">Courses & Enroll</a>
      <a href="mycourses.php">My Enrollments</a>
      <a href="attendance.php">Mark / View Attendance</a>
      <a href="schedule.php">Course Schedules</a>
    </nav>

    <section>
      <h2>Quick Start</h2>
      <ol>
        <li>Run <code>db_init.php</code> once to create the database and seed courses.</li>
        <li>Open <code>courses.php</code> to enroll a student into a course.</li>
        <li>Use <code>attendance.php</code> to mark attendance for enrollments.</li>
      </ol>
    </section>
  </div>
</body>
</html>
