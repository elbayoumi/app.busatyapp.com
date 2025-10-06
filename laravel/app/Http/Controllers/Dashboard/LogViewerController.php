<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class LogViewerController extends Controller
{
    /**
     * Regex to detect the beginning of a new Laravel log entry.
     * Typical line: [2025-09-24 10:22:33] local.ERROR: message ...
     */
    private const ENTRY_START_REGEX = '/^\[\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}(?:\.\d+)?\]/';

    /**
     * Show paginated log entries (each entry may contain multiple lines).
     */
    public function index()
    {
        $logFile = storage_path('logs/laravel.log');
        $page = (int) request()->get('page', 1);
        $perPage = 50; // entries per page (not lines)

        $entries = [];
        if (File::exists($logFile)) {
            $entries = $this->readLogEntries($logFile);

            // Newest first
            $entries = array_reverse($entries);

            $total = count($entries);
            $lastPage = max(1, (int) ceil($total / $perPage));
            $page = max(1, min($page, $lastPage));

            $offset = ($page - 1) * $perPage;
            $pageEntries = array_slice($entries, $offset, $perPage);
        } else {
            $total = 0;
            $lastPage = 1;
            $pageEntries = [];
        }

        return view('dashboard.logger.index', [
            'logs'        => $pageEntries, // array of full entry strings
            'currentPage' => $page,
            'lastPage'    => $lastPage,
        ]);
    }

    /**
     * Clear the laravel.log file.
     */
    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');

        if (File::exists($logFile)) {
            // Truncate the file safely
            File::put($logFile, '');
        }

        return redirect()
            ->route('dashboard.logger.index')
            ->with('success', 'Logs have been cleared.');
    }

    /**
     * Read the log file and split it into discrete entries.
     *
     * @param  string $path
     * @return array<int,string> Each array item is a full log entry (possibly multi-line)
     */
    private function readLogEntries(string $path): array
    {
        // Read file as lines (avoid loading a giant single string to reduce memory spikes)
        $lines = @file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return [];
        }

        $entries = [];
        $buffer = [];

        foreach ($lines as $line) {
            // New entry starts when line matches the timestamp pattern
            if (preg_match(self::ENTRY_START_REGEX, (string) $line) === 1) {
                // Flush previous buffered entry if exists
                if (!empty($buffer)) {
                    $entries[] = implode("\n", $buffer);
                    $buffer = [];
                }
            }
            $buffer[] = (string) $line;
        }

        // Flush last buffered entry
        if (!empty($buffer)) {
            $entries[] = implode("\n", $buffer);
        }

        return $entries;
    }
}
