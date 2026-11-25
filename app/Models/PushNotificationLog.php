<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushNotificationLog extends Model
{
    protected $table = 'push_notification_logs';

    protected $fillable = [
        'title',
        'body',
        'image_url',
        'target_criteria',
        'total_targets',
        'success_count',
        'failure_count',
        'admin_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'target_criteria' => 'array',
            'total_targets' => 'integer',
            'success_count' => 'integer',
            'failure_count' => 'integer',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'admin_id');
    }
}
