const formatRupiah = (angka) => `Rp ${angka.toLocaleString("id-ID")}`;

const pemasukan = 5100000000;
const pengeluaran = 5100000000;
const saldo = pemasukan - pengeluaran;

document.getElementById("pemasukan").textContent = formatRupiah(pemasukan);
document.getElementById("pengeluaran").textContent = formatRupiah(pengeluaran);
document.getElementById("saldo").textContent = formatRupiah(saldo);
