@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Beranda Operasional</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
        Selamat datang kembali, {{ explode(' ', Auth::user()->name)[0] }}. Berikut ringkasan performa Catering Ibu Dedeh bulan ini.
    </p>
</div>

<!-- KARTU RINGKASAN FINANSIAL (BULAN INI) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-primary p-6 rounded-2xl shadow-lg shadow-primary/20 text-white">
        <p class="text-primary-100 text-[10px] font-bold uppercase tracking-[0.2em] opacity-80">Omzet Bulan Ini</p>
        <p class="font-headline text-2xl font-bold mt-2">Rp {{ number_format($total_revenue, 0, ',', '.') }}</p>
        <div class="mt-4 flex items-center gap-2 text-[10px] bg-white/10 w-fit px-2 py-1 rounded">
            <span class="material-symbols-outlined text-sm">trending_up</span> Total Terbayar
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">Estimasi Modal (HPP)</p>
        <p class="font-headline text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($total_cogs, 0, ',', '.') }}</p>
        <p class="text-[10px] text-gray-400 mt-4 italic">* Berdasarkan resep bahan baku</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">Laba Kotor</p>
        <p class="font-headline text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($net_profit, 0, ',', '.') }}</p>
        <p class="text-[10px] text-green-600 font-bold mt-4">
            {{ $total_revenue > 0 ? round(($net_profit / $total_revenue) * 100) : 0 }}% Profit Margin
        </p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- Metrik Harian -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <span class="material-symbols-outlined">receipt_long</span>
        </div>
        <div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider">Order Hari Ini</p>
            <p class="font-bold text-gray-900">{{ $orders_today }} Pesanan</p>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
            <span class="material-symbols-outlined">inventory_2</span>
        </div>
        <div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider">Stok Menipis</p>
            <p class="font-bold text-orange-600">{{ $low_stock_items }} Item</p>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
            <span class="material-symbols-outlined">payments</span>
        </div>
        <div>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-wider">Hutang Vendor</p>
            <p class="font-bold text-red-600">{{ $unpaid_invoices }} Nota</p>
        </div>
    </div>
</div>

<!-- GRAFIK -->
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-8 border-b border-gray-50 pb-5">
        <h2 class="text-xl font-headline font-bold text-gray-900">Performa Penjualan 7 Hari Terakhir</h2>
    </div>
    <div class="h-[300px] w-full">
        <canvas id="salesChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(87, 0, 0, 0.2)'); 
    gradient.addColorStop(1, 'rgba(87, 0, 0, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Omzet (Rp)',
                data: @json($data_rev),
                borderColor: '#570000',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
</script>
@endsection