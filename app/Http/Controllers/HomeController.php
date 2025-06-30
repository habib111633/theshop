<?php
namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Display the home page with random categories and their products
     */
    public function index()
    {
        // Get 2 random categories with their products and media
        $categories = Category::with(['products' => function ($query) {
            $query->with('media') // Include media relationship
                ->inRandomOrder()
                ->limit(4); // Get 4 random products per category
        }])->inRandomOrder()->limit(2)->get();

        return view('home', compact('categories'));
    }
}
