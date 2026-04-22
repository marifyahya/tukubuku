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
        $featuredBooks = Book::where('stock', '>', 0)->latest()->take(6)->get();
        return view('home', compact('featuredBooks'));
    }
}
