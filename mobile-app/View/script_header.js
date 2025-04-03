//theme//
// Grab the body and theme icons
const lightIcon = document.getElementById("light-icon");
const darkIcon = document.getElementById("dark-icon");
const body = document.body;

// Set the default theme based on localStorage (if available)
const currentTheme = localStorage.getItem("theme") || "light";
body.className = "theme-" + currentTheme;

// Function to change the theme
function changeTheme(theme) {
    // Change the body's class based on the selected theme
    body.className = "theme-" + theme;
    // Save the selected theme in localStorage for persistence
    localStorage.setItem("theme", theme);
}

// Event listener for light theme
lightIcon.addEventListener("click", () => {
    changeTheme("light");
});

// Event listener for dark theme
darkIcon.addEventListener("click", () => {
    changeTheme("dark");
});