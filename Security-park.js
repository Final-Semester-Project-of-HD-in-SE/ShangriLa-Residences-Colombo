document.addEventListener('DOMContentLoaded', () => {
    const clearButtons = document.querySelectorAll('.btn-clear');

    clearButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const slotInfo = event.target.closest('.parking-slot').querySelector('.slot-info');
            slotInfo.querySelector('p:nth-child(2)').textContent = 'Vehicle: N/A';
            slotInfo.querySelector('p:nth-child(3)').textContent = 'Status: Available';
            slotInfo.closest('.parking-slot').querySelector('img').src = 'images/Available.png';

        });
    });
});