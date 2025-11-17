<?php
function env($key, $default = null) {
    static $vars = null;
    if ($vars === null) {
        $vars = [];
        $envPath = __DIR__ . '/../.env';
        if (file_exists($envPath)) {
            foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') continue;
                if (!str_contains($line, '=')) continue;
                [$k, $v] = array_map('trim', explode('=', $line, 2));
                $vars[$k] = $v;
            }
        }
    }
    return $vars[$key] ?? $default;
}
