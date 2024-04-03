<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        Relation::enforceMorphMap([
            'user' => User::class,
            'profile' => Profile::class,
            'order' => Order::class,
            'product' => Product::class,
            'receipt' => Receipt::class,
            'role' => Role::class,
            'transaction' => Transaction::class,
            'category' => Category::class,
        ]);

        if (env('APP_ENV') == 'production')
        {
            $url->forceScheme('https');
        }
    }
}
