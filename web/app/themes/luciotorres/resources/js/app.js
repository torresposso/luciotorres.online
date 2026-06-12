// Pure HTML/CSS DaisyUI Drawer is used. No custom JavaScript is required for layout toggle.

// Progressive enhancement for keyboard accessibility (WCAG AA):
// Enable Space/Enter triggers on label buttons that toggle the main drawer checkbox.
document.addEventListener('keydown', (e) => {
  if ((e.key === ' ' || e.key === 'Enter') && e.target.matches('label[for="main-drawer"]')) {
    e.preventDefault();
    const checkbox = document.getElementById(e.target.getAttribute('for'));
    if (checkbox) {
      checkbox.checked = !checkbox.checked;
      checkbox.dispatchEvent(new Event('change', { bubbles: true }));
    }
  }
});

// Navbar scroll compression
const navbar = document.querySelector('.navbar');
if (navbar) {
  // Check initial scroll position on load
  if (window.scrollY > 50) {
    navbar.classList.add('navbar-scrolled');
  }
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('navbar-scrolled');
    } else {
      navbar.classList.remove('navbar-scrolled');
    }
  }, { passive: true });
}

// Scroll reveal animations observer
const revealElements = document.querySelectorAll('.reveal');
if (revealElements.length > 0) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('reveal-active');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -40px 0px'
  });
  revealElements.forEach((el) => observer.observe(el));
}


