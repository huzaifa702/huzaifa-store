<?php

namespace App\Services;

use App\Models\Backup;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    public static function createBackup(?int $adminId = null): Backup
    {
        $filename = 'backup_' . date('Y_m_d_His') . '.sql';
        $directory = storage_path('app/backups');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $filename;

        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s %s > "%s"',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            $dbPass ? '--password=' . escapeshellarg($dbPass) : '',
            escapeshellarg($dbName),
            $path
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            // If mysqldump fails, create a manual schema backup
            self::createManualBackup($path);
        }

        $size = file_exists($path) ? filesize($path) : 0;

        return Backup::create([
            'filename' => $filename,
            'path' => 'backups/' . $filename,
            'size' => $size,
            'created_by' => $adminId,
        ]);
    }

    private static function createManualBackup(string $path): void
    {
        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = 'Tables_in_' . $dbName;
        $sql = "-- Database Backup: {$dbName}\n-- Date: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;
            $createTable = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            $rows = \Illuminate\Support\Facades\DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values = collect((array)$row)->map(function ($value) {
                    if (is_null($value)) return 'NULL';
                    return "'" . addslashes($value) . "'";
                })->implode(', ');
                $sql .= "INSERT INTO `{$tableName}` VALUES ({$values});\n";
            }
            $sql .= "\n";
        }

        file_put_contents($path, $sql);
    }

    public static function restoreBackup(Backup $backup): bool
    {
        $path = storage_path('app/' . $backup->path);

        if (!file_exists($path)) {
            return false;
        }

        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s %s %s < "%s"',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            $dbPass ? '--password=' . escapeshellarg($dbPass) : '',
            escapeshellarg($dbName),
            $path
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            // Try manual restore
            $sql = file_get_contents($path);
            \Illuminate\Support\Facades\DB::unprepared($sql);
        }

        return true;
    }

    public static function deleteBackup(Backup $backup): bool
    {
        $path = storage_path('app/' . $backup->path);

        if (file_exists($path)) {
            unlink($path);
        }

        $backup->delete();

        return true;
    }

    public static function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
