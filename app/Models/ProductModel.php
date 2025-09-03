<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
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

    protected $appends = ['is_active'];

    public function getIsActiveAttribute(): bool
    {
        return $this->status === ProductStatusEnum::ACTIVE->value;
    }

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'price' => 'float',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        'deleted_at' => 'date:Y-m-d',
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
