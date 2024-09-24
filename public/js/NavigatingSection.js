// Get the buttons and the sections
const overviewBtn = document.getElementById('overviewBtn');
const changePassBtn = document.getElementById('changePassBtn');
const overviewSection = document.getElementById('overviewSection');
const changePassSection = document.getElementById('changePassSection');

// Function to show the selected section
function showSection(activeSection) {
    if (activeSection === 'overview') {
        overviewSection.style.display = 'block';
        changePassSection.style.display = 'none';
        overviewBtn.classList.add('new-active');
        changePassBtn.classList.remove('new-active');
    } else {
        overviewSection.style.display = 'none';
        changePassSection.style.display = 'block';
        changePassBtn.classList.add('new-active');
        overviewBtn.classList.remove('new-active');
    }
}

// Add event listeners to buttons
overviewBtn.addEventListener('click', function() {
    showSection('overview');
    localStorage.setItem('activeSection', 'overview'); // Store active section
});

changePassBtn.addEventListener('click', function() {
    showSection('changePass');
    localStorage.setItem('activeSection', 'changePass'); // Store active section
});

// Check localStorage for active section on page load
window.onload = function() {
    const activeSection = localStorage.getItem('activeSection');
    if (activeSection) {
        showSection(activeSection); // Show the stored section
    } else {
        showSection('overview'); // Default to overview if nothing is stored
    }
};

// // Reset to Overview when the page is visible again after being hidden
// document.addEventListener('visibilitychange', function() {
//     if (document.visibilityState === 'visible') {
//         // Check if it has been more than 5 seconds since last visibility change
//         setTimeout(() => {
//             showSection('overview'); // Reset to overview section
//             localStorage.removeItem('activeSection'); // Clear stored section
//         }, 3000);
//     }
// });