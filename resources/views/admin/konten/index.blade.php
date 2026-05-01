@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Kelola Etalase</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">Pengaturan tampilan visual produk dan dokumentasi acara untuk halaman depan pelanggan.</p>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold border border-green-100">{{ session('success') }}</div>
@endif

<!-- TAB SWITCHER -->
<div class="flex gap-4 border-b border-gray-200 mb-8">
    <button onclick="switchTab('tab-menu')" id="btn-tab-menu" class="px-6 py-3 text-sm font-bold border-b-2 border-primary text-primary transition-all">Katalog Menu</button>
    <button onclick="switchTab('tab-galeri')" id="btn-tab-galeri" class="px-6 py-3 text-sm font-bold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-all">Galeri Momen</button>
</div>

<!-- SECTION 1: KATALOG MENU -->
<div id="tab-menu" class="tab-content space-y-6">
    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between gap-4">
        <form action="{{ route('admin.konten') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
            <input type="hidden" name="tab" value="menu">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama menu..." class="bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm w-full md:w-64">
            <select name="cat" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm">
                <option value="">Semua Kategori</option>
                <option value="Nasi Box" {{ request('cat') == 'Nasi Box' ? 'selected' : '' }}>Nasi Box</option>
                <option value="Prasmanan" {{ request('cat') == 'Prasmanan' ? 'selected' : '' }}>Prasmanan</option>
                <option value="Tumpeng" {{ request('cat') == 'Tumpeng' ? 'selected' : '' }}>Tumpeng</option>
            </select>
            @if(request('q') || request('cat'))
                <a href="{{ route('admin.konten') }}" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-2 rounded-lg">Reset</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($menus as $menu)
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex gap-5 items-start">
            <div class="w-24 h-24 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 border border-gray-100">
                @if($menu->img_main)
                    <img src="{{ asset($menu->img_main) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300"><span class="material-symbols-outlined">image</span></div>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 leading-tight">{{ $menu->name }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $menu->category }}</p>
                
                <form action="{{ route('admin.konten.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase w-16">Utama</label>
                        <input type="file" name="img_main" class="text-[10px] flex-1">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase w-16">Detail</label>
                        <input type="file" name="img_detail" class="text-[10px] flex-1">
                    </div>
                    <button type="submit" class="w-full py-2 bg-gray-900 text-white text-[10px] font-bold rounded-lg hover:bg-black transition-all">Update Foto</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-gray-400">Menu tidak ditemukan.</div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $menus->links() }}
    </div>
</div>

<!-- SECTION 2: GALERI MOMEN -->
<div id="tab-galeri" class="tab-content hidden space-y-8">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="font-headline font-bold text-lg text-gray-900 mb-6">Unggah Momen Baru</h2>
        <form action="{{ route('admin.konten.gallery.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keterangan Foto</label>
                <input type="text" name="title" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                <select name="type" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                    <option value="makanan">Foto Produk</option>
                    <option value="event">Foto Acara</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Foto</label>
                <input type="file" name="image" required class="w-full text-xs">
            </div>
            <button type="submit" class="md:col-span-3 bg-primary text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20">Tambahkan ke Galeri</button>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($galleries as $gal)
        <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-100 shadow-sm">
            <img src="{{ asset($gal->image_url) }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all flex flex-col justify-center items-center p-4 text-center">
                <p class="text-white text-xs font-bold mb-3">{{ $gal->title }}</p>
                <form action="{{ route('admin.konten.gallery.destroy', $gal->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Hide all
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show target
        document.getElementById(tabId).classList.remove('hidden');
        
        // Reset buttons
        const btnMenu = document.getElementById('btn-tab-menu');
        const btnGaleri = document.getElementById('btn-tab-galeri');
        
        btnMenu.classList.remove('border-primary', 'text-primary');
        btnMenu.classList.add('border-transparent', 'text-gray-400');
        btnGaleri.classList.remove('border-primary', 'text-primary');
        btnGaleri.classList.add('border-transparent', 'text-gray-400');
        
        if(tabId === 'tab-menu') {
            btnMenu.classList.add('border-primary', 'text-primary');
            btnMenu.classList.remove('border-transparent', 'text-gray-400');
        } else {
            btnGaleri.classList.add('border-primary', 'text-primary');
            btnGaleri.classList.remove('border-transparent', 'text-gray-400');
        }
    }
    
    // Auto switch back to gallery if we just performed an action there
    @if(session('tab') == 'galeri')
        window.onload = () => switchTab('tab-galeri');
    @endif
</script>
@endsection