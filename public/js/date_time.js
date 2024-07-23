function formatDate(date) {
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayName = days[date.getDay()];
    
    const year = date.getFullYear();
    
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed
    const day = date.getDate().toString().padStart(2, '0');
    
    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    const strTime = hours.toString().padStart(2, '0') + ':' + minutes + ' ' + ampm;

    return `${dayName}, ${year}-${month}-${day}     ${strTime}`;
}

function displayDateTime() {
    const now = new Date();
    const formattedDate = formatDate(now);
    console.log('Formatted Date:', formattedDate); // Debugging line
    document.querySelector('.date-time').textContent = formattedDate;
}

document.addEventListener('DOMContentLoaded', (event) => {
    console.log('DOM fully loaded and parsed'); // Debugging line
    displayDateTime();
});