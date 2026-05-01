<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dapur Operasional - Catering Ibu Dedeh</title>
    <link rel="icon" type="image/png" href="{{ asset('media/logo.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { 
            theme: { 
                extend: { 
                    colors: { primary: "#570000", surface: "#f9fafb" },
                    fontFamily: { "headline": ["Noto Serif"], "body": ["Manrope"] }
                } 
            } 
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom Slim Scrollbar agar sidebar tetap cantik saat di-scroll */
        #sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: #f1f1f1; border-radius: 10px; }
        #sidebar-nav::-webkit-scrollbar-thumb:hover { background: #e5e5e5; }
        
        /* Mencegah teks tertekuk/gepeng dan menjaga proporsi */
        .nav-link span { white-space: nowrap; }
        
        /* Smooth transition untuk hover effects */
        .nav-link { transition: all 0.2s ease-in-out; }
    </style>
</head>
<body class="bg-surface font-body text-gray-800 antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR (Lebar 280px / w-72 untuk ruang visual yang lega) -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col hidden md:flex z-20 shadow-sm flex-shrink-0">
        
        <!-- Logo Section (Fixed) -->
        <div class="h-20 flex-shrink-0 flex items-center px-8 border-b border-gray-50">
            <img src="{{ asset('media/logo.png') }}" alt="Logo" class="h-7 w-auto mr-3">
            <span class="font-headline font-bold text-base text-primary tracking-tight uppercase">Operasional</span>
        </div>

        <!-- Scrollable Menu Section -->
        <nav id="sidebar-nav" class="flex-1 px-5 py-6 space-y-1.5 overflow-y-auto overflow-x-hidden">
            
            <!-- BERANDA -->
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">dashboard</span> 
                <span class="font-bold text-[13px] tracking-wide">Beranda Utama</span>
            </a>
            
            <!-- SECTION: ETALASE -->
            <div class="pt-8 pb-3 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.2em]">Etalase Visual</p>
            </div>
            
            <a href="{{ route('admin.konten') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.konten') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">collections_bookmark</span> 
                <span class="font-bold text-[13px] tracking-wide">Kelola Etalase</span>
            </a>

            <!-- SECTION: OPERASIONAL -->
            <div class="pt-8 pb-3 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.2em]">Manajemen Dapur</p>
            </div>
            
            <a href="{{ route('admin.orders.index') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">assignment</span> 
                <span class="font-bold text-[13px] tracking-wide">Daftar Orderan</span>
            </a>

            <a href="{{ route('admin.menus.index') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.menus.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">restaurant_menu</span> 
                <span class="font-bold text-[13px] tracking-wide">Daftar Menu & Resep</span>
            </a>

            <a href="{{ route('admin.purchases.shopping') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.purchases.shopping') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">shopping_cart</span> 
                <span class="font-bold text-[13px] tracking-wide">Daftar Belanja H-1</span>
            </a>

            <a href="{{ route('admin.purchases.create') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.purchases.create') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">payments</span> 
                <span class="font-bold text-[13px] tracking-wide">Input Nota Pasar</span>
            </a>

            <a href="{{ route('admin.materials.index') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.materials.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">kitchen</span> 
                <span class="font-bold text-[13px] tracking-wide">Gudang Bahan Baku</span>
            </a>

            <a href="{{ route('admin.suppliers.index') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.suppliers.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">local_shipping</span> 
                <span class="font-bold text-[13px] tracking-wide">Daftar Supplier</span>
            </a>

            <!-- SECTION: KEUANGAN -->
            <div class="pt-8 pb-3 px-4">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.2em]">Keuangan</p>
            </div>

            <a href="{{ route('admin.reports.profit_loss') }}" class="nav-link flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.reports.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 hover:text-primary' }} group">
                <span class="material-symbols-outlined text-[22px]">analytics</span> 
                <span class="font-bold text-[13px] tracking-wide">Laporan Laba Rugi</span>
            </a>
        </nav>

        <!-- User Section (Fixed) -->
        <div class="p-6 border-t border-gray-50 flex-shrink-0 bg-white">
            <div class="flex items-center gap-4 mb-5 px-1">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-headline font-bold text-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Administrator</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-lg">logout</span> 
                    <span>Akhiri Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Header Mobile -->
        <header class="h-16 bg-white border-b border-gray-100 flex-shrink-0 flex items-center justify-between px-6 md:hidden">
            <img src="{{ asset('media/logo.png') }}" alt="Logo" class="h-6">
            <button class="text-gray-500"><span class="material-symbols-outlined">menu</span></button>
        </header>

        <!-- Area Konten Utama -->
        <div class="flex-1 overflow-y-auto p-8 md:p-12">
            @yield('content')
        </div>
    </main>
    
    @yield('scripts')
</body>
</html>