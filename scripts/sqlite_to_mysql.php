<?php

declare(strict_types=1);

function getArg(string $name, ?string $default = null): ?string
{
    global $argv;
    foreach ($argv as $arg) {
        if (str_starts_with($arg, "--{$name}=")) {
            return substr($arg, strlen("--{$name}="));
        }
    }
    return $default;
}

$sqlitePath = getArg('sqlite', __DIR__.'/../database/database.sqlite');
$mysqlHost = getArg('host', '127.0.0.1');
$mysqlPort = getArg('port', '3306');
$mysqlDatabase = getArg('database', 'Petfinder');
$mysqlUsername = getArg('username', 'root');
$mysqlPassword = getArg('password', '');

if (!$sqlitePath || !is_file($sqlitePath)) {
    fwrite(STDERR, "SQLite database not found: {$sqlitePath}\n");
    exit(1);
}

try {
    $sqlite = new PDO('sqlite:'.$sqlitePath);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mysql = new PDO(
        "mysql:host={$mysqlHost};port={$mysqlPort};dbname={$mysqlDatabase};charset=utf8mb4",
        $mysqlUsername,
        $mysqlPassword
    );
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Throwable $e) {
    fwrite(STDERR, "Connection failed: ".$e->getMessage()."\n");
    exit(1);
}

$tables = $sqlite
    ->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")
    ->fetchAll(PDO::FETCH_COLUMN);

$mysql->exec('SET FOREIGN_KEY_CHECKS=0');

foreach ($tables as $table) {
    $existsStmt = $mysql->prepare('SHOW TABLES LIKE ?');
    $existsStmt->execute([$table]);
    $mysqlTable = $existsStmt->fetchColumn();

    if (!$mysqlTable) {
        echo "Skipping {$table}: table does not exist in MySQL\n";
        continue;
    }

    $sqliteColumns = $sqlite->query("PRAGMA table_info(`{$table}`)")->fetchAll(PDO::FETCH_ASSOC);
    $sqliteColumnNames = array_map(static fn (array $col) => (string) $col['name'], $sqliteColumns);

    $mysqlColumns = $mysql->query("DESCRIBE `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
    $mysqlColumnNames = array_map(static fn (array $col) => (string) $col['Field'], $mysqlColumns);

    $commonColumns = array_values(array_filter(
        $mysqlColumnNames,
        static fn (string $col) => in_array($col, $sqliteColumnNames, true)
    ));

    if ($commonColumns === []) {
        echo "Skipping {$table}: no common columns\n";
        continue;
    }

    $mysql->exec("TRUNCATE TABLE `{$table}`");

    $columnList = '`'.implode('`,`', $commonColumns).'`';
    $placeholders = implode(',', array_fill(0, count($commonColumns), '?'));
    $insert = $mysql->prepare("INSERT INTO `{$table}` ({$columnList}) VALUES ({$placeholders})");

    $rows = $sqlite->query("SELECT {$columnList} FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $values = [];
        foreach ($commonColumns as $col) {
            $values[] = $row[$col] ?? null;
        }
        $insert->execute($values);
    }

    echo "Copied {$table}: ".count($rows)." row(s)\n";
}

$mysql->exec('SET FOREIGN_KEY_CHECKS=1');
echo "Done.\n";
