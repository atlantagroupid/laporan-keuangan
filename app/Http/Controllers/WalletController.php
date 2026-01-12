<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        // Check for duplicate wallet name for this user
        $exists = auth()->user()->wallets()->where('name', $validated['name'])->exists();

        if ($exists) {
            return back()->with('error', 'Nama dompet sudah ada!');
        }

        auth()->user()->wallets()->create($validated);

        return back()->with('success', 'Dompet berhasil ditambahkan!');
    }

    public function destroy(Wallet $wallet): RedirectResponse
    {
        if ($wallet->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if wallet has transactions
        if ($wallet->transactions()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus dompet yang memiliki transaksi!');
        }

        $wallet->delete();

        return back()->with('success', 'Dompet berhasil dihapus!');
    }

    public function update(Request $request, Wallet $wallet): RedirectResponse
    {
        if ($wallet->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        // Check for duplicate wallet name for this user (excluding current wallet)
        $exists = auth()->user()->wallets()
            ->where('name', $validated['name'])
            ->where('id', '!=', $wallet->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nama dompet sudah ada!');
        }

        $wallet->update($validated);

        return back()->with('success', 'Dompet berhasil diperbarui!');
    }
}
