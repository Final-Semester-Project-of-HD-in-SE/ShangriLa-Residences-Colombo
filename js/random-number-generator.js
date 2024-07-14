function generateRandomNumber() {
    return Math.floor(Math.random() * 9000) + 1000;
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('issue-no').value = generateRandomNumber();
    document.getElementById('suggestion-no').value = generateRandomNumber();
});
