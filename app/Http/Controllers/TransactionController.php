<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {


        $customMessages = [
            'wallet_id.required' => 'Silakan pilih dompet.',
            'wallet_id.exists' => 'Dompet tidak valid.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'tipe.required' => 'Tipe transaksi wajib dipilih.',
            'jumlah.required' => 'Jumlah nominal wajib diisi.',
            'jumlah.numeric' => 'Jumlah nominal harus berupa angka.',
            'jumlah.min' => 'Jumlah nominal minimal 1.',
            'jumlah.regex' => 'Nominal terlalu besar! Maksimal 30 digit.',
        ];

        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'nullable|exists:categories,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => ['required', 'numeric', 'min:1', 'regex:/^\d{1,28}(\.\d{1,2})?$/'],
        ], $customMessages);

        // Verify wallet belongs to user
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $wallet = $user->wallets()->findOrFail($validated['wallet_id']);

        $user->transactions()->create($validated);

        return back()->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ensure user owns this transaction
        if ($transaction->user_id !== $user->id) {
            abort(403);
        }



        $customMessages = [
            'wallet_id.required' => 'Silakan pilih dompet.',
            'wallet_id.exists' => 'Dompet tidak valid.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'tipe.required' => 'Tipe transaksi wajib dipilih.',
            'jumlah.required' => 'Jumlah nominal wajib diisi.',
            'jumlah.numeric' => 'Jumlah nominal harus berupa angka.',
            'jumlah.min' => 'Jumlah nominal minimal 1.',
            'jumlah.regex' => 'Nominal terlalu besar! Maksimal 30 digit.',
        ];

        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'nullable|exists:categories,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => ['required', 'numeric', 'min:1', 'regex:/^\d{1,28}(\.\d{1,2})?$/'],
        ], $customMessages);

        // Verify wallet belongs to user
        $wallet = $user->wallets()->findOrFail($validated['wallet_id']);

        $transaction->update($validated);

        return back()->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();

        return back()->with('success', 'Transaksi berhasil dihapus!');
    }

    public function destroyAll(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->transactions()->delete();

        return back()->with('success', 'Semua transaksi berhasil dihapus!');
    }
}
