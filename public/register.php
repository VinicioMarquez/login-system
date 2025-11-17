<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/session.php';
start_session();

$title = 'Registro';
$error = $ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pass   = trim($_POST['password'] ?? '');

    if ($nombre === '' || $correo === '' || $pass === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo no tiene un formato válido.';
    } else {
        try {
            $pdo = db();
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            if ($stmt->fetch()) {
                $error = 'El correo ya está registrado.';
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena_hash) VALUES (?, ?, ?)");
                $stmt->execute([$nombre, $correo, $hash]);
                $ok = 'Registro exitoso. Ahora puedes iniciar sesión.';
            }
        } catch (Throwable $e) {
            $error = 'Error de servidor: ' . $e->getMessage();
        }
    }
}

require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4 p-md-5">
        <h3 class="card-title text-center mb-4"><?= htmlspecialchars($title) ?></h3>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($ok): ?>
          <div class="alert alert-success"><?= htmlspecialchars($ok) ?></div>
        <?php endif; ?>

        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Registrarme</button>
        </form>

        <p class="text-center mt-3">
          <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </p>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
