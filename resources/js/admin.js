// Philippine Time Display
function updateDateTime() {
    const options = {
        timeZone: 'Asia/Manila',
        hour12: true,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    
    const dateOptions = {
        timeZone: 'Asia/Manila',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long'
    };
    
    const timeElement = document.getElementById('time');
    const dateElement = document.getElementById('date');
    
    if (timeElement) {
        const phTime = new Date().toLocaleTimeString('en-PH', options);
        timeElement.textContent = `🇵🇭 ${phTime}`;
    }
    
    if (dateElement) {
        const phDate = new Date().toLocaleDateString('en-PH', dateOptions);
        dateElement.textContent = phDate;
    }
}

// Update every second
setInterval(updateDateTime, 1000);
updateDateTime();

// Active link highlighting
function setActiveLink() {
    const currentPath = window.location.pathname;
    const links = document.querySelectorAll('.leftpanel a');
    
    links.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        } else if (currentPath.includes('dashboard') && link.getAttribute('href').includes('dashboard')) {
            link.classList.add('active');
        } else if (currentPath.includes('accounts') && link.getAttribute('href').includes('accounts')) {
            link.classList.add('active');
        } else if (currentPath.includes('settings') && link.getAttribute('href').includes('settings')) {
            link.classList.add('active');
        }
    });
}

// Mobile menu toggle
function addMobileMenuToggle() {
    const leftpanel = document.querySelector('.leftpanel');
    const topnavLeft = document.querySelector('.topnav-left');
    
    if (window.innerWidth <= 768 && !document.querySelector('.menu-toggle')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.innerHTML = '☰';
        toggleBtn.className = 'menu-toggle';
        toggleBtn.onclick = () => {
            leftpanel.classList.toggle('open');
        };
        topnavLeft.insertBefore(toggleBtn, topnavLeft.firstChild);
    } else if (window.innerWidth > 768) {
        const toggleBtn = document.querySelector('.menu-toggle');
        if (toggleBtn) toggleBtn.remove();
        if (leftpanel) leftpanel.classList.remove('open');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    setActiveLink();
    addMobileMenuToggle();
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(addMobileMenuToggle, 250);
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        const leftpanel = document.querySelector('.leftpanel');
        const toggleBtn = document.querySelector('.menu-toggle');
        
        if (window.innerWidth <= 768 && leftpanel && leftpanel.classList.contains('open')) {
            if (!leftpanel.contains(e.target) && !toggleBtn?.contains(e.target)) {
                leftpanel.classList.remove('open');
            }
        }
    });
});