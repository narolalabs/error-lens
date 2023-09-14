<?php

namespace Narolalabs\ErrorLens\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ErrorLog extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'url', 'request_data', 'headers', 'message', 'error', 'trace', 'email', 'ip_address', 'browser', 'previous_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_data' => 'array',
        'headers' => 'array',
        'trace' => 'array',
        'error' => 'array',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'request_data' => '[]',
        'headers' => '[]',
        'trace' => '[]',
        'error' => '[]',
    ];

    /**
     * Bootstrap the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get records based on the created at
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string  $condition
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetFilters( Builder $query, $condition )
    {
        return $query->where('created_at', 'LIKE', "%$condition%");
    }
}
