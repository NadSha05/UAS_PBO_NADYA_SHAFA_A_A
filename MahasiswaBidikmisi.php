<?php
require_once 'koneksi.php';
require_once 'Mahasiswa.php';

class MahasiswaBidikmisi extends Mahasiswa {
    private string $nomorKipKuliah;
    private float $danaSakuSubsidi;

    public function __construct(int $id, string $nama, string $nim, int $sem, float $tarif, string $kip, float $danaSaku) {
        parent::__construct($id, $nama, $nim, $sem, $tarif);
        $this->nomorKipKuliah = $kip;
        $this->danaSakuSubsidi = $danaSaku;
    }

    // Overriding method abstrak (Polimorfisme - Tahap 5)
    public function hitungTagihanSemester(): float {
        // Biaya kuliah digratiskan penuh melalui KIP Kuliah
        return 0.0;
    }

    // Overriding method abstrak (Tahap 3 & 4)
    public function tampilkanSpesifikasiAkademik(): void {
        echo "--- Spesifikasi Akademik Mahasiswa Bidikmisi ---\n";
        echo "NIM              : " . $this->getNim() . "\n";
        echo "Nama             : " . $this->getNamaMahasiswa() . "\n";
        echo "Semester         : " . $this->getSemester() . "\n";
        echo "Nomor KIP Kuliah : " . $this->nomorKipKuliah . "\n";
        echo "Dana Saku/Subsidi: Rp " . number_format($this->danaSakuSubsidi, 2, ',', '.') . "\n";
        echo "Tagihan UKT      : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . " (Ditanggung Negara)\n\n";
    }

    // Simulasi Query SELECT-WHERE spesifik (Tahap 4)
    public static function queryMahasiswaBidikmisiByKip(PDO $pdo, string $kip) {
        $stmt = $pdo->prepare("SELECT * FROM tabel_mahasiswa WHERE jenis_pembiayaan = 'bidikmisi' AND nomor_kip_kuliah = :kip");
        $stmt->execute([':kip' => $kip]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getter Setter Tambahan
    public function getNomorKipKuliah(): string { return $this->nomorKipKuliah; }
    public function getDanaSakuSubsidi(): float { return $this->danaSakuSubsidi; }
}
?>