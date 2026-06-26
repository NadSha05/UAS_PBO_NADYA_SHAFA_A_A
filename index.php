<?php
// Memanggil file koneksi dan semua definisi kelas
require_once 'koneksi.php';
require_once 'Mahasiswa.php';
require_once 'MahasiswaMandiri.php';
require_once 'MahasiswaBidikmisi.php';
require_once 'MahasiswaPrestasi.php';

// Mengambil data dari database tabel_mahasiswa secara keseluruhan
try {
    $stmt = $pdo->query("SELECT * FROM tabel_mahasiswa");
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
    <title>UAS PBO - Daftar Registrasi Pembayaran Kuliah</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #f0f2f5;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            color: #2c3e50;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .category-section {
            margin-bottom: 40px;
            border: 1px solid #e1e8ed;
            padding: 25px;
            border-radius: 8px;
            background-color: #fafbfc;
        }
        .mandiri { border-left: 6px solid #3498db; }
        .bidikmisi { border-left: 6px solid #2ecc71; }
        .prestasi { border-left: 6px solid #e74c3c; }

        .category-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #eee;
        }
        .mandiri .category-title { color: #3498db; }
        .bidikmisi .category-title { color: #2ecc71; }
        .prestasi .category-title { color: #e74c3c; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        th, td {
            border: 1px solid #d1d8e0;
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #eef2f7;
            color: #1a365d;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-tagihan {
            font-weight: bold;
            color: #e74c3c;
        }
        .spec-box {
            background-color: #fff;
            border: 1px dashed #cbd5e1;
            padding: 15px;
            margin-top: 10px;
            border-radius: 6px;
            font-family: monospace;
            white-space: pre-wrap;
            color: #475569;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Sistem Informasi Akademik</h1>
    <h2>Daftar Registrasi Pembayaran Kuliah Mahasiswa</h2>

    <div class="category-section mandiri">
        <h3 class="category-title">Kelompok: Mahasiswa Mandiri</h3>
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Golongan UKT</th>
                    <th>Nama Wali</th>
                    <th>Tarif UKT Asli</th>
                    <th>Tagihan Akhir (Tarif + Operasional)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($semua_mahasiswa as $data) {
                    if ($data['jenis_pembiayaan'] == 'mandiri') {
                        // Polimorfisme: Instansiasi objek MahasiswaMandiri
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

    <div class="category-section bidikmisi">
        <h3 class="category-title">Kelompok: Mahasiswa Bidikmisi</h3>
        <table>
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
                        // Polimorfisme: Instansiasi objek MahasiswaBidikmisi
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

    <div class="category-section prestasi">
        <h3 class="category-title">Kelompok: Mahasiswa Prestasi</h3>
        <table>
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
                        // Polimorfisme: Instansiasi objek MahasiswaPrestasi
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

</body>
</html>