<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    // SHOW BACKUP PAGE
    public function index()
    {
        return view('backup.index');
    }

    // CREATE AND DOWNLOAD BACKUP
    public function store()
    {
        $dbName   = config('database.connections.mysql.database');
        $dbUser   = config('database.connections.mysql.username');
        $dbPass   = config('database.connections.mysql.password');
        $dbHost   = config('database.connections.mysql.host');
        $dbPort   = config('database.connections.mysql.port');

        $dumpPath = env('MYSQLDUMP_PATH', 'mysqldump');
        $fileName = 'backup_' . now()->format('Y_m_d_His') . '.sql';
        $filePath = storage_path('app/' . $fileName);

        // BUILD MYSQLDUMP COMMAND
        $command = sprintf(
            '"%s" --user=%s --password=%s --host=%s --port=%s %s > "%s" 2>&1',
            $dumpPath,
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbName),
            $filePath
        );

        exec($command, $output, $exitCode);

        // COMMAND FAILED
        if ($exitCode !== 0 || ! file_exists($filePath)) {
            return back()->with('error', 'Backup failed. Please check your server configuration.');
        }

        // LOG THE ACTION
        AuditLog::create([
            'user_id'     => auth()->id(),
            'module'      => 'Backup',
            'action'      => 'BACKUP',
            'description' => 'Database backup created: ' . $fileName,
        ]);

        // DOWNLOAD THEN DELETE THE TEMP FILE
        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    // RESTORE FROM UPLOADED FILE
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file'],
        ]);

        $file = $request->file('backup_file');

        // ONLY ACCEPT .SQL FILES
        if ($file->getClientOriginalExtension() !== 'sql') {
            return back()->withErrors(['backup_file' => 'Only .sql files are accepted.']);
        }

        $dbName   = config('database.connections.mysql.database');
        $dbUser   = config('database.connections.mysql.username');
        $dbPass   = config('database.connections.mysql.password');
        $dbHost   = config('database.connections.mysql.host');
        $dbPort   = config('database.connections.mysql.port');

        $mysqlPath = env('MYSQL_PATH', 'mysql');
        $filePath  = $file->getRealPath();

        // BUILD MYSQL RESTORE COMMAND
        $command = sprintf(
            '"%s" --user=%s --password=%s --host=%s --port=%s %s < "%s" 2>&1',
            $mysqlPath,
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbName),
            $filePath
        );

        exec($command, $output, $exitCode);

        // COMMAND FAILED
        if ($exitCode !== 0) {
            return back()->with('error', 'Restore failed. Please make sure the file is a valid backup.');
        }

        // LOG THE ACTION
        AuditLog::create([
            'user_id'     => auth()->id(),
            'module'      => 'Backup',
            'action'      => 'RESTORE',
            'description' => 'Database restored from: ' . $file->getClientOriginalName(),
        ]);

        return back()->with('success', 'Database restored successfully.');
    }
}