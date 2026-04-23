<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;

class AddressController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('user.addresses.index', compact('addresses'));
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
            'landmark' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $isPrimary = $request->has('is_primary');

        if ($isPrimary) {
            Auth::user()->addresses()->update(['is_primary' => false]);
        }

        // If it's the first address, make it primary automatically
        if (Auth::user()->addresses()->count() === 0) {
            $isPrimary = true;
        }

        $validated['is_primary'] = $isPrimary;

        Auth::user()->addresses()->create($validated);

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
            'landmark' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $isPrimary = $request->has('is_primary');

        if ($isPrimary && !$address->is_primary) {
            Auth::user()->addresses()->update(['is_primary' => false]);
        }

        // Prevent unsetting the only primary address without setting a new one
        if (!$isPrimary && $address->is_primary && Auth::user()->addresses()->count() > 1) {
            // It's allowed but we should warn, or just accept it
        }

        $validated['is_primary'] = $isPrimary;

        $address->update($validated);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $wasPrimary = $address->is_primary;
        $address->delete();

        // If primary was deleted, set the latest one as primary if exists
        if ($wasPrimary) {
            $newPrimary = Auth::user()->addresses()->latest()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Set the specified address as primary.
     */
    public function setPrimary(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        Auth::user()->addresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah.');
    }
}
