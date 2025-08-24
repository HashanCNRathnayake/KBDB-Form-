<?php
date_default_timezone_set('Asia/Singapore');
session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../db.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'] ?? '/';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require __DIR__ . '/../header.php';
require __DIR__ . '/../components/navbar.php';


?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>

    <link href="dashboardStyles.css" rel="stylesheet">

</head>

<body>
    <h1>Admin Dashboard</h1>
</body>

</html>