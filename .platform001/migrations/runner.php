<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\{Config, DB};

// Загружаем .env
$envFile = dirname(__DIR__) . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
        $_ENV[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
    }
}

Config::load(dirname(__DIR__) . '/config');
$db = new DB(Config::get('db'));

$db->exec('CREATE TABLE IF NOT EXISTS migrations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL UNIQUE,
  applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

$applied = array_column($db->fetchAll('SELECT name FROM migrations'), 'name');

foreach (glob(__DIR__ . '/*.sql') as $file) {
    $name = basename($file);
    if (in_array($name, $applied, true)) {
        echo "skip  $name\n";
        continue;
    }
    echo "apply $name ... ";
    $sql = file_get_contents($file);
    // Разбиваем по ';' — безопасно для наших миграций (нет процедур/триггеров)
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
        if ($stmt !== '') $db->exec($stmt);
    }
    $db->query('INSERT INTO migrations (name) VALUES (?)', [$name]);
    echo "OK\n";
}
echo "done\n";