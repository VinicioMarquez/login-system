<?php
// Usa Bootstrap 5 por CDN
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'Login System') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f5f7fb; }
    .card { border: none; border-radius: 14px; }
    .brand { font-weight: 700; letter-spacing: .3px; }
    .footer { color:#6c757d; font-size:.9rem; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
  <div class="container">
    <a class="navbar-brand brand" href="login.php">Login System</a>
    <div>
      <a class="btn btn-outline-primary me-2" href="register.php">Registro</a>
      <a class="btn btn-primary" href="login.php">Login</a>
    </div>
  </div>
</nav>
<div class="container py-5">
