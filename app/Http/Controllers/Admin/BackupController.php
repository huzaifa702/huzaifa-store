<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Services\BackupService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::latest()->paginate(15);
        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        $adminId = session('admin_id');
        $backup = BackupService::createBackup($adminId);

        ActivityLogService::log('backup_created', "Database backup '{$backup->filename}' created", null, $backup);

        return back()->with('success', 'Backup created successfully! File: ' . $backup->filename);
    }

    public function download(Backup $backup)
    {
        $path = storage_path('app/' . $backup->path);

        if (!file_exists($path)) {
            return back()->with('error', 'Backup file not found.');
        }

        return response()->download($path, $backup->filename);
    }

    public function restore(Backup $backup)
    {
        $result = BackupService::restoreBackup($backup);

        if ($result) {
            ActivityLogService::log('backup_restored', "Database restored from '{$backup->filename}'", null, $backup);
            return back()->with('success', 'Database restored successfully from backup!');
        }

        return back()->with('error', 'Failed to restore backup.');
    }

    public function destroy(Backup $backup)
    {
        ActivityLogService::log('backup_deleted', "Backup '{$backup->filename}' deleted", null, $backup);
        BackupService::deleteBackup($backup);

        return back()->with('success', 'Backup deleted successfully!');
    }
}
