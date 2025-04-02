function goBack() {
    window.history.back();
}

function saveChanges() {
    alert("Perubahan telah disimpan!");
}

document.querySelector('.btn.exit').addEventListener('click', function (event) {
    console.log('Tombol exit diklik'); // Debugging
    if (confirm('Apakah Anda yakin ingin keluar dari aplikasi?')) {
        // Jika pengguna memilih "OK"
        window.location.href = 'index.html'; // Arahkan ke halaman login
    } else {
        // Jika pengguna memilih "Cancel"
        console.log('Logout dibatalkan'); // Debugging
        alert('Logout dibatalkan.');
        event.preventDefault(); // Mencegah tindakan default
    }
});

function logout() {
    window.location.href = 'logout.php';
}