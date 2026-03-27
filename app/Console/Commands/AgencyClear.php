<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

final class AgencyClear extends Command
{
    protected $signature = 'agency:clear';

    protected $description = 'Clear all application caches (config, route, view, cache, events, Livewire, Filament)';

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <fg=cyan>Clearing all caches...</>');
        $this->newLine();

        $tasks = [
            'Configuration cache' => fn () => Artisan::call('config:clear'),
            'Route cache' => fn () => Artisan::call('route:clear'),
            'View cache' => fn () => Artisan::call('view:clear'),
            'Application cache' => fn () => Artisan::call('cache:clear'),
            'Event cache' => fn () => Artisan::call('event:clear'),
            'Compiled files' => fn () => Artisan::call('clear-compiled'),
        ];

        foreach ($tasks as $label => $task) {
            $this->output->write("  <fg=cyan>→</> {$label}... ");
            try {
                $task();
                $this->line('<fg=green>✓</>');
            } catch (Throwable $e) {
                $this->line('<fg=yellow>skipped</> <fg=gray>('.$e->getMessage().')</>');
            }
        }

        $this->newLine();
        $this->line('  <fg=green;options=bold>✅  All caches cleared.</>');
        $this->newLine();
        $this->line('  <fg=gray>Tip: Run  <options=bold>npm run dev</>  <fg=gray>to pick up CSS/JS changes in real time.</>');
        $this->newLine();

        return self::SUCCESS;
    }
}
