@extends($layout)

@section('title', 'Panduan Pengguna')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>
    .content {
        padding: 100px 40px 30px 40px !important;
    }

    .guide-container {
        display: flex;
        gap: 25px;
    }

    /* Sidebar */
    .guide-sidebar {
        width: 260px;
        background: #ffffff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.07);
        height: fit-content;
    }

    .guide-sidebar h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #7b0000;
    }

    .guide-menu button {
        width: 100%;
        text-align: left;
        background: #f7f7f7;
        border: none;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 8px;
        font-size: 14px;
        transition: .2s;
        cursor: pointer;
    }

    .guide-menu button.active {
        background: #7b0000;
        color: white;
        font-weight: 600;
    }

    .guide-menu button:hover {
        background: #ececec;
    }

    /* Konten kanan FIXED */
    .guide-content {
        flex: 1;
        background: #fff;
        padding: 0; /* ❗ penting: hilangkan padding agar header nempel ke atas */
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.07);
    }

    .guide-header {
        background: #7b0000;
        padding: 18px 25px;
        color: white;
        font-size: 20px;
        font-weight: 700;
        border-radius: 15px 15px 0 0;
        margin: 0;
    }

    /* Wrapper isi konten */
    .guide-inner {
        padding: 25px; /* pindahan padding dari guide-content */
    }

    .accordion-item {
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .accordion-body {
    color: #6B6B6B !important;
    background-color: #F5F5F5; /* kalau mau background sama seperti gambar */
}


    /* Hilangkan background biru saat accordion dibuka */
.accordion-button:not(.collapsed) {
    background-color: #ffffff !important; 
    color: #000 !important;               
    box-shadow: none !important;           
}

.accordion-button:focus {
    box-shadow: none !important;
    outline: none !important;
}

.accordion-body ul {
    padding-left: 20px;
}

.accordion-body li {
    margin-bottom: 6px;
    line-height: 1.6;
}

</style>

<div class="guide-container">

    {{-- SIDEBAR --}}
    <div class="guide-sidebar">
        <h4>Kategori Panduan</h4>

        <div class="guide-menu">
            <button class="active" onclick="switchGuide(event, 'informasi')">Informasi Umum</button>
            <button onclick="switchGuide(event, 'data-master')">Manajemen Data Master</button>
            <button onclick="switchGuide(event, 'aset')">Manajemen Aset</button>
            <button onclick="switchGuide(event, 'sistem')">Manajemen Sistem</button>
            <button onclick="switchGuide(event, 'akun')">Manajemen Akun</button>
        </div>
    </div>

    {{-- KONTEN --}}
    <div class="guide-content">

        {{-- HEADER --}}
        <div class="guide-header" id="guideTitle">Informasi Umum</div>

        <div class="guide-inner">

            {{-- INFORMASI UMUM --}}
            <div id="informasi" class="guide-section">
                <div class="accordion">

                    {{-- Item 1 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#infoOne">
                                Apa itu SIMASTER?
                            </button>
                        </h2>
                        <div id="infoOne" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                SIMASTER (Sistem Informasi Manajemen Aset Terpadu) adalah platform berbasis web 
                                untuk memfasilitasi pengelolaan aset Teknologi Informasi (TI) secara komprehensif, meliputi 
                                inventaris Server, Website, dan kegiatan Pemeliharaan, di lingkungan Pusat Data dan 
                                Informasi (Pusdatin) Kementerian Pertahanan.
                            </div>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#infoTwo">
                                SIMASTER bertujuan untuk
                            </button>
                        </h2>
                        <div id="infoTwo" class="accordion-collapse collapse">
                           <div class="accordion-body">
                                <ul>
                                    <li>Mengelola inventaris aset TI secara terpusat, akurat, dan real-time.</li>
                                    <li>Memantau status dan kondisi aset untuk memastikan ketersediaan layanan.</li>
                                    <li>Menjadwalkan dan mencatat riwayat pemeliharaan.</li>
                                    <li>Menghasilkan laporan dan analisis pendukung keputusan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#infoThree">
                                Fitur-Fitur Utama
                            </button>
                        </h2>
                        <div id="infoThree" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                SIMASTER terbagi menjadi beberapa modul fungsional utama:
                                <ul class="mt-2">
                                    <li><strong>Data Master:</strong> Pengelolaan data referensi seperti Bidang, Satuan Kerja (Satker), dan Rak Server.</li>
                                    <li><strong>Manajemen Aset:</strong> Inventarisasi dan pengelolaan detail aset Server dan Website.</li>
                                    <li><strong>Manajemen Pemeliharaan:</strong> Penjadwalan, pencatatan, dan pemantauan aktivitas pemeliharaan aset.</li>
                                    <li><strong>Manajemen Sistem:</strong> Pengelolaan akun Pengguna, penentuan hak akses, dan pemantauan Log Aktivitas.</li>
                                    <li><strong>Sistem Monitoring:</strong> Dashboard visual, Log Aktivitas, dan Laporan Aset.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Item 4 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#infoFour">
                                Hak Akses Pengguna
                            </button>
                        </h2>
                        <div id="infoFour" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                SIMASTER menerapkan sistem hak akses bertingkat untuk membatasi fungsionalitas sesuai peran pengguna:
                                
                                <p class="mt-2 mb-1"><strong>1. Super Admin</strong></p>
                                <ul>
                                    <li>Hak akses penuh (Create, Read, Update, Delete) ke seluruh fitur dan data sistem.</li>
                                    <li>Bertanggung jawab mengelola Data Master, seluruh aset, dan Akun Pengguna.</li>
                                </ul>

                                <p class="mt-3 mb-1"><strong>2. Admin Bidang</strong></p>
                                <ul>
                                    <li>Hak akses (CRUD) hanya pada aset yang berada di bawah lingkup Bidang yang menjadi tanggung jawabnya.</li>
                                    <li>Dapat melihat laporan yang spesifik untuk Bidangnya.</li>
                                </ul>

                                <p class="mt-3 mb-1"><strong>3. Pimpinan</strong></p>
                                <ul>
                                    <li>Hak akses terbatas (View Only).</li>
                                    <li>Dirancang untuk memantau Dashboard monitoring.</li>
                                    <li>Dapat melihat seluruh laporan aset tanpa kemampuan mengubah data.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- SECTION LAIN --}}
            <div id="data-master" class="guide-section" style="display:none;">
                <div class="accordion" id="dataMasterAccordion">

    {{-- Item 1 --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#dmOne">
                Manajemen Data Master
            </button>
        </h2>
        <div id="dmOne" class="accordion-collapse collapse show">
            <div class="accordion-body">
                Modul ini digunakan untuk mengelola data referensi yang dibutuhkan dalam proses inventarisasi aset TI.
                <br>Data yang dikelola mencakup Bidang, Satuan Kerja, dan Rak Server.
            </div>
        </div>
    </div>

    {{-- Item 2: Cara Mengelola Bidang --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#dmTwo">
                Cara Mengelola Bidang
            </button>
        </h2>
        <div id="dmTwo" class="accordion-collapse collapse">
            <div class="accordion-body">

                <p><strong>Menambah Bidang:</strong></p>
                <ul>
                    <li>Akses menu <strong>Data Master &gt; Bidang</strong>.</li>
                    <li>Klik tombol <strong>Tambah Bidang</strong>.</li>
                    <li>Isi data: Nama Bidang, Singkatan.</li>
                    <li>Klik <strong>Simpan</strong>.</li>
                </ul>

                <p class="mt-3"><strong>Memperbarui Data:</strong></p>
                <ul>
                    <li>Klik ikon <strong>Pensil</strong> pada baris data yang ingin diubah.</li>
                    <li>Perbarui informasi sesuai kebutuhan.</li>
                    <li>Klik <strong>Simpan Perubahan</strong>.</li>
                </ul>

                <p class="mt-3"><strong>Menghapus Data:</strong></p>
                <ul>
                    <li>Klik ikon <strong>Sampah</strong> pada baris data untuk menghapus secara permanen dari sistem.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Item 3: Cara Mengelola Satuan Kerja --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#dmThree">
                Cara Mengelola Satuan Kerja
            </button>
        </h2>
        <div id="dmThree" class="accordion-collapse collapse">
            <div class="accordion-body">

                <p><strong>Menambah Satuan Kerja:</strong></p>
                <ul>
                    <li>Akses menu <strong>Data Master &gt; Satuan Kerja</strong>.</li>
                    <li>Klik tombol <strong>Tambah Satuan Kerja</strong>.</li>
                    <li>Isi data: Nama, Singkatan.</li>
                    <li>Klik <strong>Simpan</strong>.</li>
                </ul>

                <p class="mt-3"><strong>Memperbarui Data:</strong></p>
                <ul>
                    <li>Klik ikon <strong>Pensil</strong> pada baris data yang ingin diubah.</li>
                    <li>Perbarui informasi sesuai kebutuhan.</li>
                    <li>Klik <strong>Simpan Perubahan</strong>.</li>
                </ul>

                <p class="mt-3"><strong>Menghapus Data:</strong></p>
                <ul>
                    <li>Klik ikon <strong>Sampah</strong> untuk menghapus data secara permanen.</li>
                </ul>

            </div>
        </div>
    </div>

    {{-- Item 4: Cara Mengelola Rak Server --}}
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#dmFour">
                Cara Mengelola Rak Server
            </button>
        </h2>
        <div id="dmFour" class="accordion-collapse collapse">
            <div class="accordion-body">

                <p><strong>Menambah Rak Server:</strong></p>
                <ul>
                    <li>Akses menu <strong>Data Master &gt; Rak Server</strong>.</li>
                    <li>Klik tombol <strong>Tambah Rak Server</strong>.</li>
                    <li>Isi data: Nomor Rak, Ruangan, Kapasitas U Slot, dan Keterangan (opsional).</li>
                    <li>Klik <strong>Simpan</strong>.</li>
                </ul>

                <p class="mt-3"><strong>Memperbarui Data:</strong></p>
                <ul>
                    <li>Klik ikon <strong>Pensil</strong> pada baris data yang ingin diubah.</li>
                    <li>Perbarui informasi yang diperlukan.</li>
                    <li>Klik <strong>Simpan Perubahan</strong>.</li>
                </ul>

                <p class="mt-3"><strong>Menghapus Data:</strong></p>
                <ul>
                    <li>Klik ikon <strong>Sampah</strong> untuk menghapus data secara permanen dari sistem.</li>
                </ul>

            </div>
        </div>
    </div>

</div>

            </div>

            <div id="aset" class="guide-section" style="display:none;">
                <p>Isi panduan Manajemen Aset...</p>
            </div>

            <div id="sistem" class="guide-section" style="display:none;">
                <p>Isi panduan Manajemen Sistem...</p>
            </div>

            <div id="akun" class="guide-section" style="display:none;">
                <p>Isi panduan Manajemen Akun...</p>
            </div>

        </div>
    </div>
</div>

<script>
    function switchGuide(event, section) {

        // ganti judul
        document.getElementById("guideTitle").innerText =
            section === "informasi" ? "Informasi Umum" :
            section === "data-master" ? "Manajemen Data Master" :
            section === "aset" ? "Manajemen Aset" :
            section === "sistem" ? "Manajemen Sistem" :
            "Manajemen Akun";

        // sembunyikan semua section
        document.querySelectorAll('.guide-section').forEach(s => s.style.display = "none");

        // tampilkan section yang dipilih
        document.getElementById(section).style.display = "block";

        // update active sidebar
        document.querySelectorAll(".guide-menu button").forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
    }
</script>

@endsection
