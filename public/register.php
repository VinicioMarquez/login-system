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
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';

        if ($nombre && filter_var($correo, FILTER_VALIDATE_EMAIL) && $contrasena) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena_hash) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$nombre, $correo, $hash]);
                header("Location: login.php");
                exit;
            } catch (PDOException $e) {
                $error = "Error: el correo ya está registrado.";
            }
        } else {
            $error = "Por favor, completa todos los campos correctamente.";
        }
    }
}

/**
 * Verifica el token de reCAPTCHA con Google
 */
function verifyRecaptcha($token, $secret) {
    if (!$token) return false;
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
    ];
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
    <h2>Registro</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
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

        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>
</div>
<?php include 'partials/footer.php'; ?>
