@extends('layouts.app')

@section('content')
<section class="py-24 bg-surface min-h-screen">
    <div class="container mx-auto px-6 max-w-5xl">
        <!-- Back Link -->
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-3 text-gray-400 hover:text-primary mb-12 transition-all font-bold text-[10px] uppercase tracking-[0.3em]">
            <span class="material-symbols-outlined text-sm">west</span> Kembali ke Katalog
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            <div class="lg:col-span-7 sticky top-24">
                <div class="relative rounded-[2.5rem] overflow-hidden bg-white shadow-xl shadow-gray-100">
                    <div id="detailSlider" class="flex h-[500px] transition-transform duration-700 ease-in-out">
                        <img src="{{ asset($menu->img_main) }}" class="w-full h-full object-cover flex-shrink-0">
                        @if($menu->img_detail)
                            <img src="{{ asset($menu->img_detail) }}" class="w-full h-full object-cover flex-shrink-0">
                        @endif
                    </div>
                    
                    @if($menu->img_detail)
                    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3">
                        <button onclick="moveSlide(0)" class="slider-dot w-12 h-1 rounded-full bg-white/40 overflow-hidden relative">
                            <div class="dot-progress absolute inset-0 bg-white scale-x-100 origin-left"></div>
                        </button>
                        <button onclick="moveSlide(1)" class="slider-dot w-12 h-1 rounded-full bg-white/40"></button>
                    </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-5 pt-4">
                <p class="text-[10px] font-bold text-primary uppercase tracking-[0.4em] mb-4">{{ $menu->category }}</p>
                <h1 class="text-4xl font-headline font-bold text-gray-900 leading-tight mb-8">{{ $menu->name }}</h1>
                
                <div class="space-y-12">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Rincian Paket</p>
                        <div class="text-gray-500 leading-relaxed text-sm">
                            {!! nl2br(e($menu->description)) !!}
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-100">
                        <div class="flex justify-between items-end mb-8">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Harga Satuan</p>
                                <p class="text-3xl font-bold text-gray-900 tracking-tighter">Rp {{ number_format($menu->selling_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Tombol Hubungi Admin -->
                        <a href="https://wa.me/628978613607?text=Halo, saya ingin reservasi menu {{ $menu->name }}" target="_blank" 
                           class="w-full bg-primary text-white py-5 rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] hover:bg-red-900 transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-3">
                           <span class="material-symbols-outlined text-base">chat_bubble</span> Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function moveSlide(idx) {
        const slider = document.getElementById('detailSlider');
        const dots = document.querySelectorAll('.slider-dot');
        slider.style.transform = `translateX(-${idx * 100}%)`;
        dots.forEach((d, i) => {
            d.innerHTML = i === idx ? '<div class="dot-progress absolute inset-0 bg-white"></div>' : '';
            d.classList.toggle('bg-white/40', i !== idx);
        });
    }
</script>
@endsection