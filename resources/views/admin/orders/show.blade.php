@extends('layouts.admin')
@section('content')
<div class="mb-8 flex justify-between items-center no-print">
    <!-- PERBAIKAN RUTE DI SINI -->
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-gray-400 hover:text-primary flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar
    </a>
    <button onclick="window.print()" class="bg-gray-900 text-white px-6 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-black transition-all">
        <span class="material-symbols-outlined text-sm">print</span> Cetak Invoice
    </button>
</div>

<div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 max-w-4xl mx-auto" id="printable-invoice">
    <!-- Header Invoice -->
    <div class="flex justify-between items-start mb-12">
        <div>
            <img src="{{ asset('media/logo.png') }}" class="h-10 mb-4">
            <h2 class="text-xl font-headline font-bold text-primary uppercase">Catering Ibu Dedeh</h2>
            <p class="text-xs text-gray-400 max-w-xs">Jl. Contoh No. 123, Sukabumi. WhatsApp: 0812-XXXX-XXXX</p>
        </div>
        <div class="text-right">
            <h1 class="text-4xl font-headline font-bold text-gray-200 uppercase tracking-tighter mb-2">Invoice</h1>
            <p class="text-sm font-bold text-gray-900">{{ $order->order_ref }}</p>
            <p class="text-xs text-gray-400">{{ date('d F Y', strtotime($order->order_date)) }}</p>
        </div>
    </div>

    <!-- Info Pelanggan -->
    <div class="grid grid-cols-2 gap-12 mb-12 pb-12 border-b border-gray-100">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Ditujukan Untuk:</p>
            <p class="font-bold text-gray-900 text-lg">{{ $order->customer->name }}</p>
            <p class="text-sm text-gray-500">{{ $order->customer->phone }}</p>
            <p class="text-sm text-gray-500">{{ $order->customer->address }}</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Detail Pengiriman:</p>
            <p class="text-sm font-bold text-gray-900">Tanggal Kirim: {{ date('d/m/Y', strtotime($schedule->delivery_date)) }}</p>
            <p class="text-sm text-gray-500">Tipe: {{ ucfirst($order->order_type) }}</p>
        </div>
    </div>

    <!-- Tabel Item -->
    <table class="w-full mb-12">
        <thead class="border-b-2 border-gray-900">
            <tr class="text-left text-[10px] font-bold uppercase tracking-widest text-gray-900">
                <th class="py-4">Menu Pesanan</th>
                <th class="py-4 text-center">Qty</th>
                <th class="py-4 text-right">Harga</th>
                <th class="py-4 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($items as $item)
            <tr>
                <td class="py-6 font-bold text-gray-900">{{ $item->menu_name }}</td>
                <td class="py-6 text-center text-sm">{{ $item->quantity }} porsi</td>
                <td class="py-6 text-right text-sm">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="py-6 text-right font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="py-6 text-right font-bold uppercase text-[10px] text-gray-400">Total Pembayaran</td>
                <td class="py-6 text-right font-headline font-bold text-2xl text-primary border-t-2 border-gray-900">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer Note -->
    <div class="flex justify-between items-end">
        <div class="text-[10px] text-gray-400 italic">
            * Syarat & Ketentuan berlaku.<br>Terima kasih telah mempercayakan hidangan Anda kepada kami.
        </div>
        <div class="text-center w-48">
            <p class="text-[10px] font-bold text-gray-400 uppercase mb-16">Hormat Kami,</p>
            <div class="border-b border-gray-200"></div>
            <p class="text-xs font-bold text-gray-900 mt-2">Catering Ibu Dedeh</p>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white; }
        .no-print, aside, header { display: none !important; }
        main { padding: 0 !important; }
        #printable-invoice { border: none !important; shadow: none !important; width: 100% !important; max-width: none !important; padding: 0 !important; }
    }
</style>
@endsection