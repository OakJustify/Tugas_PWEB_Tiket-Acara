<?php

$dbFile = __DIR__ . '/database/ticket-acara.sqlite';

if (!is_dir(dirname($dbFile))) {
    mkdir(dirname($dbFile), 0777, true);
}

try {
    $koneksi = new PDO('sqlite:' . $dbFile);

    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $koneksi->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

$tabelAda = $koneksi
    ->query("SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'tb_pemesanan'")
    ->fetchColumn();

if (!$tabelAda) {
    $koneksi->exec(file_get_contents(__DIR__ . '/tiket.sql'));
}
