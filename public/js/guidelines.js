let currentMatchIndex = 0;
let matches = [];

function searchGuidelines() {
    const searchInputElement = document.getElementById('sectionSearch');
    const searchInput = searchInputElement.value.trim().toLowerCase();
    const guidelineSections = document.querySelectorAll('.box-g-2 h4, .box-g-2 p');

    // Set the width of the search input to 200px
    searchInputElement.style.width = '200px';

    // Reset previous match highlights
    resetHighlights();

    if (searchInput === "") {
        matches = [];
        document.getElementById('matchInfo').innerText = '';
        searchInputElement.style.width = ''; // Reset width when input is cleared
        return;
    }

    // Find matches
    matches = Array.from(guidelineSections).filter(section => {
        const sectionText = section.innerText.toLowerCase();
        return sectionText.includes(searchInput);
    });

    // Highlight matches
    matches.forEach((match, index) => {
        highlightMatch(match, searchInput);
    });

    if (matches.length > 0) {
        currentMatchIndex = 0;
        scrollToMatch(matches[currentMatchIndex]);
        updateMatchInfo();
    } else {
        document.getElementById('matchInfo').innerText = 'No matches found.';
    }
}

function highlightMatch(element, searchTerm) {
    const innerHTML = element.innerHTML;
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    element.innerHTML = innerHTML.replace(regex, '<span class="highlight">$1</span>');
}

function resetHighlights() {
    const guidelineSections = document.querySelectorAll('.box-g-2 h4, .box-g-2 p');
    guidelineSections.forEach(section => {
        section.innerHTML = section.innerText;  // Reset content to plain text without highlights
    });

    // Remove the 'current-match' class from any previously highlighted matches
    matches.forEach(match => match.classList.remove('current-match'));
}

function scrollToMatch(match) {
    // Remove 'current-match' class from all matches
    matches.forEach(m => m.classList.remove('current-match'));

    // Add 'current-match' class to the new match
    match.classList.add('current-match');
    match.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function updateMatchInfo() {
    document.getElementById('matchInfo').innerText = `Match ${currentMatchIndex + 1} of ${matches.length}`;
}

function prevMatch() {
    if (matches.length === 0) return;

    if (currentMatchIndex > 0) {
        currentMatchIndex--;
        scrollToMatch(matches[currentMatchIndex]);
        updateMatchInfo();
    }
}

function nextMatch() {
    if (matches.length === 0) return;

    if (currentMatchIndex < matches.length - 1) {
        currentMatchIndex++;
        scrollToMatch(matches[currentMatchIndex]);
        updateMatchInfo();
    }
}