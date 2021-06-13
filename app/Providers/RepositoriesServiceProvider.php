<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\EloquentRepositories\EloquentUserRepository;
use App\Repositories\EloquentRepositories\EloquentLoanRepository;
use App\Repositories\EloquentRepositories\EloquentEmiTransactionRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\LoanRepositoryInterface;
use App\Repositories\EmiTransactionRepositoryInterface;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            LoanRepositoryInterface::class,
            EloquentLoanRepository::class
        );

        $this->app->bind(
            EmiTransactionRepositoryInterface::class,
            EloquentEmiTransactionRepository::class
        );
    }
}
