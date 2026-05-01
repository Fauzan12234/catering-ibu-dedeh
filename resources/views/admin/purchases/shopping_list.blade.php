@extends('layouts.admin')
@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Daftar Belanja H-1</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
        Sistem menghitung otomatis kebutuhan bahan baku berdasarkan pesanan yang akan dikirim pada tanggal terpilih.
    </p>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <form action="{{ route('admin.purchases.shopping') }}" method="GET" class="flex items-end gap-4">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Target Pengiriman</label>
            <input type="date" name="date" value="{{ $target_date }}" class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
        </div>
        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-primary/20">Tampilkan Kebutuhan</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold">
                <th class="px-6 py-5">Nama Bahan</th>
                <th class="px-6 py-5">Kebutuhan Produksi</th>
                <th class="px-6 py-5">Stok Saat Ini</th>
                <th class="px-6 py-5 text-primary">Estimasi Belanja</th>
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-50">
            @forelse($requirements as $req)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-5 font-bold text-gray-900">{{ $req->name }}</td>
                <td class="px-6 py-5 font-medium">{{ number_format($req->total_needed, 2) }} {{ $req->unit }}</td>
                <td class="px-6 py-5 text-gray-500">{{ number_format($req->current_stock, 2) }} {{ $req->unit }}</td>
                <td class="px-6 py-5">
                    @php $to_buy = max(0, $req->total_needed - $req->current_stock); @endphp
                    <span class="px-3 py-1.5 rounded-lg {{ $to_buy > 0 ? 'bg-red-50 text-red-600 font-bold border border-red-100' : 'bg-green-50 text-green-600 font-bold' }}">
                        {{ number_format($to_buy, 2) }} {{ $req->unit }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">Tidak ada jadwal masak untuk tanggal ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection