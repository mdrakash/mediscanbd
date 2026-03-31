<?php

namespace App\Models;

class Upload extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'original_filename',
        'storage_path',
        'mime_type',
        'file_size',
        'language'
    ];

    protected function casts(): array
    {
        return [
            'type' => 'string',
            'language' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analysis(): HasOne
    {
        return $this->hasOne(Analysis::class);
    }

    protected function storageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->storage_path),
        );
    }
