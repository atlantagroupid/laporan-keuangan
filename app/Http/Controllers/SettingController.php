<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function updateTitle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_title' => 'required|string|max:100',
        ]);

        $user = auth()->user();

        $setting = $user->setting ?? $user->setting()->create([]);
        $setting->update(['app_title' => $validated['app_title']]);

        return back()->with('success', 'Judul berhasil disimpan!');
    }

    public function updateLogo(Request $request): RedirectResponse
    {
        $request->validate([
            'app_logo' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        $user = auth()->user();

        // Check if user is super admin
        if (!$user->isSuperAdmin()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah logo!');
        }

        $setting = $user->setting ?? $user->setting()->create([]);

        // Delete old logo if exists
        if ($setting->app_logo && file_exists(public_path($setting->app_logo))) {
            unlink(public_path($setting->app_logo));
        }

        // Create logos directory if not exists
        $logoPath = public_path('logos');
        if (!file_exists($logoPath)) {
            mkdir($logoPath, 0755, true);
        }

        // Process and convert to WebP
        $file = $request->file('app_logo');
        $filename = 'logo_' . time() . '.webp';

        // Get image info and convert to WebP
        $image = $this->createImageFromFile($file->getPathname(), $file->getMimeType());

        if ($image) {
            // Save as WebP with quality 85
            imagewebp($image, $logoPath . '/' . $filename, 85);
            imagedestroy($image);

            // Update setting
            $setting->update(['app_logo' => 'logos/' . $filename]);

            return back()->with('success', 'Logo berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal memproses gambar!');
    }

    public function deleteLogo(): RedirectResponse
    {
        $user = auth()->user();

        // Check if user is super admin
        if (!$user->isSuperAdmin()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus logo!');
        }

        $setting = $user->setting;

        if ($setting && $setting->app_logo) {
            // Delete logo file
            if (file_exists(public_path($setting->app_logo))) {
                unlink(public_path($setting->app_logo));
            }

            $setting->update(['app_logo' => null]);

            return back()->with('success', 'Logo berhasil dihapus!');
        }

        return back()->with('error', 'Tidak ada logo untuk dihapus!');
    }

    /**
     * Create GD image resource from uploaded file
     * Using @ to suppress libpng warnings about sRGB profiles
     */
    private function createImageFromFile(string $path, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return @imagecreatefromjpeg($path);
            case 'image/png':
                $image = @imagecreatefrompng($path);
                if ($image) {
                    // Handle transparency for PNG
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                return $image;
            case 'image/gif':
                return @imagecreatefromgif($path);
            case 'image/webp':
                return @imagecreatefromwebp($path);
            default:
                return null;
        }
    }
}
