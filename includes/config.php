<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/database.php';

requireLogin();

$lm_db_host = 'localhost';
$lm_db_name = 'personal_portfolio_db';
$lm_db_user = 'root';
$lm_db_pass = '';
$lm_db_charset = 'utf8mb4';

$lm_dsn = "mysql:host=$lm_db_host;dbname=$lm_db_name;charset=$lm_db_charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo_lm = new PDO($lm_dsn, $lm_db_user, $lm_db_pass, $options);
} catch (\PDOException $e) {
    die("Learn Mode DB Connection Failed: " . $e->getMessage());
}

$pdo_main = $pdo;
?>