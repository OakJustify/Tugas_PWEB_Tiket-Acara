function hitungTotal() {
    const harga = HARGA[$('#tiket').val()];
    const jumlah = parseInt($('#jumlah').val()) || 0;

    if (harga) {
        $('#total').text('Rp ' + (harga * jumlah).toLocaleString('id-ID'));
    } else {
        $('#total').text('Rp 0');
    }
}

$(document).ready(function () {
    hitungTotal();
});

$('#tiket, #jumlah').on('change input', hitungTotal);

$('#formTiket').on('submit', function (event) {
    event.preventDefault();

    if ($('#nama').val().trim() === '') {
        alert('Nama pemesan wajib diisi');
        return;
    }

    if ($('#tiket').val() === '') {
        alert('Silakan pilih tiket');
        return;
    }

    const jumlah = parseInt($('#jumlah').val());

    if (jumlah < 1 || jumlah > 10) {
        alert('Jumlah tiket harus 1 sampai 10');
        return;
    }

    event.target.submit();
});
