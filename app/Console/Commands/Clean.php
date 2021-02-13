<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Clean extends Command
{
    /** @var string */
    protected $signature = 'dev:clean';

    /** @var string */
    protected $description = 'Clean up development environment';

    /** @var string[] */
    private const FILES = [
        'database/app.db',
        'bootstrap/cache/*',
        'storage/logs/*',
        '.env',
    ];

    /**
     * @return int
     */
    public function handle(): int
    {
        $command = 'rm -rf '.implode(' ', self::FILES);
        Process::fromShellCommandline($command)->run();
        $this->line('Clean up completed.');

        return self::SUCCESS;
    }
}
