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
            return back()->with('error', 'Backup failed: ' . implode(' ', $output));
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
            '"%s" --user=%s --password=%s --host=%s --port=%s %s',
            $mysqlPath,
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbName)
        );

        // USE PROC_OPEN TO PIPE THE FILE — MORE RELIABLE ON WINDOWS THAN < REDIRECT
        $descriptors = [
            0 => ['file', $filePath, 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (! is_resource($process)) {
            return back()->with('error', 'Restore failed. Could not start the database process.');
        }

        fclose($pipes[1]);
        $errorOutput = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        // COMMAND FAILED
        if ($exitCode !== 0) {
            return back()->with('error', 'Restore failed: ' . $errorOutput);
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