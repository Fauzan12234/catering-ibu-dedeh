@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Daftar Supplier</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
        Kelola daftar toko atau vendor tempat Anda berbelanja kebutuhan operasional.
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
            <h2 class="font-headline font-bold text-lg text-gray-900 mb-6 border-b border-gray-50 pb-3">Registrasi Supplier</h2>
            <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama Toko/Vendor</label>
                    <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nomor Kontak (WA/Telp)</label>
                    <input type="text" name="phone" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
                <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary-container transition-all shadow-lg shadow-primary/20 mt-2">Simpan Data</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-xs text-gray-400 uppercase tracking-widest font-bold">
                            <th class="px-6 py-5">Nama Vendor</th>
                            <th class="px-6 py-5">Nomor Kontak</th>
                            <th class="px-6 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @forelse($suppliers as $sup)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <p class="font-bold text-gray-900 text-base">{{ $sup->name }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-medium text-gray-700">{{ $sup->phone }}</p>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <form action="{{ route('admin.suppliers.destroy', $sup->id) }}" method="POST" class="inline" onsubmit="return confirm('Menghapus data ini bersifat permanen. Lanjutkan?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-12 text-center text-gray-400">Buku kontak supplier masih kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection