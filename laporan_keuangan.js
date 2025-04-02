// Sembunyikan notifikasi setelah 5 detik
setTimeout(() => {
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    if (successAlert) successAlert.style.display = 'none';
    if (errorAlert) errorAlert.style.display = 'none';
}, 3000); // 5000 ms = 5 detik

function toggleCheckboxes(masterCheckbox) {
    // Ambil semua checkbox dengan nama "ids[]"
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');
    
    // Atur status checkbox lain sesuai dengan checkbox utama
    checkboxes.forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
    });
}