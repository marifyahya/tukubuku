<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display all books with search.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $books = Book::where('stock', '>', 0)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->paginate(12);

        return view('user.books.index', compact('books', 'search'));
    }

    /**
     * Display book details.
     */
    public function show(Book $book)
    {
        return view('user.books.show', compact('book'));
    }
}