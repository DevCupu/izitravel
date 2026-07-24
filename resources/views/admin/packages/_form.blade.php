@php
    $badgeOptions = [
        '' => 'Tanpa Badge',
        'amber' => 'Amber (Best Seller)',
        'emerald' => 'Emerald (Promo)',
        'indigo' => 'Indigo (VIP Deluxe)',
        'rose' => 'Rose (Kuota Terbatas)',
    ];
@endphp

<div class="space-y-6">
    <!-- Section: Informasi Paket -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                <i data-lucide="info" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Informasi Paket</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Detail utama paket umrah</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2 form-group">
                <label for="name" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Nama Paket') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}" required
                    class="transition"
                    placeholder="Contoh: Paket Umrah Premium 12 Hari">
                @error('name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
 
            <div class="form-group">
                <label for="category" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Kategori Paket') }}</label>
                <select id="category" name="category"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500 font-semibold">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->name }}" @selected(old('category', $package->category) === $cat->name)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="duration_days" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Durasi (Hari)') }}</label>
                <input type="number" id="duration_days" name="duration_days" min="1" max="60" value="{{ old('duration_days', $package->duration_days) }}" required
                    class="transition" placeholder="Contoh: 12">
                @error('duration_days') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
 
            <div class="form-group">
                <label for="departure_date" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Tanggal Keberangkatan') }}</label>
                <input type="date" id="departure_date" name="departure_date" value="{{ old('departure_date', optional($package->departure_date)->format('Y-m-d')) }}" required
                    class="transition">
                @error('departure_date') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
 
            <div class="form-group">
                <label for="airline" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Maskapai') }}</label>
                <input type="text" id="airline" name="airline" value="{{ old('airline', $package->airline) }}" required
                    class="transition"
                    placeholder="Contoh: Garuda Indonesia">
                @error('airline') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
 
            <div class="md:col-span-2">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 flex items-center gap-1.5">
                    <i data-lucide="hotel" class="w-3.5 h-3.5"></i> Hotel Akomodasi
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Hotel Makkah -->
                    <div class="space-y-3 p-4 rounded-xl bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30">
                        <p class="text-[10px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center gap-1">
                            <i data-lucide="kaaba" class="w-3 h-3"></i> Makkah
                        </p>
                        <div class="form-group">
                            <label for="hotel_makkah">Nama Hotel Makkah</label>
                            <input type="text" id="hotel_makkah" name="hotel_makkah" value="{{ old('hotel_makkah', $package->hotel_makkah) }}"
                                class="transition" placeholder="Contoh: Safwah Royal Orchid">
                            @error('hotel_makkah') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="hotel_makkah_nights">Jumlah Malam di Makkah</label>
                            <input type="number" id="hotel_makkah_nights" name="hotel_makkah_nights" min="1" max="30"
                                value="{{ old('hotel_makkah_nights', $package->hotel_makkah_nights) }}"
                                class="transition" placeholder="Contoh: 7">
                            @error('hotel_makkah_nights') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <!-- Hotel Madinah -->
                    <div class="space-y-3 p-4 rounded-xl bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30">
                        <p class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                            <i data-lucide="building-2" class="w-3 h-3"></i> Madinah
                        </p>
                        <div class="form-group">
                            <label for="hotel_madinah">Nama Hotel Madinah</label>
                            <input type="text" id="hotel_madinah" name="hotel_madinah" value="{{ old('hotel_madinah', $package->hotel_madinah) }}"
                                class="transition" placeholder="Contoh: Mövenpick Hotel">
                            @error('hotel_madinah') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="hotel_madinah_nights">Jumlah Malam di Madinah</label>
                            <input type="number" id="hotel_madinah_nights" name="hotel_madinah_nights" min="1" max="30"
                                value="{{ old('hotel_madinah_nights', $package->hotel_madinah_nights) }}"
                                class="transition" placeholder="Contoh: 4">
                            @error('hotel_madinah_nights') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-[10px] text-slate-400 dark:text-slate-500">Opsional: isi jika ingin menampilkan detail hotel per kota di halaman paket.</p>
            </div>

            <div class="form-group">
                <label for="hotel" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Hotel (Tampilan Singkat)') }}</label>
                <input type="text" id="hotel" name="hotel" value="{{ old('hotel', $package->hotel) }}"
                    class="transition"
                    placeholder="Contoh: Safwah Royal Orchid / Mövenpick">
                <p class="mt-1 text-[10px] text-slate-400">Ditampilkan sebagai ringkasan hotel di card paket.</p>
                @error('hotel') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
 
    <div class="border-t border-slate-100 dark:border-slate-700"></div>
 
    <!-- Section: Harga & Badge -->
    <div x-data="{ 
        rawPrice: @js(old('price', $package->price ?? 0)),
        badgeColor: @js(old('badge_color', $package->badge_color ?? '')),
        badgeLabel: @js(old('badge_label', $package->badge_label ?? '')),
        get formattedPrice() {
            if (!this.rawPrice) return '';
            return new Intl.NumberFormat('id-ID').format(this.rawPrice);
        },
        set formattedPrice(val) {
            let clean = val.replace(/[^\d]/g, '');
            this.rawPrice = clean ? parseInt(clean) : '';
        }
    }" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-50 dark:border-slate-700 pb-4 gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Harga & Badge Paket</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Atur skema penarifan dan label penanda promo paket</p>
                </div>
            </div>
            
            <!-- Real-time Badge Preview -->
            <div class="shrink-0 flex items-center gap-2 bg-slate-50 dark:bg-slate-900 px-3.5 py-1.5 rounded-xl border border-slate-100/50 dark:border-slate-700/50 text-xs">
                <span class="text-slate-400 dark:text-slate-500 font-bold">Preview Badge:</span>
                <span x-show="badgeLabel" class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider transition-all duration-200"
                    :class="{
                        'bg-amber-500 text-slate-950': badgeColor === 'amber',
                        'bg-emerald-600 text-white': badgeColor === 'emerald',
                        'bg-indigo-600 text-white': badgeColor === 'indigo',
                        'bg-rose-600 text-white': badgeColor === 'rose',
                        'bg-slate-900 text-white': !badgeColor
                    }">
                    <i data-lucide="sparkles" class="w-3 h-3"></i>
                    <span x-text="badgeLabel"></span>
                </span>
                <span x-show="!badgeLabel" class="text-slate-400 dark:text-slate-500 italic">Tanpa Badge</span>
            </div>
        </div>
 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Harga Field -->
            <div class="form-group">
                <label for="price_display" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Harga Paket (Rp)') }}</label>
                <div class="relative group">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-slate-400 group-focus-within:text-blue-500 transition-colors">Rp</span>
                    <!-- Hidden field to submit plain integer to the backend -->
                    <input type="hidden" name="price" :value="rawPrice">
                    <!-- Text field for formatted display -->
                    <input type="text" id="price_display" x-model="formattedPrice" required
                        class="pl-12 transition text-base font-extrabold tracking-wide text-slate-800 dark:text-white" placeholder="Contoh: 29.700.000">
                </div>
                <p class="mt-1 text-[10px] text-slate-400 dark:text-slate-500">Angka akan diformat otomatis dengan pemisah titik.</p>
                @error('price') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
 
            <!-- Urutan Tampil Field -->
            <div class="form-group">
                <label for="order" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Urutan Tampil') }}</label>
                <input type="number" id="order" name="order" min="0" value="{{ old('order', $package->order) }}"
                    class="transition font-semibold" placeholder="Contoh: 1">
                <p class="mt-1 text-[10px] text-slate-400 dark:text-slate-500">Menentukan urutan paket di landing page (0 teratas).</p>
                @error('order') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
 
            <!-- Warna Badge Field -->
            <div class="form-group">
                <label for="badge_color" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Warna Desain Badge') }}</label>
                <select id="badge_color" name="badge_color" x-model="badgeColor"
                    class="transition font-semibold">
                    @foreach ($badgeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('badge_color') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
 
            <!-- Teks Badge Field -->
            <div class="form-group">
                <label for="badge_label" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Teks Badge Promo') }}</label>
                <input type="text" id="badge_label" name="badge_label" x-model="badgeLabel" placeholder="Contoh: BEST SELLER"
                    class="transition font-semibold">
                @error('badge_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Fasilitas Termasuk -->
    @php
        $defaultInclusions = [
            ['icon' => 'plane', 'label' => 'Tiket pesawat PP', 'desc' => 'Penerbangan pulang-pergi dengan maskapai terpercaya'],
            ['icon' => 'file-check', 'label' => 'Pengurusan visa resmi', 'desc' => 'Visa umrah resmi yang diproses langsung'],
            ['icon' => 'building-2', 'label' => 'Hotel pelataran masjid', 'desc' => 'Akomodasi dekat Masjidil Haram & Nabawi'],
            ['icon' => 'shield-check', 'label' => 'Asuransi perjalanan', 'desc' => 'Perlindungan selama perjalanan ibadah'],
            ['icon' => 'luggage', 'label' => 'Perlengkapan ibadah', 'desc' => 'Koper, kain ihram, buku doa, & lainnya'],
            ['icon' => 'compass', 'label' => 'Muthawwif bersertifikat', 'desc' => 'Pembimbing ibadah profesional & berpengalaman'],
            ['icon' => 'utensils', 'label' => 'Makan prasmanan 3x', 'desc' => 'Menu halal & beragam setiap hari'],
            ['icon' => 'book-open', 'label' => 'Handling & manasik', 'desc' => 'Bimbingan manasik pra-keberangkatan'],
        ];
        $inclusions = old('inclusions', $package->inclusions ?? $defaultInclusions);
        $allIcons = [
            'plane','building-2','hotel','shield-check','file-check','compass','utensils',
            'luggage','book-open','check-circle','star','heart','clock','map-pin','phone',
            'mail','calendar','users','user','award','badge-check','credit-card','wallet',
            'bus','sun','moon','camera','gift','package','flag','globe','map','thumbs-up',
            'smile','coffee','bed','wifi','anchor','leaf','home','zap','sparkles',
            'file-text','image','video','music','headphones','mic','radio',
        ];
    @endphp
    <div x-data="{
        items: {{ json_encode($inclusions) }},
        allIcons: {{ json_encode($allIcons) }},
        pickerIndex: null,
        iconSearch: '',
        get filteredIcons() {
            if (!this.iconSearch) return this.allIcons;
            return this.allIcons.filter(n => n.includes(this.iconSearch.toLowerCase()));
        },
        openPicker(i) {
            this.pickerIndex = (this.pickerIndex === i) ? null : i;
            this.iconSearch = '';
            if (this.pickerIndex !== null) {
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            }
        },
        selectIcon(i, name) {
            this.items[i].icon = name;
            this.pickerIndex = null;
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },
        addItem() {
            this.items.push({ icon: 'check-circle', label: '', desc: '' });
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },
        removeItem(i) {
            this.items.splice(i, 1);
            if (this.pickerIndex === i) this.pickerIndex = null;
        }
    }">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center shrink-0">
                <i data-lucide="list-checks" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Fasilitas yang Termasuk</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Klik ikon untuk mengganti, isi label dan deskripsi</p>
            </div>
        </div>

        <div class="space-y-2 mb-3">
            <template x-for="(item, i) in items" :key="i">
                <div class="flex items-start gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-100 dark:border-slate-600">

                    <!-- Icon Picker Trigger -->
                    <div class="relative shrink-0 mt-5">
                        <input type="hidden" :name="'inclusions['+i+'][icon]'" x-model="item.icon">
                        <button type="button"
                            @click="openPicker(i)"
                            :title="'Ikon: ' + item.icon"
                            class="w-10 h-10 flex flex-col items-center justify-center rounded-xl border-2 transition gap-0.5"
                            :class="pickerIndex === i
                                ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/30 text-violet-600'
                                : 'border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:border-violet-400'">
                            <i :data-lucide="item.icon" class="w-4 h-4 pointer-events-none"></i>
                            <span class="text-[8px] font-bold leading-none opacity-50" x-text="item.icon.slice(0,6)"></span>
                        </button>

                        <!-- Picker Dropdown -->
                        <div x-show="pickerIndex === i"
                            x-cloak
                            @click.outside="pickerIndex = null"
                            class="absolute left-0 top-full mt-1 z-50 w-72 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-600 p-3">
                            <input x-model="iconSearch"
                                type="text"
                                placeholder="Cari nama ikon... (contoh: plane)"
                                class="w-full mb-2.5 px-3 py-2 text-xs rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-violet-500 focus:ring-violet-500">
                            <div class="grid grid-cols-7 gap-1 max-h-52 overflow-y-auto pr-1">
                                <template x-for="icon in filteredIcons" :key="icon">
                                    <button type="button"
                                        @click="selectIcon(i, icon)"
                                        :title="icon"
                                        class="p-2 rounded-lg flex items-center justify-center transition group"
                                        :class="item.icon === icon
                                            ? 'bg-violet-100 dark:bg-violet-900/40 text-violet-600 ring-2 ring-violet-400'
                                            : 'hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300'">
                                        <i :data-lucide="icon" class="w-4 h-4 pointer-events-none"></i>
                                    </button>
                                </template>
                                <template x-if="filteredIcons.length === 0">
                                    <div class="col-span-7 py-4 text-center text-xs text-slate-400">Ikon tidak ditemukan</div>
                                </template>
                            </div>
                            <p class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 text-center" x-text="'Dipilih: ' + item.icon"></p>
                        </div>
                    </div>

                    <!-- Label & Desc inputs -->
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Label Fasilitas</label>
                            <input type="text" :name="'inclusions['+i+'][label]'" x-model="item.label"
                                class="w-full px-3 py-2 text-xs rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: Tiket pesawat PP">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Deskripsi</label>
                            <input type="text" :name="'inclusions['+i+'][desc]'" x-model="item.desc"
                                class="w-full px-3 py-2 text-xs rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Penjelasan singkat...">
                        </div>
                    </div>

                    <!-- Remove button -->
                    <button type="button" @click="removeItem(i)"
                        class="mt-5 p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition shrink-0">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </template>
        </div>

        <button type="button" @click="addItem()"
            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 dark:hover:bg-violet-900/40 rounded-xl border border-violet-200 dark:border-violet-700 transition">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            Tambah Fasilitas
        </button>
        @error('inclusions') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Media & Status -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="image" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Media & Status</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Upload gambar dan atur visibilitas</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Toggle status -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
                <div class="flex items-center gap-3">
                    <i data-lucide="eye" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Tampilkan di landing page</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Paket akan terlihat oleh pengunjung</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $package->is_active ?? true) ? 'true' : 'false' }} }">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" x-model="checked" class="sr-only peer">
                    <div @click="checked = !checked"
                         class="toggle-switch" :class="checked && 'active'">
                        <div class="toggle-dot"></div>
                    </div>
                </label>
            </div>

            <!-- Image upload -->
            <div x-data="{
                preview: '{{ $package->image_url }}',
                handleDrop(e) {
                    e.preventDefault();
                    this.$refs.fileInput.files = e.dataTransfer.files;
                    this.handleFile(e.dataTransfer.files[0]);
                    this.$refs.zone.classList.remove('dragover');
                },
                handleFile(file) {
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => this.preview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                }
            }">
                <div x-ref="zone"
                     class="upload-zone relative p-6 text-center cursor-pointer"
                     @dragover.prevent="$refs.zone.classList.add('dragover')"
                     @dragleave.prevent="$refs.zone.classList.remove('dragover')"
                     @drop.prevent="handleDrop($event)"
                     @click="$refs.fileInput.click()">

                    <!-- Preview -->
                    <template x-if="preview">
                        <div class="relative inline-block mb-3">
                            <img :src="preview" class="w-32 h-32 object-cover rounded-xl shadow-sm border border-slate-200 dark:border-slate-600">
                            <button type="button" @click.stop="preview = ''; $refs.fileInput.value = ''"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </template>

                    <template x-if="!preview">
                        <div class="flex flex-col items-center gap-2 py-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Drag & drop gambar di sini</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">atau klik untuk memilih file</p>
                            </div>
                        </div>
                    </template>

                    <input x-ref="fileInput" type="file" id="image" name="image" accept="image/*" class="hidden"
                           @change="handleFile($event.target.files[0])">
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Format JPG/PNG/WEBP, maksimal 2MB.</p>
                @error('image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Brochure PDF upload -->
            <div class="form-group border-t border-slate-100 dark:border-slate-750/50 pt-5 mt-5">
                <label for="brochure" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Brosur Paket (PDF)') }}</label>
                <input type="file" id="brochure" name="brochure" accept="application/pdf"
                    class="block w-full text-xs text-slate-500
                           file:mr-4 file:py-2.5 file:px-4
                           file:rounded-xl file:border-0
                           file:text-xs file:font-semibold
                           file:bg-blue-50 file:text-blue-700
                           hover:file:bg-blue-100
                           dark:file:bg-blue-900/30 dark:file:text-blue-400
                           transition cursor-pointer
                           border border-slate-200 dark:border-slate-600 rounded-xl p-1 bg-white dark:bg-slate-800">
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Hanya format PDF, maksimal 5MB.</p>
                @error('brochure') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror

                @if ($package->brochure)
                    <div class="mt-3.5 flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-150 dark:border-slate-600 w-fit">
                        <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i>
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Brosur aktif:</span>
                        <a href="{{ $package->brochure_url }}" target="_blank" class="text-xs font-extrabold text-blue-600 hover:text-blue-700 hover:underline">
                            Lihat Brosur PDF
                        </a>
                    </div>
                @endif
            </div>

            <!-- Brochure Image upload -->
            <div class="form-group border-t border-slate-100 dark:border-slate-750/50 pt-5 mt-5">
                <label for="brochure_image" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Gambar Brosur A4') }}</label>
                <input type="file" id="brochure_image" name="brochure_image" accept="image/*"
                    class="block w-full text-xs text-slate-500
                           file:mr-4 file:py-2.5 file:px-4
                           file:rounded-xl file:border-0
                           file:text-xs file:font-semibold
                           file:bg-amber-50 file:text-amber-700
                           hover:file:bg-amber-100
                           dark:file:bg-amber-900/30 dark:file:text-amber-400
                           transition cursor-pointer
                           border border-slate-200 dark:border-slate-600 rounded-xl p-1 bg-white dark:bg-slate-800">
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Format JPG/PNG/WEBP, maksimal 5MB. Tampilan A4 di halaman detail paket.</p>
                @error('brochure_image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror

                @if ($package->brochure_image_url)
                    <div class="mt-3.5 flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-150 dark:border-slate-600 w-fit">
                        <i data-lucide="image" class="w-4 h-4 text-amber-500"></i>
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Gambar brosur aktif:</span>
                        <a href="{{ $package->brochure_image_url }}" target="_blank" class="text-xs font-extrabold text-amber-600 hover:text-amber-700 hover:underline">
                            Lihat Gambar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
