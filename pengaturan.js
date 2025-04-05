const goBack = () => window.history.back();

const saveChanges = () => alert("Perubahan telah disimpan!");

const handleExitClick = (event) => {
    console.log('Tombol exit diklik');
    confirmExit(event);
};

const confirmExit = (event) => {
    return confirm('Apakah Anda yakin ingin keluar dari aplikasi?')
        ? redirectTo('index.html')
        : cancelLogout(event);
};

const redirectTo = (url) => {
    window.location.href = url;
};

const cancelLogout = (event) => {
    console.log('Logout dibatalkan');
    alert('Logout dibatalkan.');
    event.preventDefault();
};

const logout = () => redirectTo('logout.php');

// Event binding
const bindEvents = () => {
    const exitButton = document.querySelector('.btn.exit');
    exitButton?.addEventListener('click', handleExitClick);
};

// Inisialisasi semua event
const init = () => {
    bindEvents();
};

init();
