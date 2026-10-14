<?php $this->load->view('template/header'); ?>

<!-- ============================================== -->
<!-- TAMBAHAN ASSETS KHUSUS HALAMAN KALKULATOR      -->
<!-- ============================================== -->

<!-- Tailwind CSS (via CDN) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    // Warna Primary Cokelat Gelap #2E1E04
                    primary: {
                        50: '#f8f6f3',
                        100: '#ede8e1',
                        200: '#d5cabc',
                        300: '#bba792',
                        400: '#9e8166',
                        500: '#5a3d16',
                        600: '#2E1E04', // Warna Cokelat Gelap
                        700: '#211503',
                        800: '#150d02',
                    },
                    // Warna Emas khusus untuk teks highlight
                    gold: {
                        DEFAULT: '#E1A626',
                        light: '#f2d8a8',
                        dark: '#c48f1f'
                    },
                    sand: {
                        50: '#faf7f2', 100: '#f3ede1', 200: '#e6d9c3', 300: '#d4be9a',
                    },
                },
            }
        }
    }
</script>

<style>
    /* 1. Teks "Angsuran" TETAP EMAS #E1A626 */
    .kalkulator-wrapper .gold-text {
        background: linear-gradient(135deg, #E1A626 0%, #f2d8a8 50%, #c48f1f 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* 2. Garis dekorasi di bawah judul berwarna Emas */
    .kalkulator-wrapper .deco-line {
        background: linear-gradient(90deg, transparent, #E1A626, transparent);
    }

    .kalkulator-wrapper input[type=number]::-webkit-inner-spin-button, 
    .kalkulator-wrapper input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .kalkulator-wrapper input[type=number] {
        -moz-appearance: textfield;
    }
    .kalkulator-wrapper input:focus { outline: none; }
    
    .kalkulator-wrapper .number-ui {
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.025em;
    }

    /* Custom Scrollbar untuk Grid Card */
    .kalkulator-scrollbar::-webkit-scrollbar { width: 5px; }
    .kalkulator-scrollbar::-webkit-scrollbar-track { background: #f8f6f3; border-radius: 6px; margin: 4px 0; }
    .kalkulator-scrollbar::-webkit-scrollbar-thumb { background: #d5cabc; border-radius: 6px; }
    .kalkulator-scrollbar::-webkit-scrollbar-thumb:hover { background: #9e8166; }
</style>

<!-- ============================================== -->
<!-- MULAI KONTEN HTML                              -->
<!-- ============================================== -->

<!-- Wrapper Utama Kalkulator -->
<div class="kalkulator-wrapper py-6 md:py-10 lg:py-12 px-4 sm:px-6 lg:px-8 text-slate-700 flex items-center justify-center min-h-[85vh]">
    
    <!-- Main Card Container -->
    <div class="max-w-6xl w-full bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100 relative">
        
        <!-- Header Section -->
        <div class="px-5 pt-16 pb-8 md:px-8 md:pt-20 md:pb-10 lg:pt-16 lg:pb-12 text-center relative overflow-hidden bg-white">
            <div class="absolute -top-10 -right-10 w-48 h-48 md:w-64 md:h-64 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60"></div>
            <div class="absolute -bottom-10 -left-10 w-48 h-48 md:w-64 md:h-64 bg-sand-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            
            <div class="relative z-10">
                <p class="text-[10px] md:text-xs uppercase tracking-[.38em] text-primary-500 font-semibold mb-2 md:mb-3">Simulasi</p>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-medium text-slate-800 leading-tight">
                    ANGSURAN <em class="gold-text">Umrah</em>
                </h1>
            </div>
        </div>

        <!-- Form & Result Section -->
        <!-- Dinamis padding dan gap: Rapat di tablet/laptop kecil, Lega di layar lebar -->
        <div class="p-5 md:p-8 lg:p-8 xl:p-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 xl:gap-10 bg-white border-t border-slate-100 relative z-10">
            
            <!-- Left Column: Input Form (5 cols) -->
            <div class="lg:col-span-5 space-y-6 md:space-y-8">
                <div>
                    <h2 class="text-lg md:text-xl font-semibold text-slate-800 mb-1">Detail Pembiayaan</h2>
                    <p class="text-xs md:text-sm text-slate-500 font-light mb-4 md:mb-5">Masukkan data nominal dan jangka waktu.</p>
                    
                    <div class="space-y-4 md:space-y-5">
                        
                        <!-- Harga Paket Input -->
                        <div>
                            <label for="hargaPaket" class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Harga Paket Umrah</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-semibold text-xs md:text-sm">Rp</span>
                                </div>
                                <input type="text" id="hargaPaket" class="focus:ring-2 focus:ring-primary-400 focus:border-primary-400 block w-full pl-10 md:pl-12 pr-3 md:pr-4 py-2.5 lg:py-3 text-sm md:text-base font-bold text-slate-800 border-slate-200 rounded-lg md:rounded-xl bg-slate-50 shadow-sm transition-all duration-300 outline-none number-ui" placeholder="0" value="34.500.000">
                            </div>
                        </div>

                        <!-- DP Input -->
                        <div>
                            <label for="dp" class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Down Payment / DP</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-semibold text-xs md:text-sm">Rp</span>
                                </div>
                                <input type="text" id="dp" class="focus:ring-2 focus:ring-primary-400 focus:border-primary-400 block w-full pl-10 md:pl-12 pr-3 md:pr-4 py-2.5 lg:py-3 text-sm md:text-base font-bold text-slate-800 border-slate-200 rounded-lg md:rounded-xl bg-slate-50 shadow-sm transition-all duration-300 outline-none number-ui" placeholder="0" value="8.625.000">
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px bg-slate-100 my-3 md:my-4 w-full"></div>

                        <!-- Grid untuk Tenor & Margin -->
                        <div class="grid grid-cols-2 gap-3 lg:gap-4">
                            <!-- Tenor Input -->
                            <div>
                                <label for="tenor" class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Tenor</label>
                                <div class="relative group">
                                    <input type="number" id="tenor" class="focus:ring-2 focus:ring-primary-400 focus:border-primary-400 block w-full pl-3 md:pl-4 pr-12 md:pr-14 py-2.5 lg:py-3 text-sm md:text-base font-bold text-slate-800 border-slate-200 rounded-lg md:rounded-xl bg-slate-50 shadow-sm transition-all duration-300 outline-none number-ui" placeholder="12" value="12" min="1">
                                    <div class="absolute inset-y-0 right-0 pr-3 md:pr-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-semibold text-xs md:text-sm">Bln</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Margin Input -->
                            <div>
                                <label for="margin" class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Margin</label>
                                <div class="relative group">
                                    <input type="number" id="margin" step="0.001" class="focus:ring-2 focus:ring-primary-400 focus:border-primary-400 block w-full pl-3 md:pl-4 pr-14 md:pr-16 py-2.5 lg:py-3 text-sm md:text-base font-bold text-slate-800 border-slate-200 rounded-lg md:rounded-xl bg-slate-50 shadow-sm transition-all duration-300 outline-none number-ui" placeholder="15" value="15">
                                    <div class="absolute inset-y-0 right-0 pr-3 md:pr-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-semibold text-xs md:text-sm">% / Thn</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Summary Box (Mengecil di layar kecil) -->
                <div class="bg-primary-50 p-4 md:p-5 lg:p-6 rounded-xl md:rounded-[1.5rem] border border-primary-200 shadow-sm relative overflow-hidden mt-4 md:mt-6">
                    <div class="absolute left-0 top-0 bottom-0 w-1 md:w-1.5 bg-gradient-to-b from-primary-400 to-primary-600"></div>
                    
                    <div class="space-y-3 md:space-y-4 relative z-10 pl-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs md:text-sm font-semibold text-slate-700">Total Down Payment</span>
                            <span id="totalDPLabel" class="text-base md:text-lg font-extrabold text-slate-800 number-ui">Rp 0</span>
                        </div>
                        <div class="border-t border-primary-200/60 border-dashed"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs md:text-sm font-semibold text-slate-700">Sisa Pokok</span>
                            <span id="sisaPembiayaanLabel" class="text-lg md:text-xl font-extrabold text-primary-600 number-ui">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Results (7 cols) -->
            <div class="lg:col-span-7 flex flex-col mt-6 lg:mt-0">
                
                <!-- Rangkuman Header -->   
                <div class="bg-primary-600 rounded-xl md:rounded-[1.5rem] px-6 py-7 lg:px-8 lg:py-8 shadow-xl shadow-primary-600/20 mb-5 md:mb-6 flex flex-col sm:flex-row items-center justify-between relative overflow-hidden gap-5 sm:gap-4">
                    <!-- Efek Cahaya Latar -->
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-10 -bottom-10 w-24 h-24 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
                    <!-- Kiri: Angsuran (Vertically Centered) -->
                    <div class="relative z-10 flex flex-col justify-center text-center sm:text-left w-full sm:w-auto pt-1 sm:pt-0">
                        <p class="text-primary-200 text-xs md:text-sm font-semibold tracking-widest uppercase mb-2">Angsuran per Bulan</p>
                        <!-- Ukuran font dikecilkan dari 5xl ke 4xl (lg), 4xl ke 3xl (sm), dan 3xl ke 2xl (mobile) -->
                        <p id="cicilanResult" class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white number-ui drop-shadow-md leading-none">Rp 0,00</p>
                    </div>
                    <!-- Kanan: Tenor -->
                    <div class="relative z-10 flex flex-col justify-center items-center sm:items-end text-center sm:text-right bg-primary-700/50 border border-primary-500/50 rounded-xl px-5 py-3.5 md:px-6 md:py-4 w-full sm:w-auto backdrop-blur-sm">
                        <p class="text-primary-200 text-[10px] md:text-xs font-semibold tracking-wider uppercase mb-1.5">Total Tenor</p>
                        <p id="tenorDisplay" class="text-lg md:text-xl font-bold text-white leading-none">12 Bulan</p>
                    </div>
                </div>

                <div class="flex justify-between items-end mb-3 md:mb-4">
                    <h2 class="text-base md:text-lg font-semibold text-slate-800">Jadwal Pembayaran</h2>
                    <span id="marginDisplay" class="text-[10px] md:text-xs font-bold text-primary-600 bg-primary-50 px-2.5 py-1 md:px-3 md:py-1 rounded-full border border-primary-100">15% Margin</span>
                </div>
                
                <!-- GRID KARTU (Scrollable, Padat di layar kecil, Responsif) -->
                <div class="flex-1 rounded-xl md:rounded-[1.5rem] border border-slate-100 bg-slate-50 p-3 md:p-4 lg:p-5">
                    <!-- max-h disesuaikan agar tidak over-scroll di laptop kecil -->
                    <div id="installmentGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 md:gap-3 max-h-[320px] lg:max-h-[380px] xl:max-h-[420px] overflow-y-auto kalkulator-scrollbar pr-1.5 pb-2">
                        <!-- Kartu akan di-generate otomatis oleh JavaScript di sini -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer / Keterangan -->
        <div class="bg-slate-50 px-5 md:px-8 xl:px-10 py-5 lg:py-6 border-t border-slate-100 rounded-b-[1.5rem] md:rounded-b-[2rem]">
            <div class="flex items-start gap-2.5 md:gap-3">
                <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 md:w-3.5 md:h-3.5">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs md:text-sm font-semibold text-slate-800 mb-1">Panduan Penggunaan Kalkulator:</h4>
                    <ul class="text-[11px] md:text-xs text-slate-500 font-light space-y-1 md:space-y-1.5 leading-relaxed list-decimal list-inside">
                        <li>Masukkan nominal <strong>Harga Paket Umrah</strong> yang ingin Anda ikuti.</li>
                        <li>Tentukan besaran <strong>Down Payment (DP)</strong> yang akan disetorkan di awal.</li>
                        <li>Ketik jangka waktu <strong>Tenor</strong> (dalam hitungan bulan) sesuai dengan kemampuan Anda.</li>
                        <li>Sesuaikan <strong>Margin Rate</strong> (% per tahun) jika diperlukan.</li>
                        <li>Sistem akan secara otomatis menampilkan <strong>Sisa Pokok</strong>, <strong>Angsuran per Bulan</strong>, dan <strong>Jadwal Pembayaran</strong> di sebelah kanan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- TEMPLATE FOOTER & SCRIPT DARI CODEIGNITER      -->
<!-- ============================================== -->
<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<!-- LOGIKA JAVASCRIPT KALKULATOR -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- DOM Elements ---
        const inputHarga = document.getElementById('hargaPaket');
        const inputDP = document.getElementById('dp');
        const inputTenor = document.getElementById('tenor');
        const inputMargin = document.getElementById('margin');
        
        const labelTotalDP = document.getElementById('totalDPLabel');
        const labelSisa = document.getElementById('sisaPembiayaanLabel');
        
        const labelCicilan = document.getElementById('cicilanResult');
        const labelTenorDisplay = document.getElementById('tenorDisplay');
        const labelMarginDisplay = document.getElementById('marginDisplay');
        const gridContainer = document.getElementById('installmentGrid');

        // --- Helper Functions ---
        function formatRupiah(value) {
            let number_string = value.toString().replace(/[^,\d]/g, '');
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah;
        }

        function formatOutputDecimal(value) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        }

        function unformatRupiah(str) {
            return parseInt(str.toString().replace(/\./g, '')) || 0;
        }

        // --- Core Calculation ---
        function calculateSimulasi() {
            // Get inputs
            const hargaPaket = unformatRupiah(inputHarga.value);
            const totalDP = unformatRupiah(inputDP.value);
            const tenor = parseInt(inputTenor.value) || 1; 
            const marginInput = parseFloat(inputMargin.value) || 0; 
            
            // Calculate remaining balance
            let sisaPembiayaan = hargaPaket - totalDP;
            if (sisaPembiayaan < 0) sisaPembiayaan = 0;

            // Margin rate calculations
            const marginTahunan = marginInput / 100;
            const marginBulanan = marginTahunan / 12;

            // Update UI Summary Box
            labelTotalDP.textContent = 'Rp ' + formatRupiah(totalDP);
            labelSisa.textContent = 'Rp ' + formatRupiah(sisaPembiayaan);
            
            // Update Card Info
            labelTenorDisplay.textContent = tenor + ' Bulan';
            labelMarginDisplay.textContent = marginInput + '% Margin';

            // Calculate Installment
            let totalCicilan = 0;
            if (sisaPembiayaan > 0) {
                const pokokPerBulan = sisaPembiayaan / tenor;
                const marginPerBulan = sisaPembiayaan * marginBulanan;
                totalCicilan = pokokPerBulan + marginPerBulan;

                labelCicilan.textContent = 'Rp ' + formatOutputDecimal(totalCicilan);
            } else {
                labelCicilan.textContent = 'Rp 0,00';
            }

            // Generate Grid Cards
            renderGridCards(tenor, totalCicilan, sisaPembiayaan);
        }

        function renderGridCards(tenor, cicilan, sisa) {
            let html = '';
            
            if (sisa <= 0) {
                // Tampilan jika DP menutupi harga paket (Lunas)
                html = `
                <div class="col-span-full py-8 text-center flex flex-col items-center justify-center opacity-60">
                    <svg class="w-10 h-10 md:w-12 md:h-12 text-primary-500 mb-2 md:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm md:text-base text-slate-600 font-bold">Pembayaran Lunas</p>
                    <p class="text-[10px] md:text-xs text-slate-400 mt-1">Tidak ada angsuran bulanan.</p>
                </div>
                `;
            } else {
                // Loop membuat kartu untuk setiap bulan
                const cicilanStr = formatOutputDecimal(cicilan);
                for(let i = 1; i <= tenor; i++) {
                    html += `
                    <div class="bg-white border border-slate-200 p-2.5 md:p-3 lg:p-4 rounded-xl md:rounded-[1rem] shadow-sm hover:shadow-md hover:border-primary-500 transition-all duration-300 flex flex-col items-center justify-center text-center group cursor-default relative overflow-hidden">
                        <div class="absolute inset-0 bg-primary-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                        
                        <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] md:text-xs font-bold mb-1.5 md:mb-2 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300 relative z-10">
                            ${i}
                        </div>
                        <p class="text-[9px] md:text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-0.5 md:mb-1 relative z-10">Bulan ${i}</p>
                        <p class="text-xs md:text-sm font-extrabold text-slate-800 group-hover:text-primary-700 transition-colors duration-300 number-ui relative z-10 line-clamp-1">
                            Rp ${cicilanStr}
                        </p>
                    </div>
                    `;
                }
            }
            
            gridContainer.innerHTML = html;
        }

        // --- Event Listeners ---
        function handleCurrencyInput(e) {
            const startPos = this.selectionStart;
            const oldLength = this.value.length;

            this.value = formatRupiah(this.value);

            const newLength = this.value.length;
            this.setSelectionRange(startPos + (newLength - oldLength), startPos + (newLength - oldLength));

            calculateSimulasi();
        }

        // Trigger calculation on input
        inputHarga.addEventListener('input', handleCurrencyInput);
        inputDP.addEventListener('input', handleCurrencyInput);
        
        // Prevent negative tenor and trigger calculation
        inputTenor.addEventListener('input', function() {
            if (this.value < 1 && this.value !== "") this.value = 1;
            // Limit tenor display agar browser tidak hang jika user input 99999
            if (this.value > 120) this.value = 120; 
            calculateSimulasi();
        });

        // Trigger calculation on margin change
        inputMargin.addEventListener('input', calculateSimulasi);

        // Init calculation on load
        inputHarga.value = formatRupiah(inputHarga.value);
        inputDP.value = formatRupiah(inputDP.value);
        calculateSimulasi();
    });
</script>