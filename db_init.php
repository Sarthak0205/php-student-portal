<?php
require_once 'db.php';

try {
    // Create tables
    execStmt("CREATE TABLE IF NOT EXISTS courses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT UNIQUE,
        title TEXT,
        description TEXT
    )");

    execStmt("CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id TEXT UNIQUE,
        name TEXT
    )");

    execStmt("CREATE TABLE IF NOT EXISTS enrollments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER,
        course_id INTEGER,
        enrolled_at TEXT,
        FOREIGN KEY(student_id) REFERENCES students(id),
        FOREIGN KEY(course_id) REFERENCES courses(id)
    )");

    execStmt("CREATE TABLE IF NOT EXISTS attendance (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        enrollment_id INTEGER,
        date TEXT,
        status TEXT, -- present/absent
        FOREIGN KEY(enrollment_id) REFERENCES enrollments(id)
    )");

    execStmt("CREATE TABLE IF NOT EXISTS schedules (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        course_id INTEGER,
        day TEXT,
        time TEXT,
        venue TEXT,
        FOREIGN KEY(course_id) REFERENCES courses(id)
    )");

    // Seed sample courses
    $existing = fetchOne("SELECT COUNT(*) as c FROM courses");
    if ($existing && $existing['c'] == 0) {
        execStmt("INSERT INTO courses (code, title, description) VALUES
            ('CS101', 'Introduction to Programming', 'Basics of programming in C/PHP'),
            ('DSA201', 'Data Structures & Algorithms', 'Foundations of algorithms and data structures'),
            ('ML301', 'Machine Learning Basics', 'Intro to ML concepts')");
    }

    // Seed sample schedules
    $existingSched = fetchOne("SELECT COUNT(*) as c FROM schedules");
    if ($existingSched && $existingSched['c'] == 0) {
        // look up course ids
        $courses = fetchAll("SELECT id, code FROM courses");
        foreach ($courses as $c) {
            if ($c['code'] == 'CS101') {
                execStmt("INSERT INTO schedules (course_id, day, time, venue) VALUES (?, ?, ?, ?)", [$c['id'], 'Mon', '10:00 - 11:30', 'Room A']);
            } elseif ($c['code'] == 'DSA201') {
                execStmt("INSERT INTO schedules (course_id, day, time, venue) VALUES (?, ?, ?, ?)", [$c['id'], 'Wed', '12:00 - 13:30', 'Room B']);
            } else {
                execStmt("INSERT INTO schedules (course_id, day, time, venue) VALUES (?, ?, ?, ?)", [$c['id'], 'Fri', '14:00 - 15:30', 'Room C']);
            }
        }
    }

    echo "<h2>Database initialized successfully.</h2>";
    echo "<p>Delete this file (db_init.php) after setup for security.</p>";
} catch (Exception $e) {
    echo "<pre>Init error: " . htmlspecialchars($e->getMessage()) . "</pre>";
}
