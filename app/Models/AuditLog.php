<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'module', 'action', 'description'])]
class AuditLog extends Model
{
    // CASTS
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // RELATIONSHIP
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // SAVE A LOG ENTRY
    public static function record(string $module, string $action, string $description): void
    {
        self::create([
            'user_id' => auth()->id(),
            'module' => $module,
            'action' => $action,
            'description' => $description,
        ]);
    }
}