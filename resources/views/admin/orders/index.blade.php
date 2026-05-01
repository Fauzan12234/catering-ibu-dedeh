@extends('layouts.admin')

@section('content')
<div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Manajemen Pesanan</h1>
        <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
            Pantau semua pesanan masuk, status pembayaran, dan jadwal pengiriman pelanggan dalam satu layar.
        </p>
    </div>
    <!-- PERBAIKAN RUTE DI SINI -->
    <a href="{{ route('admin.orders.create') }}" class="bg-primary text-white px-6 py-3.5 rounded-xl text-sm font-bold hover:bg-red-900 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span> Buat Pesanan Baru
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold border border-green-100 flex items-center gap-3">
    <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('success') }}
</div>
@endif

<!-- TABEL DAFTAR ORDER -->
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-[10px] text-gray-400 uppercase font-extrabold tracking-[0.2em]">
                    <th class="px-8 py-5">No. Referensi</th>
                    <th class="px-8 py-5">Nama Pelanggan</th>
                    <th class="px-8 py-5">Tgl Pengiriman</th>
                    <th class="px-8 py-5">Total Tagihan</th>
                    <th class="px-8 py-5">Status Bayar</th>
                    <th class="px-8 py-5 text-right">Opsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $o)
                <tr class="hover:bg-gray-50/50 transition-all">
                    <td class="px-8 py-5">
                        <span class="font-bold text-gray-900 text-sm">{{ $o->order_ref }}</span>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-tighter">{{ $o->order_type }}</p>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800 text-sm">{{ $o->customer->name }}</span>
                            <span class="text-[10px] text-gray-400">{{ $o->customer->phone }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2 text-gray-600">
                            <span class="material-symbols-outlined text-base">event</span>
                            <span class="text-sm font-medium">{{ date('d M Y', strtotime($o->order_date)) }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="font-headline font-bold text-gray-900 text-base">Rp {{ number_format($o->grand_total, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-8 py-5">
                        @if($o->payment_status == 'paid')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-wider border border-green-100">
                                <span class="w-1 h-1 rounded-full bg-green-600"></span> Lunas
                            </span>
                        @elseif($o->payment_status == 'dp')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider border border-blue-100">
                                <span class="w-1 h-1 rounded-full bg-blue-600"></span> DP
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider border border-red-100">
                                <span class="w-1 h-1 rounded-full bg-red-600"></span> Belum Bayar
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <!-- PERBAIKAN RUTE DI SINI -->
                            <a href="{{ route('admin.orders.show', $o->id) }}" class="p-2 text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="Lihat Invoice">
                                <span class="material-symbols-outlined text-[20px]">description</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center">
                        <span class="material-symbols-outlined text-5xl text-gray-200">contract_edit</span>
                        <p class="text-gray-400 text-sm mt-4 font-medium">Belum ada pesanan masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection