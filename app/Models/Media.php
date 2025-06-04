<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'type',
        'path',
        'imageable_id',
        'imageable_type', // Critical for polymorphic relation
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
    public function getUrlAttribute($value)
    {
        return asset('storage/' . $value);
    }

}