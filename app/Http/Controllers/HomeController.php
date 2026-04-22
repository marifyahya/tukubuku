<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display home page with featured books.
     */
    public function index()
    {
        $topBooks = Book::where('stock', '>', 0)->orderBy('rating', 'desc')->take(5)->get();
        $newBooks = Book::where('stock', '>', 0)->latest()->take(5)->get();
        $recommendedBooks = Book::where('stock', '>', 0)->inRandomOrder()->take(5)->get();
        
        return view('home', compact('topBooks', 'newBooks', 'recommendedBooks'));
    }
}
