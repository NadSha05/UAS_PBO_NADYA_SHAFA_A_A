<?php
// Memanggil file koneksi dan semua definisi kelas
require_once 'koneksi.php';
require_once 'Mahasiswa.php';
require_once 'MahasiswaMandiri.php';
require_once 'MahasiswaBidikmisi.php';
require_once 'MahasiswaPrestasi.php';

// Proses Simpan Data Baru Jika Form Disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_data'])) {
    $nama = $_POST['nama_mahasiswa'];
    $nim = $_POST['nim'];
    $semester = $_POST['semester'];
    $tarif = (float)$_POST['tarif_ukt_nominal'];
    $pembiayaan = $_POST['jenis_pembiayaan'];

    // Inisialisasi kolom spesifik
    $gol_ukt = ($_POST['jenis_pembiayaan'] == 'mandiri') ? $_POST['golongan_ukt'] : null;
    $wali = ($_POST['jenis_pembiayaan'] == 'mandiri') ? $_POST['nama_wali'] : null;
    $kip = ($_POST['jenis_pembiayaan'] == 'bidikmisi') ? $_POST['nomor_kip_kuliah'] : null;
    $dana = ($_POST['jenis_pembiayaan'] == 'bidikmisi') ? (float)$_POST['dana_saku_subsidi'] : null;
    $instansi = ($_POST['jenis_pembiayaan'] == 'prestasi') ? $_POST['nama_instansi_beasiswa'] : null;
    $ipk = ($_POST['jenis_pembiayaan'] == 'prestasi') ? (float)$_POST['minimal_ipk_syarat'] : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO tabel_mahasiswa (nama_mahasiswa, nim, semester, tarif_ukt_nominal, jenis_pembiayaan, golongan_ukt, nama_wali, nomor_kip_kuliah, dana_saku_subsidi, nama_instansi_beasiswa, minimal_ipk_syarat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $nim, $semester, $tarif, $pembiayaan, $gol_ukt, $wali, $kip, $dana, $instansi, $ipk]);
        $success_msg = "Data mahasiswa berhasil ditambahkan!";
    } catch (PDOException $e) {
        $error_msg = "Gagal menambah data: " . $e->getMessage();
    }
}

// Mengambil data dari database tabel_mahasiswa secara keseluruhan
try {
    $stmt = $pdo->query("SELECT * FROM tabel_mahasiswa ORDER BY id_mahasiswa DESC");
    $semua_mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Akademik - Pembayaran Kuliah</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        p.subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-top: 0;
            margin-bottom: 30px;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        
        /* Notifikasi */
        .alert-success { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 15px; }

        /* Tombol Aksi & Pencarian */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
            flex-wrap: wrap;
        }
        .btn-tambah {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-tambah:hover { background-color: #2ecc71; }
        
        .search-box {
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            width: 300px;
            font-size: 0.95rem;
        }

        /* Form Input Modal (Sembunyikan/Tampilkan via JS) */
        .form-wrapper {
            display: none;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            animation: fadeIn 0.4s;
        }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 15px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 0.85rem; font-weight: bold; color: #475569; }
        .form-group input, .form-group select { padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; }
        .form-row-full { grid-column: 1 / -1; }

        /* Menu Navigasi / Tombol Tab */
        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e1e8ed;
            padding-bottom: 15px;
        }
        .tab-btn {
            background-color: #e2e8f0;
            color: #4a5568;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .tab-btn:hover { background-color: #cbd5e1; }
        .tab-btn.active.mandiri { background-color: #3498db; color: white; }
        .tab-btn.active.bidikmisi { background-color: #2ecc71; color: white; }
        .tab-btn.active.prestasi { background-color: #e74c3c; color: white; }

        .category-section {
            display: none; 
            padding: 25px;
            border-radius: 8px;
            background-color: #fafbfc;
            animation: fadeIn 0.4s;
        }
        .category-section.active { display: block; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .mandiri-border { border: 1px solid #3498db; border-left: 6px solid #3498db; }
        .bidikmisi-border { border: 1px solid #2ecc71; border-left: 6px solid #2ecc71; }
        .prestasi-border { border: 1px solid #e74c3c; border-left: 6px solid #e74c3c; }

        .category-title {
            font-size: 1.5rem;
            margin-top: 0; margin-bottom: 20px; padding-bottom: 5px;
            border-bottom: 2px solid #eee;
        }
        .mandiri-title { color: #3498db; }
        .bidikmisi-title { color: #2ecc71; }
        .prestasi-title { color: #e74c3c; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px; margin-bottom: 20px;
            font-size: 0.95rem;
        }
        th, td { border: 1px solid #d1d8e0; padding: 12px 15px; text-align: left; }
        th { background-color: #eef2f7; color: #1a365d; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        
        /* Highlight hasil pencarian */
        .highlight { background-color: #fff3cd !important; }

        .total-tagihan { font-weight: bold; color: #e74c3c; }
        .spec-box {
            background-color: #fff; border: 1px dashed #cbd5e1;
            padding: 15px; margin-top: 15px; border-radius: 6px;
            font-family: monospace; white-space: pre-wrap; color: #475569;
        }
        h4 { margin-top: 25px; margin-bottom: 10px; color: #2c3e50; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h1>Sistem Informasi Akademik</h1>
    <p class="subtitle">Dashboard Daftar Registrasi Pembayaran Kuliah Mahasiswa</p>

    <?php if (isset($success_msg)): ?>
        <div class="alert-success"><?= $success_msg; ?></div>
    <?php endif; ?>
    <?php if (isset($error_msg)): ?>
        <div class="alert-danger"><?= $error_msg; ?></div>
    <?php endif; ?>

    <div class="action-bar">
        <button class="btn-tambah" onclick="toggleForm()">+ Tambah Data Mahasiswa</button>
        <input type="text" id="searchBar" class="search-box" placeholder="Cari berdasarkan Nama atau NIM..." onkeyup="searchTable()">
    </div>

    <div id="formTambahData" class="form-wrapper">
        <h3 style="margin-top:0; color: #2c3e50;">Form Pendaftaran Mahasiswa Baru</h3>
        <form action="" method="POST">
            <input type="hidden" name="tambah_data" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Mahasiswa:</label>
                    <input type="text" name="nama_mahasiswa" required placeholder="Contoh: Budi Santoso">
                </div>
                <div class="form-group">
                    <label>NIM:</label>
                    <input type="text" name="nim" required placeholder="Contoh: 202301001">
                </div>
                <div class="form-group">
                    <label>Semester:</label>
                    <input type="number" name="semester" required min="1" max="14" value="1">
                </div>
                <div class="form-group">
                    <label>Tarif UKT Nominal (Rp):</label>
                    <input type="number" step="0.01" name="tarif_ukt_nominal" required value="0">
                </div>
                <div class="form-group">
                    <label>Jenis Pembiayaan:</label>
                    <select name="jenis_pembiayaan" id="jenisPembiayaan" onchange="changeFormFields()" required>
                        <option value="mandiri">Mandiri</option>
                        <option value="bidikmisi">Bidikmisi</option>
                        <option value="prestasi">Prestasi</option>
                    </select>
                </div>
                
                <div class="form-group dynamic-field mandiri-field">
                    <label>Golongan UKT:</label>
                    <input type="text" name="golongan_ukt" placeholder="Contoh: I, II, III">
                </div>
                <div class="form-group dynamic-field mandiri-field">
                    <label>Nama Wali:</label>
                    <input type="text" name="nama_wali" placeholder="Nama orang tua/wali">
                </div>

                <div class="form-group dynamic-field bidikmisi-field" style="display:none;">
                    <label>Nomor KIP Kuliah:</label>
                    <input type="text" name="nomor_kip_kuliah" placeholder="Nomor KIP K">
                </div>
                <div class="form-group dynamic-field bidikmisi-field" style="display:none;">
                    <label>Dana Saku Subsidi (Rp):</label>
                    <input type="number" step="0.01" name="dana_saku_subsidi" value="0">
                </div>

                <div class="form-group dynamic-field prestasi-field" style="display:none;">
                    <label>Nama Instansi Beasiswa:</label>
                    <input type="text" name="nama_instansi_beasiswa" placeholder="Contoh: Djarum Foundation">
                </div>
                <div class="form-group dynamic-field prestasi-field" style="display:none;">
                    <label>Minimal IPK Syarat:</label>
                    <input type="number" step="0.01" name="minimal_ipk_syarat" min="0" max="4.0" value="3.0">
                </div>

                <div class="form-group form-row-full">
                    <button type="submit" class="btn-tambah" style="width: 200px;">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>

    <div class="nav-menu">
        <button class="tab-btn active mandiri" onclick="openCategory('kelompok-mandiri')">Mahasiswa Mandiri</button>
        <button class="tab-btn bidikmisi" onclick="openCategory('kelompok-bidikmisi')">Mahasiswa Bidikmisi</button>
        <button class="tab-btn prestasi" onclick="openCategory('kelompok-prestasi')">Mahasiswa Prestasi</button>
    </div>

    <div id="kelompok-mandiri" class="category-section mandiri-border active">
        <h2 class="category-title mandiri-title">Kelompok Kategori: Mahasiswa Mandiri</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Golongan UKT</th>
                    <th>Nama Wali</th>
                    <th>Tarif UKT Asli</th>
                    <th>Tagihan Akhir (Tarif + Ops)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($semua_mahasiswa as $data) {
                    if ($data['jenis_pembiayaan'] == 'mandiri') {
                        $mhs = new MahasiswaMandiri(
                            $data['id_mahasiswa'], $data['nama_mahasiswa'], $data['nim'], 
                            $data['semester'], (float)$data['tarif_ukt_nominal'], 
                            $data['golongan_ukt'], $data['nama_wali']
                        );
                        ?>
                        <tr>
                            <td><?= $mhs->getNim(); ?></td>
                            <td><?= $mhs->getNamaMahasiswa(); ?></td>
                            <td><?= $mhs->getSemester(); ?></td>
                            <td><?= $mhs->getGolonganUkt(); ?></td>
                            <td><?= $mhs->getNamaWali(); ?></td>
                            <td>Rp <?= number_format($mhs->getTarifUktNominal(), 0, ',', '.'); ?></td>
                            <td class="total-tagihan">Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        
        <h4>Spesifikasi Akademik Mahasiswa Mandiri</h4>
        <div class="spec-box">
            <?php
            foreach ($semua_mahasiswa as $data) {
                if ($data['jenis_pembiayaan'] == 'mandiri') {
                    $mhs = new MahasiswaMandiri(
                        $data['id_mahasiswa'], $data['nama_mahasiswa'], $data['nim'], 
                        $data['semester'], (float)$data['tarif_ukt_nominal'], 
                        $data['golongan_ukt'], $data['nama_wali']
                    );
                    $mhs->tampilkanSpesifikasiAkademik();
                }
            }
            ?>
        </div>
    </div>

    <div id="kelompok-bidikmisi" class="category-section bidikmisi-border">
        <h2 class="category-title bidikmisi-title">Kelompok Kategori: Mahasiswa Bidikmisi</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Nomor KIP Kuliah</th>
                    <th>Dana Saku Subsidi</th>
                    <th>Tagihan Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($semua_mahasiswa as $data) {
                    if ($data['jenis_pembiayaan'] == 'bidikmisi') {
                        $mhs = new MahasiswaBidikmisi(
                            $data['id_mahasiswa'], $data['nama_mahasiswa'], $data['nim'], 
                            $data['semester'], (float)$data['tarif_ukt_nominal'], 
                            $data['nomor_kip_kuliah'], (float)$data['dana_saku_subsidi']
                        );
                        ?>
                        <tr>
                            <td><?= $mhs->getNim(); ?></td>
                            <td><?= $mhs->getNamaMahasiswa(); ?></td>
                            <td><?= $mhs->getSemester(); ?></td>
                            <td><?= $mhs->getNomorKipKuliah(); ?></td>
                            <td>Rp <?= number_format($mhs->getDanaSakuSubsidi(), 0, ',', '.'); ?></td>
                            <td class="total-tagihan" style="color: #2ecc71;">Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?> (Gratis)</td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        
        <h4>Spesifikasi Akademik Mahasiswa Bidikmisi</h4>
        <div class="spec-box">
            <?php
            foreach ($semua_mahasiswa as $data) {
                if ($data['jenis_pembiayaan'] == 'bidikmisi') {
                    $mhs = new MahasiswaBidikmisi(
                        $data['id_mahasiswa'], $data['nama_mahasiswa'], $data['nim'], 
                        $data['semester'], (float)$data['tarif_ukt_nominal'], 
                        $data['nomor_kip_kuliah'], (float)$data['dana_saku_subsidi']
                    );
                    $mhs->tampilkanSpesifikasiAkademik();
                }
            }
            ?>
        </div>
    </div>

    <div id="kelompok-prestasi" class="category-section prestasi-border">
        <h2 class="category-title prestasi-title">Kelompok Kategori: Mahasiswa Prestasi</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Instansi Beasiswa</th>
                    <th>Min. IPK Syarat</th>
                    <th>Tarif UKT Asli</th>
                    <th>Tagihan Akhir (Bayar 25%)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($semua_mahasiswa as $data) {
                    if ($data['jenis_pembiayaan'] == 'prestasi') {
                        $mhs = new MahasiswaPrestasi(
                            $data['id_mahasiswa'], $data['nama_mahasiswa'], $data['nim'], 
                            $data['semester'], (float)$data['tarif_ukt_nominal'], 
                            $data['nama_instansi_beasiswa'], (float)$data['minimal_ipk_syarat']
                        );
                        ?>
                        <tr>
                            <td><?= $mhs->getNim(); ?></td>
                            <td><?= $mhs->getNamaMahasiswa(); ?></td>
                            <td><?= $mhs->getSemester(); ?></td>
                            <td><?= $mhs->getNamaInstansiBeasiswa(); ?></td>
                            <td><?= number_format($mhs->getMinimalIpkSyarat(), 2); ?></td>
                            <td>Rp <?= number_format($mhs->getTarifUktNominal(), 0, ',', '.'); ?></td>
                            <td class="total-tagihan">Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        
        <h4>Spesifikasi Akademik Mahasiswa Prestasi</h4>
        <div class="spec-box">
            <?php
            foreach ($semua_mahasiswa as $data) {
                if ($data['jenis_pembiayaan'] == 'prestasi') {
                    $mhs = new MahasiswaPrestasi(
                        $data['id_mahasiswa'], $data['nama_mahasiswa'], $data['nim'], 
                        $data['semester'], (float)$data['tarif_ukt_nominal'], 
                        $data['nama_instansi_beasiswa'], (float)$data['minimal_ipk_syarat']
                    );
                    $mhs->tampilkanSpesifikasiAkademik();
                }
            }
            ?>
        </div>
    </div>

</div>

<script>
// Fungsi Navigasi Tab Menu
function openCategory(categoryId) {
    var sections = document.getElementsByClassName("category-section");
    for (var i = 0; i < sections.length; i++) {
        sections[i].classList.remove("active");
    }

    var buttons = document.getElementsByClassName("tab-btn");
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("active");
    }

    document.getElementById(categoryId).classList.add("active");
    event.currentTarget.classList.add("active");
}

// Fungsi Buka/Tutup Form Input Data
function toggleForm() {
    var form = document.getElementById("formTambahData");
    if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}

// Fungsi Form Input Dinamis Berdasarkan Jenis Pembiayaan
function changeFormFields() {
    var selectedType = document.getElementById("jenisPembiayaan").value;
    
    // Sembunyikan semua field dinamis terlebih dahulu
    var dynamicFields = document.getElementsByClassName("dynamic-field");
    for (var i = 0; i < dynamicFields.length; i++) {
        dynamicFields[i].style.display = "none";
    }
    
    // Tampilkan field yang sesuai dengan select option
    var targetFields = document.getElementsByClassName(selectedType + "-field");
    for (var i = 0; i < targetFields.length; i++) {
        targetFields[i].style.display = "flex";
    }
}

// Fungsi Pencarian Data Tabel (Live Search)
function searchTable() {
    var input, filter, tables, tr, tdName, tdNim, i, j, txtValueName, txtValueNim;
    input = document.getElementById("searchBar");
    filter = input.value.toLowerCase();
    
    // Ambil semua tabel di dalam section kategori
    tables = document.querySelectorAll(".data-table tbody");
    
    tables.forEach(function(tbody) {
        tr = tbody.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            // Kolom ke-0 adalah NIM, Kolom ke-1 adalah Nama
            tdNim = tr[i].getElementsByTagName("td")[0];
            tdName = tr[i].getElementsByTagName("td")[1];
            
            if (tdNim || tdName) {
                txtValueNim = tdNim.textContent || tdNim.innerText;
                txtValueName = tdName.textContent || tdName.innerText;
                
                if (txtValueNim.toLowerCase().indexOf(filter) > -1 || txtValueName.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    tr[i].classList.add("highlight"); // Beri warna penanda
                } else {
                    tr[i].style.display = "none";
                    tr[i].classList.remove("highlight");
                }
            }
        }
        
        // Hapus highlight jika input kosong
        if (filter === "") {
            tr.forEach(row => row.classList.remove("highlight"));
        }
    });
}
</script>

</body>
</html>
