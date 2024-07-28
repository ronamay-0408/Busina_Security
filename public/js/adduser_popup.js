const showPopupBtn = document.querySelector(".add-new");
const formPopup = document.querySelector(".form-popup");
const hidePopupBtn = formPopup.querySelector(".close-btn");
const blurBgOverlay = document.querySelector(".blur-bg-overlay");

// Show login popup
showPopupBtn.addEventListener("click", () => {
    document.body.classList.add("show-popup");
});

// Hide login popup
hidePopupBtn.addEventListener("click", () => {
    document.body.classList.remove("show-popup");
});

// Hide popup when clicking on the overlay
blurBgOverlay.addEventListener("click", () => {
    document.body.classList.remove("show-popup");
});