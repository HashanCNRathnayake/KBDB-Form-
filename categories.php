<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$baseUrl = $_ENV['BASE_URL'] ?? '/';

require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
};

$username = $_SESSION['username'] ?? '';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/components/header.php';

require __DIR__ . '/components/navbar.php';

$ok = isset($_GET['ok']);
$res = $conn->query("SELECT * FROM categories ORDER BY name");
$cats = [];
while ($row = $res->fetch_assoc()) $cats[] = $row;
?>
<div class="">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><strong>Categories</strong></div>
                <div class="card-body">
                    <?php if ($ok): ?><div class="alert alert-success">Category added.</div><?php endif; ?>
                    <form class="row g-2" method="post" action="functions/add_category.php">
                        <div class="col-md-7">
                            <input name="name" class="form-control" placeholder="Category name" required>
                        </div>
                        <div class="col-md-2">
                            <select id="colorSelect" name="color" class="mb-1 form-select" required>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="yellow">Yellow</option>
                                <option value="pink">Pink</option>
                                <option value="red">Red</option>
                                <option value="purple">Purple</option>
                                <option value="orange">Orange</option>
                                <option value="teal">Teal</option>
                                <option value="gray">Gray</option>
                                <option value="brown">Brown</option>
                            </select>

                            <!-- Preview pill -->
                            <div id="colorPreview" class="cat-pill cat-blue mt-2">Preview</div>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header"><strong>Existing</strong></div>
                <div class="card-body">
                    <?php foreach ($cats as $c): ?>
                        <div class="mb-2">
                            <span class="cat-pill <?= 'cat-' . htmlspecialchars($c['color']) ?>"><?= htmlspecialchars($c['name']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const select = document.getElementById('colorSelect');
    const preview = document.getElementById('colorPreview');

    function updatePreview() {
        const val = select.value;
        preview.className = 'cat-pill cat-' + val;
        preview.textContent = val.charAt(0).toUpperCase() + val.slice(1);
    }

    select.addEventListener('change', updatePreview);
    updatePreview(); // run once at load
</script>

</body>

</html>