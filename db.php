<?php
// db.php - connect to SQLite DB and helper functions
$dbFile = __DIR__ . '/portal.db';
$dsn = "sqlite:$dbFile";

try {
    $pdo = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    die("DB connection failed: " . htmlspecialchars($e->getMessage()));
}

// Helper: fetch all
function fetchAll($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Helper: fetch one
function fetchOne($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// Helper: execute
function execStmt($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}
