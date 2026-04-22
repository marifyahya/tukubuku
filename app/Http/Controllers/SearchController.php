<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class SearchController extends Controller
{
    /**
     * Get search suggestions for books.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $books = Book::where('title', 'LIKE', "%{$query}%")
            ->orWhere('author', 'LIKE', "%{$query}%")
            ->select('id', 'title', 'author', 'cover_image', 'price')
            ->limit(10)
            ->get();

        // Format the response
        $results = $books->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'cover_url' => $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/no-cover.jpg'),
                'price' => $book->price,
            ];
        });

        return response()->json($results);
    }
}