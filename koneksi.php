<?php
// Konfigurasi Database
$host = "localhost";
$dbname = "db_uas_pbo_trpl1a_nadya_shafa_a_a";
$username = "root"; // Sesuaikan dengan konfigurasi server Anda
$password = "";     // Sesuaikan dengan konfigurasi server Anda

try {
    // Membuat koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Mengatur mode error PDO ke Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Opsional: Menampilkan pesan jika berhasil (bisa dihapus jika tidak perlu)
    // echo "Koneksi ke database berhasil!";
    
} catch (PDOException $e) {
    // Menampilkan pesan jika terjadi kesalahan koneksi
    die("Koneksi database gagal: " . $e->getMessage());
}
?>