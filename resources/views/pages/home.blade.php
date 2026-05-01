@extends('layouts.app')
@section('title', 'Beranda')
@section('nav-class', 'fixed top-0 w-full z-50 bg-transparent transition-all duration-500 border-b border-transparent')
@section('nav-text', 'text-white')

@section('content')
<!-- HERO SECTION OPTIMIZED (Auto Slide + Ken Burns Effect) -->
<section class="relative h-screen w-full flex items-center justify-center overflow-hidden bg-gray-900">
    <div class="absolute inset-0 z-0">
        <!-- Gambar 1: Langsung Load (Prasmanan Elegan) -->
        <img src="https://images.unsplash.com/photo-1555244162-803834f70033?q=80&w=1920&auto=format&fit=crop" class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-100 animate-slow-zoom" alt="Catering Ibu Dedeh 1">
        
        <!-- Gambar 2: Lazy Load (Plating Mewah) -->
        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1920&auto=format&fit=crop" loading="lazy" class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0 animate-slow-zoom" alt="Catering Ibu Dedeh 2">
                
        <!-- Gradient Overlay (Z-index lebih tinggi dari gambar agar selalu di atas) -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/40 to-black/80 z-10"></div>
    </div>
    
    <div class="relative z-20 text-center max-w-4xl mx-auto px-4 md:px-8 mt-16">
        <h1 class="font-headline text-6xl md:text-8xl text-white mb-6 tracking-tight leading-tight drop-shadow-2xl">Catering Ibu Dedeh</h1>
        <p class="text-white/90 text-xl md:text-3xl font-light mb-12 tracking-wide italic drop-shadow-lg">Warisan Rasa Autentik untuk Momen Istimewa Anda</p>
        <div class="flex justify-center">
            <a href="{{ route('menu') }}" class="silk-gradient text-white px-12 py-5 rounded-full font-bold text-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">Lihat Menu</a>
        </div>
    </div>
    
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-white/70 z-20">
        <span class="material-symbols-outlined text-4xl">expand_more</span>
    </div>
</section>

<!-- CSS KHUSUS UNTUK ANIMASI HERO -->
<style>
    @keyframes slow-zoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.15); }
    }
    .animate-slow-zoom {
        animation: slow-zoom 20s ease-in-out infinite alternate;
    }
</style>

<!-- LAYANAN KAMI -->
<section class="py-24 bg-surface">
    <div class="max-w-6xl mx-auto px-4 md:px-8">
        <div class="text-center mb-20">
            <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Layanan Kami</span>
            <h2 class="font-headline text-4xl md:text-5xl text-on-surface">Pilihan Catering Eksklusif</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center mb-8 text-primary shadow-sm"><span class="material-symbols-outlined text-4xl">lunch_dining</span></div>
                <h3 class="font-headline text-2xl mb-4">Nasi Box</h3>
                <p class="text-on-surface-variant font-light leading-relaxed text-sm">Paket praktis dengan cita rasa istimewa untuk berbagai acara formal maupun santai.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center mb-8 text-primary shadow-sm"><span class="material-symbols-outlined text-4xl">restaurant</span></div>
                <h3 class="font-headline text-2xl mb-4">Prasmanan</h3>
                <p class="text-on-surface-variant font-light leading-relaxed text-sm">Sajian prasmanan megah dengan menu tradisional pilihan untuk pernikahan dan acara besar.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center mb-8 text-primary shadow-sm"><span class="material-symbols-outlined text-4xl">cake</span></div>
                <h3 class="font-headline text-2xl mb-4">Tumpeng</h3>
                <p class="text-on-surface-variant font-light leading-relaxed text-sm">Sajian tumpeng tradisional premium untuk melengkapi syukuran dan hari spesial Anda.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center mb-8 text-primary shadow-sm"><span class="material-symbols-outlined text-4xl">business_center</span></div>
                <h3 class="font-headline text-2xl mb-4">Catering Korporasi</h3>
                <p class="text-on-surface-variant font-light leading-relaxed text-sm">Layanan konsumsi profesional untuk rapat, seminar, dan acara perusahaan lainnya.</p>
            </div>
        </div>
    </div>
</section>

<!-- MENU UNGGULAN DINAMIS DENGAN CACHE -->
<section class="py-24 bg-white overflow-hidden border-y border-surface-container">
    <div class="max-w-6xl mx-auto px-4 md:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div>
                <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Menu Unggulan</span>
                <h2 class="font-headline text-4xl md:text-5xl text-on-surface">Cita Rasa Favorit</h2>
            </div>
            <a href="{{ route('menu') }}" class="text-primary font-bold border-b-2 border-primary/20 hover:border-primary transition-all pb-1">Lihat Semua Menu</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @php
                $featuredMenus = \Illuminate\Support\Facades\Cache::remember('home_featured_menus', 3600, function() {
                    return \App\Models\Menu::inRandomOrder()->take(3)->get();
                });
                
                $labels = ['Terlaris', 'Favorit', 'Premium'];
                $colors = ['bg-[#705d00]', 'bg-primary', 'bg-gray-900'];
            @endphp

            @foreach($featuredMenus as $index => $item)
            <div class="group bg-white rounded-[1.5rem] overflow-hidden border border-gray-200 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col h-full">
                <div class="relative aspect-[4/3] overflow-hidden cursor-pointer" onclick="window.location.href='{{ route('menu') }}'">
                    <img src="{{ asset($item->img_main) }}" loading="lazy" onerror="this.src='https://placehold.co/600x400'" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute top-4 right-4 {{ $colors[$index % 3] }} text-white px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest z-10 shadow-sm">
                        {{ $labels[$index % 3] }}
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="font-headline text-xl font-bold text-gray-900 mb-2 cursor-pointer hover:text-primary transition-colors line-clamp-1" onclick="window.location.href='{{ route('menu') }}'">{{ $item->name }}</h3>
                    <p class="text-gray-500 text-sm line-clamp-2 mb-6 flex-grow leading-relaxed">{{ $item->description ?? 'Sajian istimewa dari dapur Ibu Dedeh dengan bahan pilihan terbaik.' }}</p>
                    
                    <div class="mt-auto pt-5 border-t border-gray-100">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Mulai dari</span>
                            <span class="text-primary font-bold text-xl leading-none tracking-tight">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</span>
                        </div>
                        <button onclick="window.addToCart('{{ $item->name }}', {{ $item->selling_price }})" class="w-full silk-gradient text-white py-3.5 rounded-xl font-bold flex justify-center items-center gap-2 hover:shadow-lg hover:shadow-primary/30 transition-all shadow-sm active:scale-95 text-sm">
                            <span class="material-symbols-outlined text-lg">add_shopping_cart</span> Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- KISAH KAMI -->
<section class="py-24 bg-surface overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col lg:flex-row gap-16 items-center">
        <div class="w-full lg:w-1/2 relative">
            <div class="relative rounded-[2rem] overflow-hidden shadow-2xl">
                <img class="w-full h-[500px] md:h-[650px] object-cover" src="{{ asset('media/kisah-kami.png') }}" loading="lazy" onerror="this.src='https://placehold.co/800x1000?text=Ibu+Dedeh'">
            </div>
            <div class="absolute -bottom-6 -right-6 md:-bottom-8 md:-right-8 bg-primary text-white p-8 md:p-10 rounded-3xl shadow-2xl hidden md:block max-w-[280px]">
                <p class="font-headline text-xl md:text-2xl leading-relaxed italic">"Rasa adalah doa yang kami sajikan di setiap piring Anda."</p>
                <p class="text-tertiary-fixed text-xs md:text-sm mt-4 md:mt-6 font-bold tracking-widest uppercase">— Ibu Dedeh</p>
            </div>
        </div>
        <div class="w-full lg:w-1/2 space-y-8">
            <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs block">Kisah Kami</span>
            <h2 class="font-headline text-4xl md:text-5xl lg:text-6xl text-on-surface leading-tight">Tradisi Kuliner yang Dirawat dengan Hati</h2>
            <div class="space-y-6 text-on-surface-variant text-lg leading-relaxed font-light">
                <p>Berawal dari dapur mungil di sudut kota pada tahun 2018, Catering Ibu Dedeh telah tumbuh menjadi simbol kehangatan dan kelezatan hidangan Nusantara. Kami percaya bahwa setiap perayaan layak mendapatkan dedikasi rasa yang sempurna.</p>
                <p>Menggunakan bahan-bahan lokal terbaik dan rempah pilihan yang diolah secara tradisional, kami membawa cita rasa masakan rumah ke tingkat profesionalisme yang tinggi.</p>
            </div>
            <div class="grid grid-cols-2 gap-8 pt-8 border-t border-primary/10">
                <div><span class="block text-4xl font-headline text-primary mb-2">8+</span><span class="text-xs uppercase tracking-widest text-on-surface-variant font-bold">Tahun Pengalaman</span></div>
                <div><span class="block text-4xl font-headline text-primary mb-2">7rb+</span><span class="text-xs uppercase tracking-widest text-on-surface-variant font-bold">Acara Sukses</span></div>
            </div>
        </div>
    </div>
</section>

<!-- GALERI VISUAL DINAMIS DENGAN CACHE -->
<section class="py-24 bg-white border-t border-surface-container">
    <div class="max-w-6xl mx-auto px-4 md:px-8">
        <div class="text-center mb-16">
            <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Galeri Visual</span>
            <h2 class="font-headline text-4xl md:text-5xl text-on-surface">Momen yang Kami Abadikan</h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @php
                $homeGalleries = \Illuminate\Support\Facades\Cache::remember('home_galleries', 3600, function() {
                    return \App\Models\Gallery::latest()->take(4)->get();
                });
            @endphp

            @forelse($homeGalleries as $idx => $gallery)
                <div class="aspect-[4/5] overflow-hidden rounded-2xl group shadow-md {{ $idx % 2 != 0 ? 'translate-y-4 md:translate-y-8' : '' }}">
                    <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" loading="lazy" src="{{ asset($gallery->image_url) }}" onerror="this.src='https://placehold.co/400x500'">
                </div>
            @empty
                <div class="aspect-[4/5] overflow-hidden rounded-2xl group shadow-md"><img class="w-full h-full object-cover" loading="lazy" src="{{ asset('media/galeri/event-1.jpg') }}"></div>
                <div class="aspect-[4/5] overflow-hidden rounded-2xl group shadow-md translate-y-4 md:translate-y-8"><img class="w-full h-full object-cover" loading="lazy" src="{{ asset('media/galeri/makanan-1.jpg') }}"></div>
                <div class="aspect-[4/5] overflow-hidden rounded-2xl group shadow-md"><img class="w-full h-full object-cover" loading="lazy" src="{{ asset('media/galeri/makanan-2.jpg') }}"></div>
                <div class="aspect-[4/5] overflow-hidden rounded-2xl group shadow-md translate-y-4 md:translate-y-8"><img class="w-full h-full object-cover" loading="lazy" src="{{ asset('media/galeri/event-2.jpg') }}"></div>
            @endforelse
        </div>
        
        <div class="mt-20 md:mt-24 text-center">
            <a href="{{ route('galeri') }}" class="border-2 border-primary text-primary px-10 py-4 rounded-full font-bold hover:bg-primary hover:text-white transition-colors duration-300">Lihat Galeri Lengkap</a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // LOGIKA AUTO-SLIDE SUPER RINGAN (VANILLA JS)
    document.addEventListener('DOMContentLoaded', () => {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        
        if(slides.length > 1) {
            setInterval(() => {
                // Sembunyikan slide saat ini
                slides[currentSlide].classList.remove('opacity-100');
                slides[currentSlide].classList.add('opacity-0');
                
                // Lanjut ke slide berikutnya
                currentSlide = (currentSlide + 1) % slides.length;
                
                // Tampilkan slide berikutnya
                slides[currentSlide].classList.remove('opacity-0');
                slides[currentSlide].classList.add('opacity-100');
            }, 4500); // Berganti setiap 4.5 detik
        }
    });
</script>
@endsection