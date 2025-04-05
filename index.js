// Fungsi untuk memilih atau membatalkan semua checkbox (Functional Style)
const toggleCheckboxes = (source) => {
    Array.from(document.querySelectorAll('input[name="ids[]"]'))
        .forEach(checkbox => checkbox.checked = source.checked);
};

// Fungsi murni untuk mencegah zoom saat scroll
const preventZoomOnWheel = (e) => 
    e.ctrlKey && e.preventDefault();

// Fungsi murni untuk mencegah zoom dengan tombol plus/minus/0
const preventZoomOnKeydown = (e) => 
    e.ctrlKey && ['+', '-', '0'].includes(e.key) && e.preventDefault();

// Pasang event listener
window.addEventListener('wheel', preventZoomOnWheel, { passive: false });
window.addEventListener('keydown', preventZoomOnKeydown);
