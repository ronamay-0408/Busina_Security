function formatDate(date) {
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayName = days[date.getDay()];
    
    const year = date.getFullYear();
    
    const month = (date.getMonth() + 1); // Month is 1-based in this format
    const day = date.getDate();
    
    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');
    
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    const strTime = hours.toString().padStart(2, '0') + ':' + minutes + ':' + seconds + ' ' + ampm;

    return `${dayName}, ${month}/${day}/${year} - ${strTime}`;
}

function displayDateTime() {
    const now = new Date();
    const formattedDate = formatDate(now);
    document.querySelector('.date-time').textContent = formattedDate;
}

document.addEventListener('DOMContentLoaded', (event) => {
    console.log('DOM fully loaded and parsed'); // Debugging line
    displayDateTime();
    // Update the time every second
    setInterval(displayDateTime, 1000); // 1000 milliseconds = 1 second
});
