<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catering Ibu Dedeh - @yield('title', 'Beranda')</title>
    <link rel="icon" type="image/png" href="{{ asset('media/logo.ico') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface": "#f5f5f5", "primary": "#570000", "primary-container": "#800000",
                        "tertiary": "#705d00", "on-surface": "#1b1c1b", "on-surface-variant": "#5a413d",
                        "surface-container-low": "#f6f3f1", "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e0e0e0", "outline-variant": "#e2bfb9"
                    },
                    fontFamily: { "headline": ["Noto Serif"], "body": ["Manrope"], "label": ["Manrope"] },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .silk-gradient { background: linear-gradient(135deg, #570000 0%, #800000 100%); }
        /* Scrollbar styling untuk drawer keranjang */
        #cart-items::-webkit-scrollbar { width: 4px; }
        #cart-items::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
    
    <nav id="@yield('nav-id', 'main-nav')" class="@yield('nav-class', 'sticky top-0 w-full z-50 bg-white border-b border-stone-100 shadow-sm transition-all duration-300')">
        <div class="flex justify-between items-center w-full px-4 md:px-8 py-4 md:py-5 max-w-6xl mx-auto">
            <div class="flex items-center gap-3">
                <img src="{{ asset('media/logo.png') }}" alt="Logo Catering Ibu Dedeh" class="h-8 md:h-10 w-auto object-contain">
                <div class="nav-logo text-xl md:text-2xl font-serif font-bold transition-colors duration-300 @yield('nav-text', 'text-red-900')">
                    <a href="{{ route('home') }}">Catering Ibu Dedeh</a>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-8 font-serif tracking-tight text-lg uppercase">
                <a href="{{ route('home') }}" class="nav-link transition-all duration-300 @yield('nav-text', 'text-stone-600') hover:text-red-500 {{ request()->routeIs('home') ? 'active font-bold border-b-2 pb-1 border-current text-primary' : 'border-b-2 border-transparent pb-1' }}">Beranda</a>
                <a href="{{ route('menu') }}" class="nav-link transition-all duration-300 @yield('nav-text', 'text-stone-600') hover:text-red-500 {{ request()->routeIs('menu') ? 'active font-bold border-b-2 pb-1 border-current text-primary' : 'border-b-2 border-transparent pb-1' }}">Menu</a>
                <a href="{{ route('galeri') }}" class="nav-link transition-all duration-300 @yield('nav-text', 'text-stone-600') hover:text-red-500 {{ request()->routeIs('galeri') ? 'active font-bold border-b-2 pb-1 border-current text-primary' : 'border-b-2 border-transparent pb-1' }}">Galeri</a>
            </div>
            
            <div class="flex items-center gap-3 md:gap-4">
                <!-- Tombol Keranjang Navbar -->
                <button onclick="toggleCartDrawer()" class="relative p-2 scale-95 duration-200 ease-in-out hover:bg-white/20 rounded-full nav-icon-btn">
                    <span class="nav-icon material-symbols-outlined text-2xl transition-colors duration-300 @yield('nav-text', 'text-red-900')">shopping_cart</span>
                    <span id="global-cart-badge" class="absolute top-0 right-0 bg-tertiary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden shadow-sm border border-white">0</span>
                </button>

                @auth
                <a href="{{ route('admin.dashboard') }}" class="hidden lg:flex items-center gap-2 text-sm font-bold bg-white text-primary px-4 py-2 rounded-full hover:bg-gray-100 transition-colors border border-primary/20 shadow-sm">
                    <span class="material-symbols-outlined text-base">dashboard</span> Dashboard
                </a>
                @else
                <button type="button" onclick="openLoginModal()" class="hidden lg:flex items-center gap-2 text-sm font-bold bg-white text-primary px-4 py-2 rounded-full hover:bg-gray-100 transition-colors border border-primary/20 shadow-sm">
                    <span class="material-symbols-outlined text-base">shield_person</span> Login
                </button>
                @endauth

                <a href="{{ route('kontak') }}" class="btn-hubungi hidden md:inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-white text-primary border border-primary/20 shadow-sm text-sm font-bold transition-all hover:bg-gray-100 hover:shadow-md">
                    Hubungi Kami
                </a>

                <button id="mobile-menu-btn" class="md:hidden focus:outline-none nav-icon-btn p-2">
                    <span class="nav-icon material-symbols-outlined text-3xl transition-colors duration-300 @yield('nav-text', 'text-red-900')">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white/95 backdrop-blur-md w-full absolute left-0 top-full shadow-lg py-4 px-6 border-t border-stone-100">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="nav-link-mobile text-gray-700 font-serif text-base uppercase py-2">Beranda</a>
                <a href="{{ route('menu') }}" class="nav-link-mobile text-gray-700 font-serif text-base uppercase py-2">Menu</a>
                <a href="{{ route('galeri') }}" class="nav-link-mobile text-gray-700 font-serif text-base uppercase py-2">Galeri</a>
                
                @auth
                <a href="{{ route('admin.dashboard') }}" class="text-primary font-bold font-serif text-base uppercase py-2 border-t border-stone-200 mt-2 flex items-center gap-2"><span class="material-symbols-outlined text-base">dashboard</span> Dashboard Admin</a>
                @else
                <button type="button" onclick="openLoginModal()" class="text-primary font-bold font-serif text-base uppercase py-2 border-t border-stone-200 mt-2 flex items-center gap-2 text-left"><span class="material-symbols-outlined text-base">shield_person</span> Login Staff</button>
                @endauth
                <a href="{{ route('kontak') }}" class="bg-primary text-white text-center py-3 rounded-full font-bold mt-2 text-sm">Hubungi Kami</a>
            </div>
        </div>
    </nav>

    <!-- KONTEN DINAMIS -->
    <main>
        @yield('content')
    </main>

    <!-- DRAWER KERANJANG BELANJA (Mobile Friendly & Rapih) -->
    <div id="cart-drawer" class="fixed inset-0 z-[100] pointer-events-none overflow-hidden transition-all duration-500">
        <!-- Backdrop -->
        <div id="cart-overlay" onclick="toggleCartDrawer()" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-500 pointer-events-none"></div>
        
        <!-- Drawer Panel -->
        <div id="cart-panel" class="absolute right-0 top-0 h-full w-[90vw] sm:w-full sm:max-w-md bg-white shadow-2xl translate-x-full transition-transform duration-500 ease-out flex flex-col pointer-events-auto">
            
            <!-- Header Keranjang -->
            <div class="flex justify-between items-center p-5 sm:p-6 border-b border-gray-100 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/10 text-primary rounded-full flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-xl">shopping_basket</span>
                    </div>
                    <h2 class="font-headline text-xl sm:text-2xl font-bold text-gray-900">Keranjang</h2>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Tombol Clear All -->
                    <button onclick="clearCart()" id="clear-cart-btn" class="hidden text-[10px] font-bold uppercase tracking-widest text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-full transition-colors">Kosongkan</button>
                    <button onclick="toggleCartDrawer()" class="w-8 h-8 hover:bg-gray-100 rounded-full transition-colors text-gray-400 hover:text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
            </div>
            
            <!-- Isi Keranjang -->
            <div id="cart-items" class="flex-grow overflow-y-auto p-5 sm:p-6 space-y-4 bg-gray-50/50">
                <!-- Item dirender via JavaScript -->
            </div>
            
            <!-- Footer & Checkout WA -->
            <div class="p-5 sm:p-6 border-t border-gray-100 bg-white shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)]">
                <div class="flex justify-between items-end mb-5">
                    <span class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Total Estimasi</span>
                    <span id="cart-total" class="text-2xl font-bold text-primary tracking-tighter">Rp 0</span>
                </div>
                <!-- Tombol Checkout Bersih (Tanpa Icon WhatsApp yang error) -->
                <button onclick="checkoutToWhatsApp()" class="w-full silk-gradient text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center">
                    Pesan via WhatsApp
                </button>
                <p class="text-[9px] text-center text-gray-400 mt-3 italic">*Pemesanan akan dilanjutkan dengan Admin kami.</p>
            </div>
        </div>
    </div>

    <!-- MODAL NOTIFIKASI BERHASIL TAMBAH -->
    <div id="success-modal" class="fixed inset-0 z-[110] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeSuccessModal()"></div>
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-xs w-[90vw] mx-auto transform scale-95 transition-all duration-300 relative z-10 text-center" id="success-modal-content">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4 relative">
                <svg class="animate-spin absolute inset-0 w-full h-full text-green-500/30" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="material-symbols-outlined text-green-600 text-4xl relative z-10">check_circle</span>
            </div>
            <h3 class="font-headline text-xl font-bold text-gray-900 mb-2">Berhasil!</h3>
            <p id="modal-message" class="text-gray-500 text-sm mb-4 line-clamp-2">Item ditambahkan ke keranjang</p>
            <p class="text-[10px] text-gray-400 mb-6 font-bold uppercase tracking-widest">Menutup dalam <span id="success-countdown" class="text-primary text-sm">3</span> detik</p>
            <button onclick="toggleCartDrawer(); closeSuccessModal();" class="w-full py-3 bg-primary text-white rounded-xl font-bold hover:bg-red-900 transition-colors text-sm shadow-md">Lihat Keranjang</button>
        </div>
    </div>

    <!-- MODAL LOGIN OPERASIONAL -->
    <div id="login-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeLoginModal()"></div>
        <div class="relative bg-white p-8 md:p-10 rounded-2xl shadow-2xl w-full max-w-md mx-4 transform scale-95 transition-all duration-300" id="login-modal-content">
            <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined">close</span></button>
            <div class="text-center mb-8">
                <img src="{{ asset('media/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-4 object-contain">
                <h2 class="text-2xl font-bold text-gray-900 font-headline">Sistem Operasional</h2>
                <p class="text-sm text-gray-500 mt-1">Masuk ke dashboard manajemen</p>
            </div>

            @if(session('login_error'))
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm text-center mb-6 border border-red-100">
                    {{ session('login_error') }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                    <input type="text" name="username" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary p-3 transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary p-3 transition-all" required>
                </div>
                <button type="submit" class="w-full silk-gradient text-white font-bold py-3 rounded-lg hover:shadow-lg hover:shadow-primary/30 transition-all mt-4">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <footer class="bg-red-950 text-white pt-24 pb-12">
        <div class="max-w-6xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
                <div class="space-y-6">
                    <div class="font-serif italic text-3xl text-white">Catering Ibu Dedeh</div>
                    <p class="font-sans text-white text-sm leading-loose tracking-wide opacity-80">Menyajikan kebahagiaan melalui setiap hidangan sejak 2018. Dedikasi kami adalah pada kualitas, rasa, dan pelayanan katering premium di Indonesia.</p>
                </div>
                <div>
                    <h4 class="font-serif text-2xl mb-8 text-white">Navigasi</h4>
                    <ul class="space-y-4 font-sans text-sm tracking-wide">
                        <li><a href="{{ route('home') }}" class="text-white hover:opacity-100 opacity-80 transition-opacity">Beranda</a></li>
                        <li><a href="{{ route('menu') }}" class="text-white hover:opacity-100 opacity-80 transition-opacity">Menu</a></li>
                        <li><a href="{{ route('galeri') }}" class="text-white hover:opacity-100 opacity-80 transition-opacity">Galeri</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-serif text-2xl mb-8 text-white">Media Sosial</h4>
                    <ul class="space-y-4 font-sans text-sm tracking-wide">
                        <li><a href="https://www.instagram.com/cateringibudedeh" target="_blank" class="text-white hover:opacity-100 opacity-80 transition-opacity flex items-center gap-3"><span class="material-symbols-outlined text-base">photo_camera</span> Instagram</a></li>
                        <li><a href="https://www.tiktok.com/@cateringibudedeh" target="_blank" class="text-white hover:opacity-100 opacity-80 transition-opacity flex items-center gap-3"><span class="material-symbols-outlined text-base">video_library</span> TikTok</a></li>
                        <li><a href="https://wa.me/628978613607" target="_blank" class="text-white hover:opacity-100 opacity-80 transition-opacity flex items-center gap-3"><span class="material-symbols-outlined text-base">chat</span> WhatsApp</a></li>
                    </ul>
                </div>
                <div class="space-y-8">
                    <h4 class="font-serif text-2xl mb-2 text-white">Pesan Sekarang</h4>
                    <p class="text-white text-sm font-sans tracking-wide mb-6 opacity-80">Konsultasikan acara Anda secara gratis bersama tim kami.</p>
                    <a href="https://wa.me/628978613607" target="_blank" class="inline-flex items-center gap-3 bg-white text-red-950 px-8 py-4 rounded-xl font-bold text-base hover:bg-stone-100 transition-all shadow-lg hover:-translate-y-1"><span class="material-symbols-outlined">chat</span> Hubungi via WhatsApp</a>
                </div>
            </div>
            <div class="pt-8 border-t border-white/10 text-center">
                <p class="text-white text-sm tracking-widest font-sans opacity-60">© 2026 CATERING IBU DEDEH. TRADISI DALAM SETIAP RASA.</p>
            </div>
        </div>
    </footer>

    <!-- GLOBAL JAVASCRIPT LOGIC -->
    <script>
        // Modal Login Logic
        function openLoginModal() {
            const modal = document.getElementById('login-modal');
            const content = document.getElementById('login-modal-content');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95'); content.classList.add('scale-100');
        }
        function closeLoginModal() {
            const modal = document.getElementById('login-modal');
            const content = document.getElementById('login-modal-content');
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100'); content.classList.add('scale-95');
        }
        @if(session('login_error'))
            document.addEventListener('DOMContentLoaded', function() { openLoginModal(); });
        @endif

        // --- SISTEM KERANJANG GLOBAL ---
        let globalCart = JSON.parse(localStorage.getItem('catering_cart')) || [];

        function updateCartBadge() {
            const badge = document.getElementById('global-cart-badge');
            const clearBtn = document.getElementById('clear-cart-btn');
            const totalQty = globalCart.reduce((sum, item) => sum + item.qty, 0);
            
            if (totalQty > 0) {
                badge.innerText = totalQty;
                badge.classList.remove('hidden');
                if(clearBtn) clearBtn.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
                if(clearBtn) clearBtn.classList.add('hidden');
            }
            localStorage.setItem('catering_cart', JSON.stringify(globalCart));
        }

        window.addToCart = function(name, priceStr) {
            const price = typeof priceStr === 'string' ? parseInt(priceStr.replace(/[^0-9]/g, '')) : priceStr;
            const existingItem = globalCart.find(item => item.name === name);
            
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                globalCart.push({ name, price, qty: 1 });
            }
            
            updateCartBadge();
            renderCartDrawer();
            showSuccessModal(name);
        };

        window.clearCart = function() {
            if (confirm('Anda yakin ingin mengosongkan keranjang belanja?')) {
                globalCart = [];
                updateCartBadge();
                renderCartDrawer();
            }
        };

        function toggleCartDrawer() {
            const drawer = document.getElementById('cart-drawer');
            const overlay = document.getElementById('cart-overlay');
            const panel = document.getElementById('cart-panel');
            
            if (drawer.classList.contains('pointer-events-none')) {
                drawer.classList.remove('pointer-events-none');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                panel.classList.remove('translate-x-full');
                renderCartDrawer();
            } else {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                panel.classList.add('translate-x-full');
                setTimeout(() => drawer.classList.add('pointer-events-none'), 500);
            }
        }

        function renderCartDrawer() {
            const list = document.getElementById('cart-items');
            const totalLabel = document.getElementById('cart-total');
            let total = 0;

            if (globalCart.length === 0) {
                list.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 opacity-50 py-16">
                        <span class="material-symbols-outlined text-6xl mb-4">shopping_cart_off</span>
                        <p class="font-bold">Keranjang Anda Kosong</p>
                    </div>`;
                totalLabel.innerText = 'Rp 0';
                return;
            }

            list.innerHTML = globalCart.map((item, idx) => {
                total += (item.price * item.qty);
                return `
                    <div class="flex justify-between items-center bg-white border border-gray-100 p-4 rounded-2xl shadow-sm">
                        <div class="flex-1 pr-3">
                            <h4 class="font-bold text-gray-900 text-sm leading-snug mb-1 line-clamp-1">${item.name}</h4>
                            <p class="text-[11px] text-primary font-bold">Rp ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg p-1 shrink-0">
                            <button onclick="updateCartQty(${idx}, -1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-white hover:text-red-500 hover:shadow-sm rounded transition-all text-lg font-bold">-</button>
                            <span class="text-xs font-bold w-4 text-center">${item.qty}</span>
                            <button onclick="updateCartQty(${idx}, 1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-white hover:text-green-500 hover:shadow-sm rounded transition-all text-lg font-bold">+</button>
                        </div>
                    </div>
                `;
            }).join('');
            
            totalLabel.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        window.updateCartQty = function(idx, delta) {
            globalCart[idx].qty += delta;
            if (globalCart[idx].qty <= 0) globalCart.splice(idx, 1);
            updateCartBadge();
            renderCartDrawer();
        };

        window.checkoutToWhatsApp = function() {
            if (globalCart.length === 0) return alert("Keranjang belanja masih kosong!");
            
            const waNumber = "628978613607";
            let message = `*HALO CATERING IBU DEDEH*\nSaya ingin bertanya / memesan menu katering berikut:\n\n`;
            let total = 0;
            
            globalCart.forEach((item, idx) => {
                const subtotal = item.price * item.qty;
                total += subtotal;
                message += `*${idx + 1}. ${item.name}*\n   ${item.qty} Porsi x Rp ${item.price.toLocaleString('id-ID')} = Rp ${subtotal.toLocaleString('id-ID')}\n`;
            });
            
            message += `\n========================\n*TOTAL ESTIMASI: Rp ${total.toLocaleString('id-ID')}*\n========================\n\nMohon informasi lebih lanjut mengenai pemesanan ini. Terima kasih!`;
            
            window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`, '_blank');
        };

        // Modal Success Logic with 3 Seconds Countdown
        let successInterval;
        let successTimeout;

        function showSuccessModal(itemName) {
            const modal = document.getElementById('success-modal');
            const content = document.getElementById('success-modal-content');
            const countdownSpan = document.getElementById('success-countdown');
            
            document.getElementById('modal-message').innerText = `${itemName}`;
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95'); content.classList.add('scale-100');

            clearInterval(successInterval);
            clearTimeout(successTimeout);
            
            let secondsLeft = 3;
            countdownSpan.innerText = secondsLeft;

            successInterval = setInterval(() => {
                secondsLeft--;
                if (secondsLeft > 0) {
                    countdownSpan.innerText = secondsLeft;
                } else {
                    clearInterval(successInterval);
                }
            }, 1000);

            // Auto close after 3 seconds
            successTimeout = setTimeout(() => {
                closeSuccessModal();
            }, 3000);
        }

        function closeSuccessModal() {
            const modal = document.getElementById('success-modal');
            const content = document.getElementById('success-modal-content');
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100'); content.classList.add('scale-95');
            
            clearInterval(successInterval);
            clearTimeout(successTimeout);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateCartBadge();
            document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });
        });
    </script>
    <script src="{{ asset('script.js') }}"></script>
    @yield('scripts')
</body>
</html>