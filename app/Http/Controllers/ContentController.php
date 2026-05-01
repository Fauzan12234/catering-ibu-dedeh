<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Gallery;
use Illuminate\Support\Facades\File;

class ContentController extends Controller
{
    public function index(Request $request) {
        // Query untuk Menu di Etalase dengan Pagination & Filter
        $menuQuery = Menu::orderBy('name', 'asc');
        
        if ($request->has('cat') && $request->cat != '') {
            $menuQuery->where('category', $request->cat);
        }
        
        if ($request->has('q') && $request->q != '') {
            $menuQuery->where('name', 'like', '%' . $request->q . '%');
        }

        $menus = $menuQuery->paginate(10, ['*'], 'menu_page')->appends($request->query());
        
        // Ambil semua galeri
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        
        return view('admin.konten.index', compact('menus', 'galleries'));
    }

    public function updateMenuPhoto(Request $request, $id) {
        $request->validate([
            'img_main' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'img_detail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $menu = Menu::findOrFail($id);

        if ($request->hasFile('img_main')) {
            if ($menu->img_main && File::exists(public_path($menu->img_main))) {
                File::delete(public_path($menu->img_main));
            }
            $file = $request->file('img_main');
            $filename = time() . '_main_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/menus'), $filename);
            $menu->img_main = 'uploads/menus/' . $filename;
        }

        if ($request->hasFile('img_detail')) {
            if ($menu->img_detail && File::exists(public_path($menu->img_detail))) {
                File::delete(public_path($menu->img_detail));
            }
            $file = $request->file('img_detail');
            $filename = time() . '_detail_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/menus'), $filename);
            $menu->img_detail = 'uploads/menus/' . $filename;
        }

        $menu->save();
        return back()->with('success', 'Foto katalog berhasil diperbarui.');
    }

    public function storeGallery(Request $request) {
        $request->validate([
            'title' => 'required|string|max:150',
            'type' => 'required|in:makanan,event',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $file = $request->file('image');
        $filename = time() . '_gallery_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/galleries'), $filename);

        Gallery::create([
            'title' => $request->title,
            'type' => $request->type,
            'image_url' => 'uploads/galleries/' . $filename
        ]);

        return back()->with('success', 'Momen baru berhasil ditambahkan ke galeri.');
    }

    public function destroyGallery($id) {
        $gallery = Gallery::findOrFail($id);
        if (File::exists(public_path($gallery->image_url))) {
            File::delete(public_path($gallery->image_url));
        }
        $gallery->delete();
        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}