// Menyembunyikan notifikasi setelah 3 detik (Functional Style)
const hideNotifications = () => ['success-alert', 'error-alert'].forEach(hideElement);

setTimeout(hideNotifications, 3000);

// Fungsi toggle semua checkbox (Functional Style)
const toggleCheckboxes = (masterCheckbox) => 
    Array.from(document.querySelectorAll('input[name="ids[]"]'))
        .forEach(checkbox => checkbox.checked = masterCheckbox.checked);
