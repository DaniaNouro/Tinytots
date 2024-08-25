<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserSearchRepository;
use App\Interfaces\UserSearchRepositoryInterface;
use App\Repositories\UserSortRepository;
use App\Interfaces\UserSortRepositoryInterface;
class AppServiceProvider extends ServiceProvider
{
    
    public function register()
    {
        $this->app->bind(
            UserSearchRepositoryInterface::class,
            UserSearchRepository::class
        );
        $this->app->bind(
            UserSortRepositoryInterface::class,
            UserSortRepository::class
        );
    }

   
    public function boot()
    {
     
    }
}
