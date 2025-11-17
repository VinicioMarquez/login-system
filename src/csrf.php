<?php
// src/csrf.php
require_once __DIR__ . '/session.php';

function csrf_token(): string {
  start_secure_session();
  if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf'];
}

function csrf_check(string $token): bool {
  start_secure_session();
  return hash_equals($_SESSION['csrf'] ?? '', $token);
}
