function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.querySelector('.overlay').classList.toggle('show');
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('show');
    document.querySelector('.overlay').classList.remove('show');
}