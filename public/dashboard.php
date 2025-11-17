<?php
require_once __DIR__ . '/../src/session.php';
start_session();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$title = 'Dashboard';
require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h3 class="mb-3">Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?></h3>
        <p class="text-muted">Has iniciado sesión correctamente.</p>
        <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
