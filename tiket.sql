CREATE TABLE IF NOT EXISTS tb_pemesanan (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_pemesan VARCHAR(100) NOT NULL,
    jenis_kelamin VARCHAR(10) NOT NULL,
    kelas_tiket  VARCHAR(10)  NOT NULL,
    jumlah_tiket INTEGER       NOT NULL,
    harga_satuan INTEGER       NOT NULL,
    total_harga  INTEGER       NOT NULL,
    created_at   TEXT          DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tb_pemesanan (nama_pemesan, jenis_kelamin, kelas_tiket, jumlah_tiket, harga_satuan, total_harga) VALUES
('Budi Santoso', 'laki-laki', 'silver',  2, 10000,  20000),
('Siti Rahma',   'perempuan', 'gold',    4, 30000, 120000),
('Agus Salim',   'laki-laki', 'diamond', 1, 60000,  60000),
('Dewi Anggraini','perempuan', 'gold',    3, 30000,  90000);
