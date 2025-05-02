document.addEventListener("DOMContentLoaded", function () {
    // Menu button toggle (if needed)
    const menuBtn = document.querySelector(".menu-btn");
    menuBtn.addEventListener("click", function () {
        alert("Menu button clicked! Implement navigation here.");
    });

    // Highlight active navigation item
    const navItems = document.querySelectorAll(".nav-item");
    navItems.forEach(item => {
        item.addEventListener("click", function () {
            navItems.forEach(nav => nav.classList.remove("active"));
            this.classList.add("active");
        });
    });
});