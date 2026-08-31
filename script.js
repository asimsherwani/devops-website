document.getElementById("year").textContent = new Date().getFullYear();

const header = document.querySelector(".header");

window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
        header.style.background = "rgba(7, 17, 31, 0.96)";
    } else {
        header.style.background = "rgba(7, 17, 31, 0.82)";
    }
});