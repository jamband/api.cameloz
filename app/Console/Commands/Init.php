<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Init extends Command
{
    /** @var string */
    protected $signature = 'dev:init';

    /** @var string */
    protected $description = 'Prepare the project for the development environment';

    private Filesystem $file;

    /**
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        parent::__construct();

        $this->file = $file;
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->file->copy('.env.example', '.env');
        $this->file->put(database_path('app.db'), '');

        $this->call('migrate', [
            '--force' => true,
            '--seed' => true,
        ]);

        $this->line("\nSet DB_DATABASE in the .env file.\n".
            'When completed, you can try performance check the with some commands:');

        $this->table(['Commands'], [
            ['php artisan route:list'],
            ['php artisan serve'],
            ['php artisan test'],
            ['php artisan up'],
            ['php artisan down'],
        ]);

        return self::SUCCESS;
    }
}
