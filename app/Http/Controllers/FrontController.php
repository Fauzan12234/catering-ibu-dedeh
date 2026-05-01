<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index() {
        return view('pages.home');
    }

public function menu() {
    // Urutan: Nasi Box Reguler paling depan, lalu Tumpeng
    $menus = \App\Models\Menu::orderByRaw("FIELD(category, 'Nasi Box', 'Tumpeng', 'Prasmanan') ASC")->get();

    $formattedMenus = $menus->map(function($m) {
        return [
            'id'        => $m->id,
            'name'      => $m->name,
            'category'  => strtolower(str_replace(' ', '', $m->category)),
            'category_label' => $m->category,
            'price'     => number_format($m->selling_price, 0, ',', '.'),
            'imgMain'   => asset($m->img_main),
        ];
    });

    return view('pages.menu', compact('formattedMenus'));
}

public function menuDetail($id) {
    $menu = \App\Models\Menu::findOrFail($id);
    return view('pages.menu-detail', compact('menu'));
}

    public function galeri() {
        $galleries = DB::table('galleries')->get();
        return view('pages.galeri', compact('galleries'));
    }

    public function kontak() {
        return view('pages.kontak');
    }
}