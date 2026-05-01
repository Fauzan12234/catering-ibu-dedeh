@extends('layouts.admin')

@section('content')
<!-- SECTION HEADER -->
<div class="mb-10">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Laporan Laba Rugi</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
        Analisis performa keuangan bulanan. Pantau selisih antara total pendapatan dan biaya modal produksi secara akurat.
    </p>
</div>

<!-- FILTER PERIODE -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row items-end gap-4">
    <form action="{{ route('admin.reports.profit_loss') }}" method="GET" class="flex flex-wrap items-end gap-4 w-full">
        <div class="flex-1 min-w-[150px]">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Bulan</label>
            <select name="month" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-primary focus:border-primary transition-all">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun</label>
            <select name="year" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-primary focus:border-primary transition-all">
                @for($y=date('Y')-2; $y<=date('Y'); $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="bg-primary text-white px-8 py-3.5 rounded-xl text-sm font-bold hover:bg-red-900 transition-all shadow-lg shadow-primary/10">
            Terapkan Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
    <!-- KIRI: SUMMARY CARDS -->
    <div class="lg:col-span-5 flex flex-col gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between h-full">
            <div>
                <div class="flex justify-between items-start mb-6">
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">Ringkasan Pendapatan</p>
                    <span class="material-symbols-outlined text-gray-300">payments</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Total Omzet (Kotor)</p>
                        <p class="text-2xl font-headline font-bold text-gray-900">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="pt-4 border-t border-gray-50">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Total Modal (HPP)</p>
                        <p class="text-2xl font-headline font-bold text-primary">Rp {{ number_format($cogs, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 p-4 bg-gray-50 rounded-2xl flex justify-between items-center">
                @php $profit = $revenue - $cogs; @endphp
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Laba Bersih Periode Ini</p>
                    <p class="text-xl font-headline font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-primary' }}">
                        {{ $profit < 0 ? '-' : '' }} Rp {{ number_format(abs($profit), 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Margin</p>
                    <p class="text-sm font-bold text-gray-900">{{ $revenue > 0 ? round(($profit / $revenue) * 100) : 0 }}%</p>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-primary p-6 rounded-3xl text-white shadow-xl shadow-primary/10">
            <div class="flex items-center gap-3 mb-3">
                <span class="material-symbols-outlined text-primary-200">info</span>
                <p class="text-[10px] font-bold uppercase tracking-widest text-white/80">Kalkulasi Otomatis</p>
            </div>
            <p class="text-xs leading-relaxed text-white/70">
                Data laba rugi di atas disinkronisasi secara real-time dengan resep menu dan nota belanja pasar terbaru.
            </p>
        </div>
    </div>

    <!-- KANAN: CHART VISUALIZATION -->
    <div class="lg:col-span-7">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm h-full flex flex-col">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h3 class="text-[11px] font-bold text-gray-900 uppercase tracking-widest">Struktur Finansial</h3>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium italic">Perbandingan Omzet vs Modal</p>
                </div>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Beban HPP</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Net Profit</span>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 flex items-center justify-center relative min-h-[300px]">
                <!-- Chart Canvas -->
                <div class="w-full h-full max-w-[300px]">
                    <canvas id="profitChart"></canvas>
                </div>
                
                <!-- Info Overlay di Tengah Chart -->
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-4">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em]">Total Omzet</p>
                    <p class="text-lg font-headline font-bold text-gray-900">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('profitChart').getContext('2d');
    
    const revenue = {{ $revenue }};
    const cogs = {{ $cogs }};
    const profit = revenue - cogs;

    // Harmonized Colors: Menggunakan Primary (Dark Red) untuk HPP dan Hijau untuk Profit
    const hppColor = '#570000'; // Warna Primary kita
    const profitColor = profit >= 0 ? '#16a34a' : '#991b1b'; // Hijau jika untung, Merah Terang jika rugi
    const labelProfit = profit >= 0 ? 'Keuntungan Bersih' : 'Kerugian / Minus';

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Modal Produksi (HPP)', labelProfit],
            datasets: [{
                data: [cogs, Math.abs(profit)],
                backgroundColor: [hppColor, profitColor],
                hoverOffset: 12,
                borderWidth: 0,
                borderRadius: 10
            }]
        },
        options: {
            cutout: '82%', 
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    padding: 15,
                    titleFont: { size: 12, weight: 'bold', family: "'Manrope', sans-serif" },
                    bodyFont: { size: 14, family: "'Manrope', sans-serif" },
                    displayColors: true,
                    boxWidth: 8,
                    boxHeight: 8,
                    boxPadding: 6,
                    callbacks: {
                        label: function(context) {
                            let val = context.raw;
                            return ' ' + context.label + ': Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection