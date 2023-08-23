<?php

namespace App\Actions;

use App\Actions\Traits\AsObject;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class GenerateActivityLog
{
    use AsObject;

    protected $activity;

    public function handle(string $action, Model $model, string $description = null) : ActivityLog
    {
        return ActivityLog::create([
            'action' => $action,
            'typeable_id' => $model->id,
            'typeable_type' => $model->getMorphClass(),
            'description' => $description,
            'device' => json_encode(['ip_address' => GetIpAddress::run(), 'user_agent' => request()->header('User-Agent')]),
            'user_id' => auth()->check() ? auth()->user()->id : null
        ]);
    }
}
