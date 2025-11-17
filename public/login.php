<?php
require_once __DIR__ . '/../src/env.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/session.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captcha
    $captchaToken = $_POST['g-recaptcha-response'] ?? null;
    $secret = getenv('RECAPTCHA_SECRET_KEY');

    if (!verifyRecaptcha($captchaToken, $secret)) {
        $error = "Por favor, completa el captcha.";
    } else {
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';

        if ($correo && $contrasena) {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($contrasena, $usuario['contrasena_hash'])) {
                start_session();
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nombre'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Credenciales incorrectas.";
            }
        } else {
            $error = "Por favor, completa todos los campos.";
        }
    }
}

/**
 * Verifica el token de reCAPTCHA con Google
 */
function verifyRecaptcha($token, $secret) {
    if (!$token) return false;
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = ['secret' => $secret, 'response' => $token, 'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $result = file_get_contents($url, false, stream_context_create($options));
    if ($result === false) return false;
    $json = json_decode($result, true);
    return isset($json['success']) && $json['success'] === true;
}
?>

<?php include 'partials/header.php'; ?>
<div class="container mt-5">
    <h2>Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>

        <!-- Captcha -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <div class="g-recaptcha mb-3" data-sitekey="<?= htmlspecialchars(getenv('RECAPTCHA_SITE_KEY')) ?>"></div>

        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
    </form>
</div>
<?php include 'partials/footer.php'; ?>
