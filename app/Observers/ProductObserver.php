<?php

namespace App\Observers;

use App\Actions\GenerateActivityLog;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        GenerateActivityLog::run('Created', $product, "Added new product item named {$product->name}");
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        GenerateActivityLog::run('Updated', $product, "Edited {$product->name} product details");
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        GenerateActivityLog::run('Deleted', $product, "Deleted {$product->name} product");
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
