<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScraperLog extends Model
{
    protected $fillable = [
        'source',
        'success',
        'products_found',
        'products_scraped',
        'rows_saved',
        'rows_processed',
        'errors',
        'duration_seconds',
    ];

    protected $casts = [
        'success' => 'boolean',
        'errors'  => 'array',
    ];
}
