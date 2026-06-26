<?php

// Kelas Abstrak Mahasiswa
abstract class Mahasiswa {
    // Atribut terenkapsulasi dengan akses protected 
    // (dipetakan secara pas dari kolom database Anda)
    protected int $id_mahasiswa;
    protected string $nama_mahasiswa;
    protected string $nim;
    protected int $semester;
    protected float $tarifUktNominal;

    // Konstruktor untuk menginisialisasi atribut
    public function __construct(int $id_mahasiswa, string $nama_mahasiswa, string $nim, int $semester, float $tarifUktNominal) {
        $this->id_mahasiswa = $id_mahasiswa;
        $this->nama_mahasiswa = $nama_mahasiswa;
        $this->nim = $nim;
        $this->semester = $semester;
        $this->tarifUktNominal = $tarifUktNominal;
    }

    // --- Abstract Methods (Wajib diimplementasikan oleh kelas turunannya) ---
    
    // Metode abstrak untuk menghitung tagihan UKT/SPP per semester
    abstract public function hitungTagihanSemester(): float;

    // Metode abstrak untuk menampilkan spesifikasi atau detail akademik mahasiswa
    abstract public function tampilkanSpesifikasiAkademik(): void;

    // --- Getter dan Setter (Enkapsulasi) ---

    public function getIdMahasiswa(): int {
        return $this->id_mahasiswa;
    }

    public function setIdMahasiswa(int $id_mahasiswa): void {
        $this->id_mahasiswa = $id_mahasiswa;
    }

    public function getNamaMahasiswa(): string {
        return $this->nama_mahasiswa;
    }

    public function setNamaMahasiswa(string $nama_mahasiswa): void {
        $this->nama_mahasiswa = $nama_mahasiswa;
    }

    public function getNim(): string {
        return $this->nim;
    }

    public function setNim(string $nim): void {
        $this->nim = $nim;
    }

    public function getSemester(): int {
        return $this->semester;
    }

    public function setSemester(int $semester): void {
        $this->semester = $semester;
    }

    public function getTarifUktNominal(): float {
        return $this->tarifUktNominal;
    }

    public function setTarifUktNominal(float $tarifUktNominal): void {
        $this->tarifUktNominal = $tarifUktNominal;
    }
}

?>