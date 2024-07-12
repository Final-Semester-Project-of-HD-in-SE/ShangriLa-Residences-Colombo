document.addEventListener('DOMContentLoaded', function() {
    const complaintForm = document.getElementById('complaint-form');
    const serviceRequestForm = document.getElementById('service-request-form');
    const complaintsList = document.getElementById('complaints-list');
    const serviceRequestsList = document.getElementById('service-requests-list');

    // Function to initialize entries from localStorage
    function initializeEntries() {
        const storedComplaints = JSON.parse(localStorage.getItem('complaints')) || [];
        const storedRequests = JSON.parse(localStorage.getItem('serviceRequests')) || [];

        // Display stored complaints
        storedComplaints.forEach(entry => {
            addEntry(complaintsList, entry.userName, entry.text, true); // Passing true for isEditable
        });

        // Display stored service requests
        storedRequests.forEach(entry => {
            addEntry(serviceRequestsList, entry.userName, entry.text, true); // Passing true for isEditable
        });
    }

    // Initialize entries when the page loads
    initializeEntries();

    // Event listener for submitting complaints
    complaintForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const userName = complaintForm.querySelector('#complaint-name').value;
        const complaintText = complaintForm.querySelector('#complaint-text').value;

        // Add to localStorage
        const storedComplaints = JSON.parse(localStorage.getItem('complaints')) || [];
        storedComplaints.push({ userName, text: complaintText });
        localStorage.setItem('complaints', JSON.stringify(storedComplaints));

        // Add to UI
        addEntry(complaintsList, userName, complaintText, true); // Passing true for isEditable

        // Reset form fields after submission
        complaintForm.reset();
    });

    // Event listener for submitting service requests
    serviceRequestForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const userName = serviceRequestForm.querySelector('#request-name').value;
        const requestText = serviceRequestForm.querySelector('#request-text').value;

        // Add to localStorage
        const storedRequests = JSON.parse(localStorage.getItem('serviceRequests')) || [];
        storedRequests.push({ userName, text: requestText });
        localStorage.setItem('serviceRequests', JSON.stringify(storedRequests));

        // Add to UI
        addEntry(serviceRequestsList, userName, requestText, true); // Passing true for isEditable

        // Reset form fields after submission
        serviceRequestForm.reset();
    });

    // Function to add an entry to the UI
    function addEntry(listElement, userName, text, isEditable) {
        const entryItem = document.createElement('div');
        entryItem.classList.add('entry-item');

        const editButton = `<button class="edit-btn">Edit</button>`;
        entryItem.innerHTML = `<strong>${userName}</strong>: ${text} ${isEditable ? editButton : ''}`;

        // Add event listener for edit button
        if (isEditable) {
            const editBtn = entryItem.querySelector('.edit-btn');
            editBtn.addEventListener('click', function() {
                const newText = prompt('Enter new text:');
                if (newText !== null && newText.trim() !== '') {
                    // Update text in UI
                    entryItem.innerHTML = `<strong>${userName}</strong>: ${newText} ${editButton}`;

                    // Update localStorage
                    const entriesKey = listElement.id === 'complaints-list' ? 'complaints' : 'serviceRequests';
                    const storedEntries = JSON.parse(localStorage.getItem(entriesKey)) || [];
                    const entryIndex = Array.from(listElement.children).indexOf(entryItem);
                    storedEntries[entryIndex].text = newText;
                    localStorage.setItem(entriesKey, JSON.stringify(storedEntries));
                }
            });
        }

        listElement.appendChild(entryItem);
    }
});

