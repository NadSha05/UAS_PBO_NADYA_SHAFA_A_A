<?php
require_once 'koneksi.php';
require_once 'Mahasiswa.php'; // Kelas abstrak induk

class MahasiswaMandiri extends Mahasiswa {
    private string $golonganUkt;
    private string $namaWali;

    public function __construct(int $id, string $nama, string $nim, int $sem, float $tarif, string $golUkt, string $wali) {
        parent::__construct($id, $nama, $nim, $sem, $tarif);
        $this->golonganUkt = $golUkt;
        $this->namaWali = $wali;
    }

    // Overriding method abstrak (Polimorfisme - Tahap 5)
    public function hitungTagihanSemester(): float {
        // Tarif UKT + Biaya Operasional Rp 100.000
        return $this->tarifUktNominal + 100000;
    }

    // Overriding method abstrak (Tahap 3 & 4)
    public function tampilkanSpesifikasiAkademik(): void {
        echo "--- Spesifikasi Akademik Mahasiswa Mandiri ---\n";
        echo "NIM           : " . $this->getNim() . "\n";
        echo "Nama          : " . $this->getNamaMahasiswa() . "\n";
        echo "Semester      : " . $this->getSemester() . "\n";
        echo "Golongan UKT  : " . $this->golonganUkt . "\n";
        echo "Nama Wali     : " . $this->namaWali . "\n";
        echo "Tagihan UKT   : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . "\n\n";
    }

    // Simulasi Query SELECT-WHERE spesifik (Tahap 4)
    public static function queryMahasiswaMandiriByGolongan(PDO $pdo, string $golongan) {
        $stmt = $pdo->prepare("SELECT * FROM tabel_mahasiswa WHERE jenis_pembiayaan = 'mandiri' AND golongan_ukt = :golongan");
        $stmt->execute([':golongan' => $golongan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getter Setter Tambahan
    public function getGolonganUkt(): string { return $this->golonganUkt; }
    public function getNamaWali(): string { return $this->namaWali; }
}
?>