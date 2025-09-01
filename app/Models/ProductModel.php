<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductModel extends Model
{
    use SoftDeletes;

    protected $table = 'product';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'status',
        'price',
        'amount',
    ];

    protected $dates = ['deleted_at'];

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'price' => 'float',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
