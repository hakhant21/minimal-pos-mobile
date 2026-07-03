<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DbFresh extends Command
{
    protected $signature = 'db:fresh
        {--seed : Seed the database after migration}
        {--force : Force the operation to run in production}';

    protected $description = 'Delete the SQLite database file, drop all tables, and re-run all migrations';

    public function handle(): int
    {
        $database = config('database.connections.sqlite.database');

        if (! str_contains($database, ':memory:')) {
            $this->components->task('Deleting SQLite database file', function () use ($database) {
                if (File::exists($database)) {
                    File::delete($database);
                }
            });
        }

        $params = ['--force' => $this->option('force') ?? true];

        if ($this->option('seed')) {
            $params['--seed'] = true;
        }

        return Artisan::call('migrate:fresh', $params, outputBuffer: $this->output);
    }
}
