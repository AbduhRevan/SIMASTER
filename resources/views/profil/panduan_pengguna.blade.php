@extends('layouts.app')

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
</style>

<div class="guide-container">

    {{-- SIDEBAR --}}
    <div class="guide-sidebar">
        <h4>Kategori Panduan</h4>

        <div class="guide-menu">
            <button class="active" onclick="switchGuide(event, 'informasi')">Informasi Umum</button>
            <button onclick="switchGuide(event, 'data-master')">Data Master</button>
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
                                Bla bla bla
                            </div>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#infoTwo">
                                Fitur Utama
                            </button>
                        </h2>
                        <div id="infoTwo" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Isi fitur utama...
                            </div>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#infoThree">
                                Hak Akses
                            </button>
                        </h2>
                        <div id="infoThree" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Isi hak akses...
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- SECTION LAIN --}}
            <div id="data-master" class="guide-section" style="display:none;">
                <p>Isi panduan Data Master...</p>
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
            section === "data-master" ? "Data Master" :
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
