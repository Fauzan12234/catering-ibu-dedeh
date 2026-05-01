@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Katalog Menu & Resep</h1>
        <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
            Daftar menu yang tersedia beserta rincian Harga Pokok Penjualan (HPP) yang dihitung dari resep.
        </p>
    </div>
    <a href="{{ route('admin.menus.create') }}" class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-container transition-all shadow-lg shadow-primary/20 flex items-center gap-2 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px]">add</span> Racik Menu Baru
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold border border-green-100">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100">{{ session('error') }}</div>
@endif

<!-- PANEL FILTER -->
<div class="bg-white p-4 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100 mb-6 flex flex-col md:flex-row items-start md:items-center gap-4">
    <span class="text-sm font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">filter_list</span> Filter Etalase:
    </span>
    <form action="{{ route('admin.menus.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <select name="type" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-gray-700 min-w-[150px]">
            <option value="">Semua Tipe</option>
            <option value="satuan" {{ request('type') == 'satuan' ? 'selected' : '' }}>Menu Satuan</option>
            <option value="paket" {{ request('type') == 'paket' ? 'selected' : '' }}>Menu Paketan</option>
        </select>

        <select name="category" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-gray-700 min-w-[180px]">
            <option value="">Semua Kategori</option>
            <option value="Nasi Box" {{ request('category') == 'Nasi Box' ? 'selected' : '' }}>Kategori Nasi Box</option>
            <option value="Prasmanan" {{ request('category') == 'Prasmanan' ? 'selected' : '' }}>Kategori Prasmanan</option>
            <option value="Tumpeng" {{ request('category') == 'Tumpeng' ? 'selected' : '' }}>Kategori Tumpeng</option>
        </select>

        @if(request('type') || request('category'))
            <a href="{{ route('admin.menus.index') }}" class="text-sm font-bold text-red-500 hover:text-red-700 transition-colors px-4 py-2 bg-red-50 hover:bg-red-100 rounded-lg flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">close</span> Reset
            </a>
        @endif
    </form>
</div>

<!-- TABEL DATA -->
<div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-xs text-gray-400 uppercase tracking-widest font-bold">
                    <th class="px-6 py-5">Informasi Menu</th>
                    <th class="px-6 py-5">Harga Jual</th>
                    <th class="px-6 py-5">Modal (HPP Dinamis)</th>
                    <th class="px-6 py-5">Margin Pendapatan</th>
                    <th class="px-6 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                @forelse($menus as $m)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-5">
                        <p class="font-bold text-gray-900 text-base">{{ $m->name }}</p>
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">{{ $m->category }} <span class="mx-1">•</span> TIPE: {{ $m->type }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <p class="font-bold text-gray-800 text-base">Rp {{ number_format($m->selling_price, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <p class="font-bold text-red-600 text-base">Rp {{ number_format($m->current_cogs, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <p class="font-bold text-green-600 text-base">Rp {{ number_format($m->selling_price - $m->current_cogs, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-5 text-right">
                        <a href="{{ route('admin.menus.edit', $m->id) }}" class="inline-flex text-blue-600 hover:text-blue-800 bg-blue-50 p-2 rounded-lg transition-colors mr-1">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>
                        <form action="{{ route('admin.menus.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Menghapus menu ini bersifat permanen. Lanjutkan?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition-colors"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Katalog menu tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- PAGINASI BAWAAN LARAVEL -->
    @if($menus->hasPages())
    <div class="p-6 border-t border-gray-50 bg-gray-50/50">
        {{ $menus->links() }}
    </div>
    @endif
</div>
@endsection