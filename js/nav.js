document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.getElementById('nav-toggle');
    const sideNav = document.getElementById('side-nav');
    
    navToggle.addEventListener('click', () => {
        navToggle.classList.toggle('active');
        sideNav.classList.toggle('active');
    });
    
    // Close nav when clicking outside
    document.addEventListener('click', (e) => {
        if (!sideNav.contains(e.target) && !navToggle.contains(e.target)) {
            navToggle.classList.remove('active');
            sideNav.classList.remove('active');
        }
    });
});