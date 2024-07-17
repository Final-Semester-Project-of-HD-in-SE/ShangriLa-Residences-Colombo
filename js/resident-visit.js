document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('visitor-form');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        alert('Visitor pre-registered successfully!');
        form.reset();
    });
});