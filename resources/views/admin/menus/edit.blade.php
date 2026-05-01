@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.menus.index') }}" class="text-sm font-bold text-gray-400 hover:text-primary transition-colors mb-2 inline-block">&larr; Kembali ke Katalog</a>
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Perbarui Data Menu</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
        Lakukan penyesuaian harga jual, deskripsi, atau ubah takaran bahan baku pada resep menu ini.
    </p>
</div>

@if($errors->any())
<div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100">
    <ul class="list-disc list-inside ml-4">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Informasi Produk -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-8 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100">
                <h2 class="font-headline font-bold text-lg text-gray-900 mb-6 border-b border-gray-50 pb-3">Informasi Produk</h2>
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama Menu</label>
                        <input type="text" name="name" value="{{ $menu->name }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tipe</label>
                            <select name="type" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="satuan" {{ $menu->type == 'satuan' ? 'selected' : '' }}>Satuan</option>
                                <option value="paket" {{ $menu->type == 'paket' ? 'selected' : '' }}>Paket</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kategori</label>
                            <select name="category" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="Nasi Box" {{ $menu->category == 'Nasi Box' ? 'selected' : '' }}>Nasi Box</option>
                                <option value="Prasmanan" {{ $menu->category == 'Prasmanan' ? 'selected' : '' }}>Prasmanan</option>
                                <option value="Tumpeng" {{ $menu->category == 'Tumpeng' ? 'selected' : '' }}>Tumpeng</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Harga Jual (Rp)</label>
                        <input type="number" name="selling_price" value="{{ $menu->selling_price }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-primary focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">{{ $menu->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Komposisi Resep (BOM) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="font-headline font-bold text-lg text-gray-900">Komposisi Resep (BOM)</h2>
                        <p class="text-xs text-gray-500 mt-1">Estimasi HPP akan dikalkulasi ulang secara otomatis.</p>
                    </div>
                    <button type="button" onclick="addRow()" class="bg-white text-primary border border-primary/20 px-4 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 shadow-sm transition-all">+ Tambah Bahan</button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white border-b border-gray-50">
                            <tr class="text-xs text-gray-400 uppercase tracking-widest font-bold">
                                <th class="p-5 w-1/2">Bahan Baku (Gudang)</th>
                                <th class="p-5 w-1/4">Kebutuhan</th>
                                <th class="p-5 w-1/4">Estimasi HPP</th>
                                <th class="p-5"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-items" class="divide-y divide-gray-50">
                            <!-- Injeksi Baris Javascript -->
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-100">
                            <tr>
                                <td colspan="2" class="p-6 text-right font-bold text-gray-500 uppercase tracking-wide text-xs">Estimasi Modal Total:</td>
                                <td class="p-6 font-headline font-bold text-red-600 text-xl" id="total_hpp_display">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-primary-container transition-all shadow-lg shadow-primary/20 mt-8 text-lg">
                Simpan Pembaruan Data
            </button>
        </div>
    </div>
</form>

<script>
    const materials = @json($materials);
    const existingRecipes = @json($menu->recipes);
    let rowIdx = 0;
    
    function addRow(selectedMaterialId = '', qtyValue = '') {
        let options = '<option value="">- Cari Bahan -</option>';
        materials.forEach(m => {
            const isSelected = (m.id == selectedMaterialId) ? 'selected' : '';
            options += `<option value="${m.id}" data-price="${m.moving_average_price}" ${isSelected}>${m.name} (${m.unit})</option>`;
        });

        const html = `
            <tr id="row-${rowIdx}" class="hover:bg-gray-50/50 transition-colors">
                <td class="p-5">
                    <select name="items[${rowIdx}][material_id]" class="mat-select w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" onchange="calculateRow(${rowIdx})" required>
                        ${options}
                    </select>
                </td>
                <td class="p-5">
                    <input type="number" step="0.01" name="items[${rowIdx}][qty]" value="${qtyValue}" class="qty-input w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" oninput="calculateRow(${rowIdx})" required>
                </td>
                <td class="p-5">
                    <input type="text" readonly class="subtotal-display w-full bg-transparent border-none text-gray-800 font-bold p-0 text-base" value="Rp 0">
                    <input type="hidden" class="subtotal-value" value="0">
                </td>
                <td class="p-5 text-center">
                    <button type="button" onclick="removeRow(${rowIdx})" class="text-red-400 hover:text-red-600 bg-red-50 p-2 rounded-lg transition-colors"><span class="material-symbols-outlined text-[18px]">close</span></button>
                </td>
            </tr>
        `;
        document.getElementById('tbody-items').insertAdjacentHTML('beforeend', html);
        
        // Kalkulasi awal jika data pre-filled
        if(selectedMaterialId !== '') {
            calculateRow(rowIdx);
        }
        
        rowIdx++;
    }

    function removeRow(id) {
        document.getElementById(`row-${id}`).remove();
        calculateGrandTotal();
    }

    function calculateRow(id) {
        const row = document.getElementById(`row-${id}`);
        const select = row.querySelector('.mat-select');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = select.options[select.selectedIndex]?.getAttribute('data-price') || 0;
        
        const subtotal = qty * price;
        row.querySelector('.subtotal-value').value = subtotal;
        row.querySelector('.subtotal-display').value = 'Rp ' + Math.round(subtotal).toLocaleString('id-ID');
        
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-value').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total_hpp_display').innerText = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }

    window.onload = function() {
        // Tampilkan resep yang sudah ada jika ada, jika kosong tampilkan 1 baris kosong
        if(existingRecipes.length > 0) {
            existingRecipes.forEach(recipe => {
                addRow(recipe.material_id, recipe.quantity);
            });
        } else {
            addRow();
        }
    };
</script>
@endsection