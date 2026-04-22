<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wishlists = Wishlist::with('book')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle item in wishlist.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $userId = Auth::id();
        $bookId = $request->book_id;

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Buku dihapus dari wishlist.');
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'book_id' => $bookId,
            ]);
            return back()->with('success', 'Buku ditambahkan ke wishlist.');
        }
    }
}
