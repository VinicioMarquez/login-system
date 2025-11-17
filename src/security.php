<?php
// src/security.php
function clean(string $s): string {
  return trim($s);
}
function valid_name(string $s): bool {
  return strlen($s) >= 2 && strlen($s) <= 100;
}
function valid_email(string $e): bool {
  return filter_var($e, FILTER_VALIDATE_EMAIL) !== false && strlen($e) <= 190;
}
function valid_password(string $p): bool {
  return strlen($p) >= 8; // mínimo: puedes añadir reglas de complejidad
}
