<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$baseUrl = $_ENV['BASE_URL'] ?? '/';

require __DIR__ . '/db.php';
require __DIR__ . '/components/header.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
};

$username = $_SESSION['username'] ?? '';
$user_id = $_SESSION['user_id'] ?? '';
$role = $_SESSION['role'] ?? '';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);


$catsRes = $conn->query("SELECT id, name FROM categories ORDER BY name");
$cats = [];
while ($row = $catsRes->fetch_assoc()) $cats[] = $row;

?>

<head>
  <title>Landing Page</title>

</head>

<body>
  <?php require __DIR__ . '/components/navbar.php'; ?>

  <!-- <h1>Landing Page</h1> -->

  <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
      <strong><?= $flash['message'] ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>


  <!-- Hi, <?= htmlspecialchars($username) ?>!
  Hi, <?= htmlspecialchars($user_id) ?>!
  Hi, <?= htmlspecialchars($role) ?>! -->

  <div class="">
    <div class="row">
      <div class="col mx-auto">
        <div class="card shadow-sm">
          <!-- <div class="card-header"><strong>Create FAQ</strong></div> -->
          <div class="card-body">
            <?php if (isset($_GET['ok'])): ?>
              <div class="alert alert-success">Saved.</div>
            <?php endif; ?>


            <form method="post" action="functions/store_faq.php">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Question</label>
                    <textarea name="question" class="form-control" rows="6" required></textarea>
                  </div>

                </div>

                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Answer</label>
                    <textarea name="answer" class="form-control" rows="6" required></textarea>
                  </div>

                </div>
              </div>


              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Category</label>
                  <div class="input-group">
                    <select name="category_id" class="form-select" required>
                      <option value="" disabled selected>Choose...</option>
                      <?php foreach ($cats as $c): ?>
                        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                    <a href="categories.php" class="btn btn-outline-secondary">Add new</a>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Active</label>
                  <select name="active" class="form-select" required>
                    <option value="TRUE" selected>TRUE</option>
                    <option value="FALSE">FALSE</option>
                  </select>
                </div>
              </div>

              <div class="row g-3 mt-1">
                <div class="col-md-6">
                  <label class="form-label">Keywords (comma separated)</label>
                  <input name="keywords" class="form-control" placeholder="eligibility, mid-career, ...">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Person or Group</label>
                  <input name="person_or_group" class="form-control" placeholder="e.g., Student Affairs">
                </div>
              </div>

              <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary">Save</button>
                <a href="list_faqs.php" class="btn btn-outline-secondary">Go to View</a>
              </div>
              <p class="text-muted mt-3 mb-0"><small><strong>Last Updated</strong> is auto-set on save/edit.</small></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>