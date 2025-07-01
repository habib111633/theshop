<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'user_id', // seller ID should be fillable if set via forms
        'stock',   // if you manage stock via forms
    ];

    /**
     * Relationship: Product belongs to a User (seller)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'imageable');
    }

    /**
     * Accessor: Format price with currency
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Accessor: Get the current stock (alias for 'stock' column)
     */
    public function getCurrentStockAttribute()
    {
        return $this->stock;
    }

    /**
     * Accessor: Get the available stock for the current user (stock minus cart quantity in session)
     */
    public function getAvailableStockAttribute()
    {
        $cart = session('cart', []);
        $cartQty = isset($cart[$this->id]) ? $cart[$this->id]['quantity'] : 0;
        return max(0, $this->stock - $cartQty);
    }

}