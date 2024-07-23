function formatDate(date) {
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayName = days[date.getDay()];

    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed
    const day = date.getDate().toString().padStart(2, '0');

    return `${dayName}, ${year}-${month}-${day}`;
}

function formatTime(date) {
    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'

    return `${hours.toString().padStart(2, '0')}:${minutes} ${ampm}`;
}

function displayDateTime() {
    const now = new Date();
    const formattedDate = formatDate(now);
    const formattedTime = formatTime(now);

    document.querySelector('#date').value = formattedDate.split(', ')[1];
    document.querySelector('#time').value = formattedTime;

    document.querySelector('.date-time').textContent = `${formattedDate} ${formattedTime}`;
}

document.addEventListener('DOMContentLoaded', (event) => {
    displayDateTime();
});