@extends('layouts.app')
@section('title', 'Galeri')

@section('content')
<header class="bg-surface pt-24 pb-8">
    <div class="max-w-6xl mx-auto px-4 md:px-8 text-center">
        <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Kenangan dalam Bingkai</span>
        <h1 class="font-headline text-5xl md:text-7xl text-gray-900 tracking-tight mb-8 leading-tight font-bold">Galeri Kami</h1>
        <p class="text-gray-500 text-lg md:text-xl font-body max-w-3xl mx-auto leading-relaxed">Setiap momen istimewa terabadikan dalam sajian dan suasana yang penuh kehangatan.</p>
    </div>
</header>

<!-- Filter Tab (Mengikuti gaya menu.blade.php) -->
<section class="bg-surface pb-12">
    <div class="max-w-6xl mx-auto px-4 md:px-8">
        <div class="flex flex-wrap justify-center gap-2 bg-white p-2 rounded-[3rem] shadow-sm border border-gray-100 max-w-fit mx-auto" id="filter-container">
            <!-- Default aktif di Makanan -->
            <button onclick="filterGallery('makanan')" class="filter-btn active-filter px-8 py-3 rounded-full text-[10px] font-bold tracking-[0.2em] uppercase transition-all shadow-sm" data-category="makanan">Makanan</button>
            <button onclick="filterGallery('acara')" class="filter-btn px-8 py-3 rounded-full text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 hover:text-primary transition-all" data-category="acara">Acara</button>
        </div>
    </div>
</section>

<!-- Kontainer Galeri -->
<section id="gallery-container" class="bg-white py-20 min-h-[50vh]">
    <div class="max-w-6xl mx-auto px-4 md:px-8">
        <!-- Masonry Grid (Kolase Estetik) -->
        <div id="gallery-grid" class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
            <!-- Item dirender via JavaScript agar ringan -->
        </div>
        
        <!-- Pagination -->
        <div id="gallery-pagination" class="flex justify-center mt-16 gap-4 flex-wrap"></div>
    </div>
</section>

<!-- Quotes Section -->
<div class="bg-surface py-32 border-y border-gray-100">
    <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col items-center text-center">
        <div class="w-px h-24 bg-primary/30 mb-12"></div>
        <h2 class="font-headline text-3xl md:text-5xl text-gray-900 max-w-4xl italic leading-tight">"Setiap hidangan adalah kanvas, setiap acara adalah mahakarya."</h2>
    </div>
</div>

<!-- Call to Action -->
<section class="bg-white py-32">
    <div class="max-w-6xl mx-auto px-4 md:px-8 text-center">
        <h2 class="font-headline text-4xl md:text-5xl text-gray-900 mb-6 font-bold tracking-tight">Siap Merencanakan Acara Istimewa?</h2>
        <p class="text-gray-500 text-lg mb-12 max-w-2xl mx-auto">Hubungi tim kami untuk konsultasi gratis dan penawaran spesial.</p>
        <a href="{{ route('kontak') }}" class="silk-gradient text-white px-12 py-5 rounded-full font-bold uppercase tracking-widest text-xs hover:shadow-2xl transition-all duration-300 active:scale-95 shadow-xl flex items-center justify-center gap-3 w-fit mx-auto">
            <span class="material-symbols-outlined">calendar_month</span> Hubungi Sekarang
        </a>
    </div>
</section>

<style>
    /* Styling Filter menyesuaikan dengan halaman Menu */
    .active-filter {
        background: #570000 !important;
        color: white !important;
        box-shadow: 0 10px 20px -5px rgba(87, 0, 0, 0.3) !important;
    }
    .filter-btn:not(.active-filter):hover { background-color: #f9fafb; }
</style>
@endsection

@section('scripts')
<script>
    // Mengambil data bersih dari Database
    const allGalleries = @json($galleries->map(function($gallery) {
        return [
            'type' => strtolower(trim($gallery->type)), // memastikan lowercase: 'makanan' atau 'acara'
            'img' => asset($gallery->image_url),
            'title' => $gallery->title ?? 'Momen Spesial'
        ];
    }));

    let currentFilter = 'makanan'; // Kategori awal
    let currentPage = 1;
    const itemsPerPage = 9; // Tampilkan 9 foto per halaman agar pas di grid 3 kolom

    function renderGallery() {
        const grid = document.getElementById('gallery-grid');
        
        // Filter Data
        const items = allGalleries.filter(item => item.type === currentFilter);
        
        // Pagination
        const totalPages = Math.ceil(items.length / itemsPerPage);
        const pagedItems = items.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

        // Jika Data Kosong
        if (items.length === 0) {
            grid.innerHTML = `<div class="col-span-full w-full text-center py-20 text-gray-400 italic break-inside-avoid">Belum ada foto untuk kategori ini.</div>`;
            document.getElementById('gallery-pagination').innerHTML = '';
            return;
        }

        // Render Kolase / Masonry Item
        grid.innerHTML = pagedItems.map(item => `
            <div class="break-inside-avoid mb-6 relative group overflow-hidden rounded-[1.5rem] bg-gray-50 cursor-pointer border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500" onclick="openLightbox('${item.img}')">
                
                <img src="${item.img}" loading="lazy" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700" alt="${item.title}">
                
                <!-- Overlay Hitam & Teks saat di-hover -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                    <span class="text-white font-headline text-lg font-bold translate-y-4 group-hover:translate-y-0 transition-transform duration-500">${item.title}</span>
                    <span class="text-white/80 text-[10px] uppercase tracking-widest mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-700 delay-100 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">zoom_in</span> Lihat Detail
                    </span>
                </div>
            </div>
        `).join('');

        renderPagination(totalPages);
    }

    function filterGallery(type) {
        currentFilter = type;
        currentPage = 1;
        
        // Mengubah warna tombol filter
        document.querySelectorAll('.filter-btn').forEach(btn => {
            const isActive = btn.dataset.category === type;
            btn.classList.toggle('active-filter', isActive);
            btn.classList.toggle('shadow-sm', isActive);
            
            if(isActive) {
                btn.classList.remove('text-gray-400');
            } else {
                btn.classList.add('text-gray-400');
            }
        });
        
        renderGallery();
    }

    function renderPagination(total) {
        const container = document.getElementById('gallery-pagination');
        let html = '';
        if (total > 1) {
            for (let i = 1; i <= total; i++) {
                html += `<button onclick="changePage(${i})" class="w-10 h-10 rounded-full border text-[11px] font-bold transition-all ${currentPage === i ? 'active-filter text-white border-primary shadow-md' : 'border-gray-200 text-gray-400 hover:border-primary hover:text-primary'}">${i}</button>`;
            }
        }
        container.innerHTML = html;
    }

    function changePage(p) { 
        currentPage = p; 
        renderGallery(); 
        window.scrollTo({ top: document.getElementById('gallery-container').offsetTop - 100, behavior: 'smooth' }); 
    }

    // Fungsi untuk membuka Lightbox/Modal bawaan dari layouts/app.blade.php
    function openLightbox(src) {
        const modal = document.getElementById('image-modal');
        const img = document.getElementById('modal-image');
        
        if(modal && img) {
            img.src = src;
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }
    }

    // Menangani Event Penutupan Modal Gambar
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('image-modal');
        const closeBtn = document.getElementById('close-modal-img');
        
        if(modal) {
            // Tutup dari tombol X
            closeBtn?.addEventListener('click', () => {
                modal.classList.add('opacity-0', 'pointer-events-none');
            });
            
            // Tutup dari klik area latar hitam
            modal.addEventListener('click', (e) => {
                if (e.target === modal || e.target.classList.contains('backdrop-blur-sm')) {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }
    });

    // Jalankan render awal saat halaman terbuka
    window.onload = renderGallery;
</script>
@endsection