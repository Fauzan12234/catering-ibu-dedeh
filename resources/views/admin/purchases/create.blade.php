@extends('layouts.admin')
@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Input Nota Pasar</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">Masukkan rincian belanja hari ini. Sistem akan otomatis memperbarui stok gudang dan mengalkulasi HPP menu secara real-time.</p>
</div>

<form action="{{ route('admin.purchases.store') }}" method="POST">
    @csrf
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Pilih Toko / Supplier</label>
            <select name="supplier_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                @foreach($suppliers as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Tanggal Nota</label>
            <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h2 class="font-headline font-bold text-lg text-gray-900">Rincian Barang Belanja</h2>
            <button type="button" onclick="addPurchaseRow()" class="bg-white text-primary border border-primary/20 px-4 py-2 rounded-lg text-[10px] font-bold hover:bg-gray-100 shadow-sm">+ Baris Baru</button>
        </div>
        <table class="w-full text-left table-fixed">
            <thead class="bg-white border-b border-gray-50 text-[10px] text-gray-400 uppercase font-bold">
                <tr><th class="p-5 w-2/5">Nama Bahan</th><th class="p-5 w-1/5">Qty Belanja</th><th class="p-5 w-1/5">Harga Satuan Nota</th><th class="p-5 w-1/5">Subtotal</th><th class="p-5 w-16"></th></tr>
            </thead>
            <tbody id="purchase-items" class="divide-y divide-gray-50"></tbody>
        </table>
    </div>
    <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl mt-8 shadow-lg shadow-primary/20">Simpan Nota & Update HPP Final</button>
</form>

<script>
    const materials = @json($materials); let pIdx = 0;
    function addPurchaseRow() {
        let opts = '<option value="">- Pilih Bahan -</option>';
        materials.forEach(m => opts += `<option value="${m.id}">${m.name} (${m.unit})</option>`);
        const html = `<tr id="prow-${pIdx}"><td class="p-4"><select name="items[${pIdx}][material_id]" class="w-full bg-gray-50 border-none rounded-lg p-2 text-sm" required>${opts}</select></td><td class="p-4"><input type="number" step="0.01" name="items[${pIdx}][qty]" class="p-qty w-full bg-gray-50 border-none rounded-lg p-2 text-sm" oninput="calcP(${pIdx})" required></td><td class="p-4"><input type="number" name="items[${pIdx}][price]" class="p-price w-full bg-gray-50 border-none rounded-lg p-2 text-sm" oninput="calcP(${pIdx})" required></td><td class="p-4"><span class="p-sub font-bold text-gray-900 text-sm">Rp 0</span></td><td class="p-4"><button type="button" onclick="document.getElementById('prow-${pIdx}').remove()" class="text-red-400">×</button></td></tr>`;
        document.getElementById('purchase-items').insertAdjacentHTML('beforeend', html); pIdx++;
    }
    function calcP(id) {
        const row = document.getElementById(`prow-${id}`);
        const q = row.querySelector('.p-qty').value || 0;
        const p = row.querySelector('.p-price').value || 0;
        row.querySelector('.p-sub').innerText = 'Rp ' + (q * p).toLocaleString('id-ID');
    }
    window.onload = addPurchaseRow;
</script>
@endsection