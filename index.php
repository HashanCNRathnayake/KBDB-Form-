<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$baseUrl = $_ENV['BASE_URL'] ?? '/';

require 'db.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
};

$username = $_SESSION['username'] ?? '';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/components/header.php';
?>

<head>
  <title>Landing Page</title>

</head>

<body>
  <?php require __DIR__ . '/components/navbar.php';
  ?>

  <h1>Landing Page</h1>

  <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
      <strong><?= $flash['message'] ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>


  Hi, <?= htmlspecialchars($username) ?>!
</body>

</html>