// Pure Function
const formatRupiah = (angka) => `Rp ${angka.toLocaleString("id-ID")}`;

// Pure Function untuk menghitung saldo
const hitungSaldo = (pemasukan, pengeluaran) => pemasukan - pengeluaran;

// Pure Function untuk membuat data siap ditampilkan
const buatDataKeuangan = (pemasukan, pengeluaran) => ({
    pemasukan: formatRupiah(pemasukan),
    pengeluaran: formatRupiah(pengeluaran),
    saldo: formatRupiah(hitungSaldo(pemasukan, pengeluaran))
});

// Impure function: Efek samping (memisahkan efeknya)
const tampilkanKeuangan = (data) => {
    document.getElementById("pemasukan").textContent = data.pemasukan;
    document.getElementById("pengeluaran").textContent = data.pengeluaran;
    document.getElementById("saldo").textContent = data.saldo;
};

// Data
const pemasukan = 5100000000;
const pengeluaran = 5100000000;

// Jalankan
const dataKeuangan = buatDataKeuangan(pemasukan, pengeluaran);
tampilkanKeuangan(dataKeuangan);
