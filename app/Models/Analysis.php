<?php

namespace App\Models;

class Analysis extends Model
{
    protected $fillable = [
        'upload_id',
        'user_id',
        'medicines',
        'test_summary',
        'parameters',
        'abnormal_findings',
        'advice',
        'next_steps',
        'lifestyle_tips',
        'report_type',
        'tests_advised',
        'raw_response',
        'language',
        'status',
        'error_message'
    ];

    protected function casts(): array
    {
        return [
            'medicines' => 'array',
            'parameters' => 'array',
            'abnormal_findings' => 'array',
            'tests_advised' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function upload(): BelongsTo
    {
        return $this->belongsTo(Upload::class);
    }
