<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtractionLog extends Model
{
    protected $table = 'extraction_logs';

    protected $fillable = [
        'file_name',
        'success',
        'error',
        'order_number',
        'ship_to',
        'item_count',
        'items',
        'raw_result',
    ];

    protected $casts = [
        'success'    => 'boolean',
        'item_count' => 'integer',
        'items'      => 'array',
        'raw_result' => 'array',
    ];
}
