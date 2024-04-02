<?php

namespace App\Providers;

use App\Events\NewUserEvent;
use App\Events\VerifyDeviceEvent;
use App\Models\Category;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewUserNotification;
use App\Notifications\VerifyDeviceNotification;
use App\Observers\AuthenticationObserver;
use App\Observers\CategoryObserver;
use App\Observers\ProductObserver;
use App\Observers\ProfileObserver;
use App\Observers\ReceiptObserver;
use App\Observers\RoleObserver;
use App\Observers\TransactionObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewUserEvent::class => [
            NewUserNotification::class,
        ],
        VerifyDeviceEvent::class => [
            VerifyDeviceNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        Profile::observe(ProfileObserver::class);
        Receipt::observe(ReceiptObserver::class);
        Role::observe(RoleObserver::class);
        Transaction::observe(TransactionObserver::class);
        User::observe(UserObserver::class);

        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            (new AuthenticationObserver)->login($event->user);
        });

        Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            filled($event->user)
            ? (new AuthenticationObserver)->logout($event->user)
            : false;
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }
}
