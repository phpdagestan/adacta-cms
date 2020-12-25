<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\ProductCategory
 *
 * @property int $id
 * @property string $name Название
 * @property string $slug ЧПУ
 * @property int $created_by Пользователь создал
 * @property int $updated_by Пользователь обновил
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property bool $is_active Показывать на сайте
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $guarded = ['created_by', 'updated_by', 'created_at', 'updated_at'];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $currentUserId = Auth::id();
            $model->created_by = $currentUserId;
            $model->updated_by = $currentUserId;
        });
        self::updating(function($model){
            $currentUserId = Auth::id();
            $model->updated_by = $currentUserId;
        });
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
