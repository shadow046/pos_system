<?php

namespace App\Observers;

use App\Actions\GenerateActivityLog;
use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        GenerateActivityLog::run('Created', $category, "Added new category named {$category->name}");
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        GenerateActivityLog::run('Updated', $category, "Edited {$category->name} category details");
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        GenerateActivityLog::run('Deleted', $category, "Deleted {$category->name} category");
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
