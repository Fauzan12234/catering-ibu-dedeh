@extends('layouts.app')

@section('content')
<section class="py-24 bg-surface min-h-screen">
    <div class="max-w-6xl mx-auto px-4 md:px-8">
        <!-- Header -->
        <div class="mb-16 text-center">
            <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Katalog Kuliner</span>
            <h1 class="text-4xl md:text-5xl font-headline font-bold text-gray-900 tracking-tight leading-tight">Cita Rasa Autentik</h1>
        </div>

        <!-- Filter Tab (Urutan Baru & Nasi Box Default Aktif) -->
        <div class="flex flex-wrap justify-center gap-2 mb-16 bg-white p-2 rounded-[3rem] shadow-sm border border-gray-100 max-w-fit mx-auto" id="filter-container">
            <button onclick="filterMenu('nasiboxreguler')" class="filter-btn active-filter px-8 py-3 rounded-full text-[10px] font-bold tracking-[0.2em] uppercase transition-all shadow-sm" data-category="nasiboxreguler">Nasi Box</button>
            <button onclick="filterMenu('tumpeng')" class="filter-btn px-8 py-3 rounded-full text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 hover:text-primary transition-all" data-category="tumpeng">Tumpeng</button>
            <button onclick="filterMenu('tampah')" class="filter-btn px-8 py-3 rounded-full text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 hover:text-primary transition-all" data-category="tampah">Tampah</button>
            <button onclick="filterMenu('prasmanan')" class="filter-btn px-8 py-3 rounded-full text-[10px] font-bold tracking-[0.2em] uppercase text-gray-400 hover:text-primary transition-all" data-category="prasmanan">Prasmanan</button>
        </div>

        <!-- Grid Menu -->
        <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Isi menu akan dirender oleh JavaScript secara Client-Side (No Server Load!) -->
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="flex justify-center items-center gap-4 mt-20"></div>
    </div>
</section>

<!-- MODAL DETAIL (Diperkecil & Lebih Padat) -->
<div id="menuModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-md" onclick="closeModal()"></div>
    
    <div class="bg-white w-full max-w-4xl rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-2xl relative z-10 flex flex-col md:flex-row max-h-[90vh] overflow-y-auto animate-in fade-in zoom-in duration-300">
        
        <button onclick="closeModal()" class="absolute top-4 right-4 md:top-5 md:right-5 z-[110] w-10 h-10 md:w-12 md:h-12 bg-white/90 shadow-xl text-primary rounded-full flex items-center justify-center transition-all hover:rotate-90">
            <span class="material-symbols-outlined font-bold text-lg md:text-xl">close</span>
        </button>

        <div class="w-full md:w-[45%] bg-gray-100 h-[250px] md:h-auto relative overflow-hidden flex-shrink-0">
            <div id="modalSlider" class="flex h-full w-full transition-transform duration-500 ease-in-out"></div>
        </div>

        <div class="w-full md:w-[55%] p-6 md:p-10 flex flex-col bg-white">
            <div class="flex-1">
                <span id="modalCat" class="text-[10px] font-bold text-primary uppercase tracking-[0.4em] inline-block mb-2">Kategori</span>
                <h2 id="modalName" class="text-2xl md:text-3xl font-headline font-bold text-gray-900 leading-tight">Nama Menu</h2>
                
                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 mt-6">
                    <div class="flex items-center gap-2 mb-3 border-b border-gray-200 pb-3">
                        <span class="material-symbols-outlined text-primary text-sm">restaurant_menu</span>
                        <p class="text-xs font-bold text-gray-900 uppercase tracking-widest">Detail & Komposisi</p>
                    </div>
                    <div id="modalDesc" class="text-gray-600 text-sm leading-relaxed space-y-2"></div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="w-full sm:w-auto text-center sm:text-left">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Harga Satuan</p>
                    <p id="modalPrice" class="text-2xl font-bold text-gray-900 tracking-tighter">Rp 0</p>
                </div>
                <button id="modalAddBtn" class="w-full sm:w-auto silk-gradient text-white px-8 py-3.5 rounded-xl font-bold text-xs uppercase tracking-widest hover:shadow-xl transition-all flex items-center justify-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-lg">add_shopping_cart</span> Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<style>
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
    const allItems = {!! $formattedMenus->toJson() !!};
    
    // DEFAULT KATEGORI: NASI BOX
    let currentCategory = 'nasiboxreguler';
    let currentPage = 1;
    const itemsPerPage = 6;

    function renderMenu() {
        const grid = document.getElementById('menu-grid');
        
        // Filter langsung beraksi berdasarkan kategori aktif
        const items = allItems.filter(i => i.category.replace(/\s+/g, '').toLowerCase() === currentCategory);

        const totalPages = Math.ceil(items.length / itemsPerPage);
        const pagedItems = items.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

        if(items.length === 0) {
            grid.innerHTML = `<div class="col-span-full text-center py-10 text-gray-400 italic">Belum ada menu di kategori ini.</div>`;
            document.getElementById('pagination-container').innerHTML = '';
            return;
        }

        grid.innerHTML = pagedItems.map((item) => `
            <div class="group bg-white rounded-[1.5rem] overflow-hidden border border-gray-200 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col h-full">
                <div class="relative aspect-[4/3] overflow-hidden cursor-pointer" onclick="openModal(${allItems.indexOf(item)})">
                    <!-- OPTIMASI: Lazy Load Image -->
                    <img src="${item.imgMain}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-primary px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest z-10 pointer-events-none shadow-sm">
                        ${item.category_label}
                    </div>
                </div>
                
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-headline text-lg font-bold text-gray-900 mb-2 cursor-pointer hover:text-primary transition-colors line-clamp-1" onclick="openModal(${allItems.indexOf(item)})">${item.name}</h3>
                    <p class="text-gray-500 text-xs line-clamp-2 mb-4 flex-grow leading-relaxed">${item.desc || 'Sajian istimewa dari dapur Ibu Dedeh.'}</p>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 mt-auto">
                        <div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest block mb-1">Harga</span>
                            <span class="text-primary font-bold text-lg leading-none block tracking-tight">Rp ${item.price}</span>
                        </div>
                        <button onclick="window.addToCart('${item.name}', '${item.price}')" class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 shadow-sm active:scale-95" title="Tambah ke Keranjang">
                            <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        renderPagination(totalPages);
    }

    function filterMenu(cat) {
        currentCategory = cat; currentPage = 1;
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.toggle('active-filter', b.dataset.category === cat);
            b.classList.toggle('shadow-sm', b.dataset.category === cat);
            
            if(b.dataset.category !== cat) b.classList.add('text-gray-400');
            else b.classList.remove('text-gray-400');
        });
        renderMenu();
    }

    function openModal(idx) {
        const item = allItems[idx];
        document.getElementById('modalName').innerText = item.name;
        document.getElementById('modalCat').innerText = item.category_label;
        document.getElementById('modalDesc').innerHTML = (item.desc || 'Deskripsi belum tersedia.').replace(/\n/g, '<br>');
        document.getElementById('modalPrice').innerText = 'Rp ' + item.price;
        
        document.getElementById('modalAddBtn').onclick = () => {
            window.addToCart(item.name, item.price);
            closeModal();
        };

        const slider = document.getElementById('modalSlider');
        slider.innerHTML = `<img src="${item.imgMain}" loading="lazy" class="w-full h-full object-cover">`;
        
        document.getElementById('menuModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('menuModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function renderPagination(total) {
        const container = document.getElementById('pagination-container');
        let html = '';
        if (total > 1) {
            for (let i = 1; i <= total; i++) {
                html += `<button onclick="changePage(${i})" class="w-10 h-10 rounded-full border text-[11px] font-bold transition-all ${currentPage === i ? 'active-filter text-white border-primary shadow-md' : 'border-gray-200 text-gray-400 hover:border-primary hover:text-primary'}">${i}</button>`;
            }
        }
        container.innerHTML = html;
    }

    function changePage(p) { currentPage = p; renderMenu(); window.scrollTo({ top: document.getElementById('filter-container').offsetTop - 100, behavior: 'smooth' }); }
    
    // Langsung merender data Nasi Box saat pertama kali di load
    window.onload = renderMenu;
</script>
@endsection