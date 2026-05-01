@extends('layouts.app')
@section('title', 'Kontak Kami')

@section('content')
<!-- Header Minimalis -->
<header class="bg-surface pt-24 pb-16">
    <div class="max-w-6xl mx-auto px-4 md:px-8 text-center">
        <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Mari Berdiskusi</span>
        <h1 class="font-headline text-5xl md:text-7xl text-gray-900 tracking-tight mb-6 leading-tight font-bold">Hubungi Kami</h1>
        <p class="text-gray-500 text-lg md:text-xl font-body max-w-2xl mx-auto leading-relaxed">Punya rencana acara spesial? Beri tahu kami detailnya, dan tim kami akan merancang sajian terbaik untuk Anda.</p>
    </div>
</header>

<section class="max-w-6xl mx-auto px-4 md:px-8 pb-32 -mt-8">
    <!-- Grid items-stretch memastikan tinggi kolom kiri & kanan sama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <!-- KOLOM KIRI: Informasi Kontak & Peta -->
        <!-- flex & flex-col memastikan elemen di dalamnya mengisi ruang yang ada -->
        <div class="lg:col-span-5 flex flex-col gap-8 h-full">
            
            <!-- Kartu Info Kontak -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 relative overflow-hidden flex-shrink-0">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[100%] z-0"></div>
                <div class="relative z-10">
                    <h3 class="font-headline text-2xl text-gray-900 font-bold mb-8">Informasi Kontak</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-primary/10 text-primary rounded-full flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-lg">location_on</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 mb-1">Dapur Pusat</p>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Jalan Raya Serang Km 24.5<br>
                                    Kp. Talaga 69 RT 05/02<br>
                                    Talagasari, Balaraja<br>
                                    Kab. Tangerang
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary/10 text-primary rounded-full flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-lg">schedule</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 mb-1">Jam Operasional</p>
                                <p class="text-sm text-gray-500">Setiap Hari: 07.00 - 16.00 WIB</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary/10 text-primary rounded-full flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-lg">call</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 mb-1">WhatsApp / Telepon</p>
                                <p class="text-sm text-gray-500">0897-8613-607</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Peta (Mengisi sisa ruang tinggi / flex-grow) -->
            <div class="bg-white rounded-[2rem] p-4 shadow-sm border border-gray-100 flex flex-col group flex-grow">
                <!-- flex-grow & min-h-0 memastikan iframe mengambil tinggi maksimal yang tersedia agar sejajar -->
                <div class="rounded-2xl overflow-hidden flex-grow relative min-h-[200px]">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5110994868533!2d106.4572978086108!3d-6.196093460671774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e420197fc4bde61%3A0x2afad4f762095fa5!2sCATERING%20IBU%20DEDEH!5e0!3m2!1sid!2sid!4v1774795050658!5m2!1sid!2sid" width="100%" height="100%" style="border:0; position:absolute; top:0; left:0;" allowfullscreen="" loading="lazy" class="filter grayscale-[20%] group-hover:grayscale-0 transition-all duration-500"></iframe>
                </div>
                <a href="https://maps.app.goo.gl/bwyELtbNWPGLDbUm7" target="_blank" class="mt-4 bg-gray-50 text-gray-700 w-full py-3.5 rounded-xl font-bold text-sm flex justify-center items-center gap-2 hover:bg-gray-100 transition-colors shrink-0">
                    Buka di Google Maps <span class="material-symbols-outlined text-lg">open_in_new</span>
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN: Formulir -->
        <!-- h-full memastikan card kanan setinggi kolom induk -->
        <div class="lg:col-span-7 bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 p-8 md:p-12 border border-gray-50 flex flex-col h-full">
            <h2 class="text-2xl md:text-3xl font-headline font-bold text-gray-900 mb-8">Kirim Pesan Langsung</h2>
            
            <!-- flex-grow pada form agar elemen dalamnya bisa mengisi ruang -->
            <form id="wa-contact-form" onsubmit="sendContactToWhatsApp(event)" class="space-y-6 flex flex-col flex-grow">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" id="wa-name" class="w-full bg-gray-50 border-transparent rounded-xl p-4 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Jenis Acara</label>
                        <select id="wa-event" class="w-full bg-gray-50 border-transparent rounded-xl p-4 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-gray-600" required>
                            <option value="" disabled selected>Pilih acara...</option>
                            <option value="Pernikahan">Pernikahan</option>
                            <option value="Syukuran / Khitanan">Syukuran / Khitanan</option>
                            <option value="Rapat / Kantor">Rapat / Acara Kantor</option>
                            <option value="Ulang Tahun">Ulang Tahun</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Tanggal Acara (Opsional)</label>
                        <input type="date" id="wa-date" class="w-full bg-gray-50 border-transparent rounded-xl p-4 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-gray-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Porsi (Opsional)</label>
                        <input type="number" id="wa-qty" class="w-full bg-gray-50 border-transparent rounded-xl p-4 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Contoh: 100">
                    </div>
                </div>

                <!-- Textarea diberi flex-grow agar memanjang otomatis jika ada sisa ruang -->
                <div class="flex flex-col flex-grow">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Pesan Tambahan</label>
                    <textarea id="wa-message" class="w-full h-full min-h-[120px] bg-gray-50 border-transparent rounded-xl p-4 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none flex-grow" placeholder="Ceritakan detail kebutuhan atau pertanyaan Anda di sini..." required></textarea>
                </div>

                <!-- mt-auto memastikan tombol selalu ada di bagian paling bawah -->
                <div class="pt-4 mt-auto">
                    <button type="submit" class="w-full silk-gradient text-white py-5 rounded-xl font-bold uppercase tracking-widest text-xs shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        Kirim ke WhatsApp Kami
                    </button>
                    <p class="text-center text-[10px] text-gray-400 mt-4 italic">*Anda akan diarahkan ke aplikasi WhatsApp.</p>
                </div>
            </form>
        </div>
        
    </div>
</section>
@endsection

@section('scripts')
<script>
    function sendContactToWhatsApp(event) {
        event.preventDefault();

        const name = document.getElementById('wa-name').value;
        const eventType = document.getElementById('wa-event').value;
        const date = document.getElementById('wa-date').value;
        const qty = document.getElementById('wa-qty').value;
        const message = document.getElementById('wa-message').value;

        const waNumber = "628978613607"; 

        let formattedDate = "Belum ditentukan";
        if (date) {
            const dateObj = new Date(date);
            formattedDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        let waText = `*HALO CATERING IBU DEDEH*\n`;
        waText += `Saya ingin berkonsultasi mengenai layanan katering. Berikut detailnya:\n\n`;
        waText += `*Nama:* ${name}\n`;
        waText += `*Jenis Acara:* ${eventType}\n`;
        waText += `*Rencana Tanggal:* ${formattedDate}\n`;
        if (qty) waText += `*Perkiraan Porsi:* ${qty} Porsi\n`;
        waText += `\n*Pesan / Pertanyaan:*\n_${message}_\n\n`;
        waText += `Mohon info lebih lanjutnya. Terima kasih.`;

        const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(waText)}`;
        window.open(waUrl, '_blank');
    }
</script>
@endsection