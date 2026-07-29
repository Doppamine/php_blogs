<?php

declare(strict_types=1);

use App\Commands\SeedCommand;
use App\Core\Database;

$config = require __DIR__ . '/../bootstrap.php';

$commands = [
    'db:seed' => static function () use ($config) {
        $pdo = Database::getConnection($config);
        $seedCommand = new SeedCommand($pdo);
        return $seedCommand->run();
    },
];

$name = $argv[1] ?? null;

if ($name === null || !isset($commands[$name])) {
    fwrite(STDERR, 'Usage: bin/console.php <command>' . PHP_EOL . PHP_EOL . 'Available commands:' . PHP_EOL);

    foreach (array_keys($commands) as $command) {
        fwrite(STDERR, ' '. $command . PHP_EOL);
    }
    exit(1);
}



try {
    exit($commands[$name]());
} catch (Throwable $e) {
    fwrite(STDERR, 'Error: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}