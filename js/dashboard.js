document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');


    
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('minimized');
        mainContent.classList.toggle('minimized');
    });
});