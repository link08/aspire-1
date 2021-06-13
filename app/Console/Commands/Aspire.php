<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Aspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aspire:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Aspire application in local';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Settup up a fresh Aspire Loan Application');

        // Setup a fresh migration
        $this->call('migrate:refresh');

        // Setup and generate client secret for Passport
        $this->call('passport:install');

        // Run DB seeds
        $this->call('db:seed');

        $this->info('Setup completed.');

        // Start laravel server
        $this->call('serve');
    }
}
