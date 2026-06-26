<?php
require_once 'koneksi.php';
require_once 'Mahasiswa.php';

class MahasiswaPrestasi extends Mahasiswa {
    private string $namaInstansiBeasiswa;
    private float $minimalIpkSyarat;

    public function __construct(int $id, string $nama, string $nim, int $sem, float $tarif, string $instansi, float $ipk) {
        parent::__construct($id, $nama, $nim, $sem, $tarif);
        $this->namaInstansiBeasiswa = $instansi;
        $this->minimalIpkSyarat = $ipk;
    }

    // Overriding method abstrak (Polimorfisme - Tahap 5)
    public function hitungTagihanSemester(): float {
        // Mendapatkan beasiswa 75%, sehingga mahasiswa cukup membayar 25% dari UKT asli
        return $this->tarifUktNominal * 0.25;
    }

    // Overriding method abstrak (Tahap 3 & 4)
    public function tampilkanSpesifikasiAkademik(): void {
        echo "--- Spesifikasi Akademik Mahasiswa Prestasi ---\n";
        echo "NIM              : " . $this->getNim() . "\n";
        echo "Nama             : " . $this->getNamaMahasiswa() . "\n";
        echo "Semester         : " . $this->getSemester() . "\n";
        echo "Instansi Beasiswa: " . $this->namaInstansiBeasiswa . "\n";
        echo "Syarat Min. IPK  : " . number_format($this->minimalIpkSyarat, 2) . "\n";
        echo "Tagihan UKT      : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . " (Mendapatkan Beasiswa)\n\n";
    }

    // Simulasi Query SELECT-WHERE spesifik (Tahap 4)
    public static function queryMahasiswaPrestasiByIpk(PDO $pdo, float $minIpk) {
        $stmt = $pdo->prepare("SELECT * FROM tabel_mahasiswa WHERE jenis_pembiayaan = 'prestasi' AND minimal_ipk_syarat >= :min_ipk");
        $stmt->execute([':min_ipk' => $minIpk]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getter Setter Tambahan
    public function getNamaInstansiBeasiswa(): string { return $this->namaInstansiBeasiswa; }
    public function getMinimalIpkSyarat(): float { return $this->minimalIpkSyarat; }
}
?>