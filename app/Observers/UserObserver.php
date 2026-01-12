<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $defaults = [
            // Pengeluaran
            ['name' => 'Makanan & Minuman', 'icon' => 'fas fa-utensils', 'color' => '#ef4444', 'type' => 'pengeluaran'],
            ['name' => 'Transportasi', 'icon' => 'fas fa-car', 'color' => '#3b82f6', 'type' => 'pengeluaran'],
            ['name' => 'Belanja', 'icon' => 'fas fa-shopping-bag', 'color' => '#8b5cf6', 'type' => 'pengeluaran'],
            ['name' => 'Hiburan', 'icon' => 'fas fa-gamepad', 'color' => '#ec4899', 'type' => 'pengeluaran'],
            ['name' => 'Kesehatan', 'icon' => 'fas fa-heartbeat', 'color' => '#10b981', 'type' => 'pengeluaran'],
            ['name' => 'Tagihan & Utilitas', 'icon' => 'fas fa-file-invoice-dollar', 'color' => '#f59e0b', 'type' => 'pengeluaran'],
            ['name' => 'Pendidikan', 'icon' => 'fas fa-graduation-cap', 'color' => '#6366f1', 'type' => 'pengeluaran'],
            ['name' => 'Lainnya', 'icon' => 'fas fa-ellipsis-h', 'color' => '#64748b', 'type' => 'pengeluaran'],

            // Pemasukan
            ['name' => 'Gaji', 'icon' => 'fas fa-money-bill-wave', 'color' => '#10b981', 'type' => 'pemasukan'],
            ['name' => 'Bisnis', 'icon' => 'fas fa-briefcase', 'color' => '#3b82f6', 'type' => 'pemasukan'],
            ['name' => 'Investasi', 'icon' => 'fas fa-chart-line', 'color' => '#8b5cf6', 'type' => 'pemasukan'],
            ['name' => 'Hibah / Hadiah', 'icon' => 'fas fa-gift', 'color' => '#ec4899', 'type' => 'pemasukan'],
            ['name' => 'Lainnya', 'icon' => 'fas fa-plus-circle', 'color' => '#64748b', 'type' => 'pemasukan'],
        ];

        foreach ($defaults as $category) {
            $user->categories()->create($category);
        }
    }
}
