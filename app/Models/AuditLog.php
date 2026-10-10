<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'action',
    'description',
    'subject_type',
    'subject_id',
])]
class AuditLog extends Model
{
    // NO UPDATE TIMESTAMPS — logs are created once, never edited
    public $timestamps = true;
    const UPDATED_AT = null;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // HELPER — create a log entry from anywhere in the app
    public static function record(string $action, string $description, ?string $subjectType = null, ?int $subjectId = null): void
    {
        self::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'description'  => $description,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
        ]);
    }
}