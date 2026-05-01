@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Katalog Gudang Bahan Baku</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
        Daftarkan material yang digunakan di dapur. Angka stok dan HPP (Harga Pokok Penjualan) akan diperbarui secara otomatis melalui sistem faktur pembelian.
    </p>
</div>

@if(session('success'))
<div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold border border-green-100">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100">
            <h2 class="font-headline font-bold text-lg text-gray-900 mb-6 border-b border-gray-50 pb-3">Registrasi Material Baru</h2>
            <form action="{{ route('admin.materials.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kode SKU (Opsional)</label>
                    <input type="text" name="sku" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary uppercase transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama Bahan Baku</label>
                    <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kategori</label>
                        <select name="category" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="Sayuran">Sayuran</option>
                            <option value="Daging/Ikan">Daging/Ikan</option>
                            <option value="Bumbu">Bumbu</option>
                            <option value="Karbohidrat">Karbohidrat</option>
                            <option value="Kemasan">Kemasan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Satuan</label>
                        <select name="unit" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="Kg">Kg</option>
                            <option value="Gram">Gram</option>
                            <option value="Liter">Liter</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Ikat">Ikat</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Batas Peringatan Stok Minimal</label>
                    <input type="number" step="0.01" name="min_stock" required value="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                
                <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary-container transition-all shadow-lg shadow-primary/20 mt-2">Daftarkan Ke Gudang</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-xs text-gray-400 uppercase tracking-widest font-bold">
                            <th class="px-6 py-5">Deskripsi Item</th>
                            <th class="px-6 py-5">Ketersediaan Stok</th>
                            <th class="px-6 py-5">HPP Dinamis Rata-rata</th>
                            <th class="px-6 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @forelse($materials as $mat)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <p class="font-bold text-gray-900 text-base">{{ $mat->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $mat->category }} <span class="mx-1">•</span> SKU: {{ $mat->sku ?? 'Tidak diatur' }}</p>
                            </td>
                            <td class="px-6 py-5">
                                @if($mat->current_stock <= $mat->min_stock)
                                    <span class="inline-flex items-center gap-1.5 text-red-700 font-bold bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                                        <span class="material-symbols-outlined text-[16px]">warning</span> {{ $mat->current_stock }} {{ $mat->unit }}
                                    </span>
                                @else
                                    <span class="font-bold text-gray-700 text-base">{{ $mat->current_stock }} <span class="font-normal text-gray-500 text-xs">{{ $mat->unit }}</span></span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-bold text-primary text-base">Rp {{ number_format($mat->moving_average_price, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">PER {{ $mat->unit }}</p>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <form action="{{ route('admin.materials.destroy', $mat->id) }}" method="POST" class="inline" onsubmit="return confirm('Menghapus item dari katalog bersifat permanen. Lanjutkan?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">Katalog gudang saat ini belum memiliki data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection