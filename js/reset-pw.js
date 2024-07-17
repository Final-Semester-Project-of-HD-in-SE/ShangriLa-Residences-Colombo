document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('forgot-password-form');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const email = document.getElementById('email').value;
        alert(`Password reset instructions have been sent to ${email}`);
        form.reset();
    });
});