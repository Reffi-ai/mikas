// Fungsi untuk memilih atau membatalkan semua checkbox
function toggleCheckboxes(source) {
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = source.checked);
}

 // Mencegah zoom dengan Ctrl + Scroll
window.addEventListener('wheel', function(e) {
    if (e.ctrlKey) {
        e.preventDefault();
    }
}, { passive: false });

// Mencegah zoom dengan Ctrl + Plus/Minus
window.addEventListener('keydown', function(e) {
    if (e.ctrlKey && (e.key === '+' || e.key === '-' || e.key === '0')) {
        e.preventDefault();
    }
});