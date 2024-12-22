<?php

namespace App\Providers;

use App\Repositories\FileRepository;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\GroupRepository;
use App\Repositories\GroupRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
          Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
     $this->app->bind(GroupRepositoryInterface::class,GroupRepository::class);
     $this->app->bind(FileRepositoryInterface::class,FileRepository::class);
     $this->app->bind(UserRepositoryInterface::class,UserRepository::class);


    }
}
