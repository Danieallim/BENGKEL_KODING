<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}" class="inline-flex items-center justify-center w-9 h-9 
                  rounded-lg bg-slate-100 text-slate-500 
                  hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
        <div class="card-body p-8">

            <form action="{{ route('periksa-pasien.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                {{-- Pilih Obat --}}
                <div class="form-control mb-5">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Pilih Obat <span class="text-red-500">*</span></span>
                    </label>
                    <select id="select-obat" class="select select-bordered w-full rounded-lg border-2 px-4">
                        <option value="">-- Pilih Obat --</option>
                        @foreach ($obats as $obat)
                            <option value="{{ $obat->id }}"
                                data-nama="{{ $obat->nama_obat }}"
                                data-harga="{{ $obat->harga }}"
                                data-stok="{{ $obat->stok }}"
                                {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                                {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }} (Stok: {{ $obat->stok }}){{ $obat->stok <= 0 ? ' - HABIS' : ($obat->stok <= 5 ? ' - MENIPIS' : '') }}
                            </option>
                        @endforeach
                    </select>
                    <label class="label pt-1">
                        <span class="text-xs text-slate-500">Obat dengan stok 0 tidak bisa dipilih. Stok ≤ 5 ditandai menipis.</span>
                    </label>
                </div>

                {{-- Obat Terpilih --}}
                <div class="form-control mb-5">
                    <label class="label pb-1 ">
                        <span class="text-sm font-semibold text-gray-700">Obat Terpilih</span>
                    </label>

                    <ul id="obat-terpilih" class="flex flex-col gap-2 mb-2 min-h-[48px]"></ul>

                    <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                    <input type="hidden" name="obat_json" id="obat_json">
                </div>

                {{-- Total Harga --}}
                <div class="form-control mb-5">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Rincian Biaya</span>
                    </label>

                    <div class="rounded-lg border-2 bg-slate-50 px-4 py-3 space-y-2 text-sm">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Total Obat</span>
                            <span id="total-obat" class="font-semibold">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Biaya Pemeriksaan</span>
                            <span class="font-semibold">Rp 150.000</span>
                        </div>
                        <div class="border-t pt-2 flex items-center justify-between text-slate-800">
                            <span class="font-bold">Total Akhir</span>
                            <span id="total-harga" class="font-bold">Rp 150.000</span>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="form-control mb-8">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Catatan <span class="text-slate-400 font-normal">(Opsional)</span></span>
                    </label>
                    <textarea name="catatan" id="catatan" rows="4"
                        placeholder="Masukkan catatan..."
                        class="textarea textarea-bordered w-full border-2 px-4 py-2 rounded-lg resize-none">{{ old('catatan') }}</textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded-lg px-6">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                    <a href="{{ route('periksa-pasien.index') }}"
                        class="btn btn-ghost bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg px-6">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    <script>
        const selectObat = document.getElementById('select-obat');
        const listObat = document.getElementById('obat-terpilih');
        const inputBiaya = document.getElementById('biaya_periksa');
        const inputObatJson = document.getElementById('obat_json');
        const totalHargaEl = document.getElementById('total-harga');
        const totalObatEl = document.getElementById('total-obat');
        const biayaJasaPeriksa = 150000;

        let daftarObat = [];

        selectObat.addEventListener('change', () => {
            const selectedOption = selectObat.options[selectObat.selectedIndex];
            const id = selectedOption.value;
            const nama = selectedOption.dataset.nama;
            const harga = parseInt(selectedOption.dataset.harga || 0);
            const stok = parseInt(selectedOption.dataset.stok || 0);

            if (!id || daftarObat.some(o => o.id == id)) return;
            if (stok <= 0) {
                alert('Stok obat habis, silakan pilih obat lain.');
                return;
            }

            daftarObat.push({ id, nama, harga });
            renderObat();
            selectObat.selectedIndex = 0;
        });

        function renderObat() {
            listObat.innerHTML = '';
            let total = 0;

            daftarObat.forEach((obat, index) => {
                total += obat.harga;

                const item = document.createElement('li');
                item.className = 'flex items-center justify-between px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700';
                item.innerHTML = `
                    <span>${obat.nama} — <span class="font-semibold">Rp ${obat.harga.toLocaleString()}</span></span>
                    <button type="button"
                        onclick="hapusObat(${index})"
                        class="btn btn-sm bg-red-500 hover:bg-red-600 text-white border-none rounded-lg px-3">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                listObat.appendChild(item);
            });

            inputBiaya.value = total;
            totalObatEl.textContent = `Rp ${total.toLocaleString()}`;
            totalHargaEl.textContent = `Rp ${(total + biayaJasaPeriksa).toLocaleString()}`;
            inputObatJson.value = JSON.stringify(daftarObat.map(o => o.id));
        }

        function hapusObat(index) {
            daftarObat.splice(index, 1);
            renderObat();
        }
    </script>

</x-layouts.app>