<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/session.php';
start_session();

$title = 'Login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($correo === '' || $password === '') {
        $error = 'Correo y contraseña son obligatorios.';
    } else {
        try {
            $pdo = db();
            $stmt = $pdo->prepare("SELECT id, nombre, contrasena_hash FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['contrasena_hash'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nombre'];
                session_regenerate_id(true);
                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'Credenciales incorrectas.';
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
        <h3 class="card-title text-center mb-4">Iniciar sesión</h3>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>

        <p class="text-center mt-3">
          <a href="register.php">¿No tienes cuenta? Regístrate</a>
        </p>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
