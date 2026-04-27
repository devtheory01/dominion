// FILE: /assets/script.js
// Javascript for Mobile Menu, IntersectionObserver (Fade-ins), Modals.

// Mobile Menu Toggle
function toggleMenu() {
    document.getElementById("nav-menu").classList.toggle("show");
}

// Fade-in via IntersectionObserver
document.addEventListener("DOMContentLoaded", function() {
    let faders = document.querySelectorAll(".fade-in");
    let options = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" };
    
    let observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add("visible");
            observer.unobserve(entry.target);
        });
    }, options);

    faders.forEach(fader => { observer.observe(fader); });

    // Back to top button visibility
    window.addEventListener("scroll", () => {
        let btn = document.getElementById("back-to-top");
        if(btn) btn.style.display = window.scrollY > 300 ? "block" : "none";
    });
});

// YouTube Modal
function openModal(youtubeId) {
    let modal = document.getElementById('videoModal');
    let container = document.getElementById('videoContainer');
    container.innerHTML = '<iframe src="https://www.youtube.com/embed/' + youtubeId + '?autoplay=1" allowfullscreen></iframe>';
    modal.style.display = "block";
}
function closeModal(e) {
    let modal = document.getElementById('videoModal');
    if (e.target === modal || e.target.className === 'close-btn') {
        modal.style.display = "none";
        document.getElementById('videoContainer').innerHTML = '';
    }
}
