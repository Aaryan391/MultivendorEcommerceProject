<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $categories = Category::all();
            $subcategories = Subcategory::all();
            
            // Pass both variables as an associative array
            $view->with([
                'categories' => $categories,
                'subcategories' => $subcategories
            ]);
        });
        
    }
}
