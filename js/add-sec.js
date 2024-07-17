document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('officer-form');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        alert('New security officer registered successfully!');
        form.reset();
    });
});