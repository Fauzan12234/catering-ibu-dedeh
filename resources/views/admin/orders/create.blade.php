@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Input Orderan Baru</h1>
    <p class="text-gray-500 text-sm mt-2 max-w-2xl leading-relaxed">
        Terima pesanan masuk dan tentukan jadwal kirim. Kebutuhan belanja besok akan otomatis disiapkan oleh sistem berdasarkan data menu yang dipesan.
    </p>
</div>

@if(session('error'))
<div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100">{{ session('error') }}</div>
@endif

<form action="{{ route('admin.orders.store') }}" method="POST">
    @csrf
    <div class="bg-white p-8 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100 mb-8">
        <h2 class="font-headline font-bold text-lg text-gray-900 mb-6 border-b border-gray-50 pb-3">Informasi Pelanggan & Jadwal</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">No. Referensi Order</label>
                <input type="text" name="order_ref" value="{{ $order_ref }}" readonly class="w-full bg-gray-100 border border-gray-200 rounded-xl p-3 text-sm font-bold text-gray-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama Pelanggan</label>
                <input type="text" name="customer_name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nomor WhatsApp</label>
                <input type="text" name="customer_phone" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Alamat Pengiriman</label>
                <input type="text" name="customer_address" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tipe Pesanan</label>
                <select name="order_type" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="retail">Retail / Nasi Box</option>
                    <option value="prasmanan">Prasmanan</option>
                    <option value="factory">Katering Pabrik</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tanggal Pengiriman (Hari H)</label>
                <input type="date" name="delivery_date" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
        <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
            <h2 class="font-headline font-bold text-lg text-gray-900">Daftar Menu Pesanan</h2>
            <button type="button" onclick="addOrderRow()" class="bg-white text-primary border border-primary/20 px-4 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 shadow-sm transition-all">+ Tambah Baris Menu</button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left table-fixed">
                <thead class="bg-white border-b border-gray-50">
                    <tr class="text-xs text-gray-400 uppercase tracking-widest font-bold">
                        <th class="p-5 w-2/5">Pilih Menu dari Etalase</th>
                        <th class="p-5 w-1/5">Jumlah Porsi</th>
                        <th class="p-5 w-1/5">Harga Satuan</th>
                        <th class="p-5 w-1/5">Subtotal</th>
                        <th class="p-5 w-16"></th>
                    </tr>
                </thead>
                <tbody id="order-items" class="divide-y divide-gray-50"></tbody>
                <tfoot class="bg-gray-50 border-t border-gray-100">
                    <tr>
                        <td colspan="3" class="p-6 text-right font-bold text-gray-500 uppercase tracking-wide text-xs align-middle">Total Tagihan Pesanan:</td>
                        <td colspan="2" class="p-6 align-middle">
                            <div id="display_grand_total" class="font-headline font-bold text-primary text-2xl tracking-tight">Rp 0</div>
                            <input type="hidden" name="grand_total" id="order_grand_total" value="0">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-primary-container transition-all shadow-lg shadow-primary/20 mt-8 text-lg">
        Validasi & Simpan Pesanan
    </button>
</form>

<script>
    const menus = @json($menus); 
    let ordIdx = 0;

    function addOrderRow() {
        let opts = '<option value="">- Pilih Menu -</option>';
        menus.forEach(m => {
            opts += `<option value="${m.id}" data-price="${m.selling_price}">${m.name}</option>`;
        });
        
        const html = `
            <tr id="ord-${ordIdx}" class="hover:bg-gray-50/50 transition-colors">
                <td class="p-5">
                    <select name="items[${ordIdx}][menu_id]" class="menu-select w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" onchange="calcOrder(${ordIdx})" required>
                        ${opts}
                    </select>
                </td>
                <td class="p-5">
                    <input type="number" name="items[${ordIdx}][qty]" class="ord-qty w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" oninput="calcOrder(${ordIdx})" required>
                </td>
                <td class="p-5">
                    <input type="number" name="items[${ordIdx}][price]" class="ord-price w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" oninput="calcOrder(${ordIdx})" required>
                </td>
                <td class="p-5">
                    <input type="text" readonly class="ord-subtotal-display w-full bg-transparent border-none text-gray-800 font-bold p-0 text-base cursor-default focus:ring-0" value="Rp 0">
                    <input type="hidden" class="ord-subtotal" value="0">
                </td>
                <td class="p-5 text-center">
                    <button type="button" onclick="remOrder(${ordIdx})" class="text-red-400 hover:text-red-600 bg-red-50 p-2 rounded-lg transition-colors"><span class="material-symbols-outlined text-[18px]">close</span></button>
                </td>
            </tr>`;
        
        document.getElementById('order-items').insertAdjacentHTML('beforeend', html); 
        ordIdx++;
    }

    function remOrder(id) { 
        document.getElementById(`ord-${id}`).remove(); 
        calcGrand(); 
    }

    function calcOrder(id) {
        const row = document.getElementById(`ord-${id}`); 
        const sel = row.querySelector('.menu-select');
        
        if(sel.selectedIndex > 0 && !row.querySelector('.ord-price').value) { 
            row.querySelector('.ord-price').value = sel.options[sel.selectedIndex].getAttribute('data-price'); 
        }
        
        const q = parseFloat(row.querySelector('.ord-qty').value) || 0; 
        const p = parseFloat(row.querySelector('.ord-price').value) || 0;
        const subtotal = q * p;

        row.querySelector('.ord-subtotal').value = subtotal; 
        row.querySelector('.ord-subtotal-display').value = 'Rp ' + subtotal.toLocaleString('id-ID');
        calcGrand();
    }

    function calcGrand() {
        let t = 0; 
        document.querySelectorAll('.ord-subtotal').forEach(i => t += parseFloat(i.value) || 0); 
        document.getElementById('order_grand_total').value = t;
        document.getElementById('display_grand_total').innerText = 'Rp ' + t.toLocaleString('id-ID');
    }

    window.onload = addOrderRow;
</script>
@endsection