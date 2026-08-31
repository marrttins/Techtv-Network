function initApp() {
    // Theme Toggle Logic
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        // Load initial theme
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);

        themeToggle.addEventListener('click', () => {
            const activeTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeToggle) return;
        const icon = themeToggle.querySelector('i') || themeToggle;
        if (theme === 'dark') {
            icon.innerHTML = '☀️';
        } else {
            icon.innerHTML = '🌙';
        }
    }

    // Mobile Menu Toggle Logic
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileClose = document.getElementById('mobile-drawer-close');
    const navMenu = document.getElementById('nav-menu');
    const mobileBackdrop = document.getElementById('mobile-nav-backdrop');

    function openMobileMenu(e) {
        if (e) e.preventDefault();
        if (navMenu) navMenu.classList.add('nav-menu--open');
        if (mobileBackdrop) mobileBackdrop.classList.add('is-active');
        document.body.classList.add('mobile-nav-open');
    }

    function closeMobileMenu(e) {
        if (navMenu) navMenu.classList.remove('nav-menu--open');
        if (mobileBackdrop) mobileBackdrop.classList.remove('is-active');
        document.body.classList.remove('mobile-nav-open');
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (navMenu && navMenu.classList.contains('nav-menu--open')) {
                closeMobileMenu(e);
            } else {
                openMobileMenu(e);
            }
        });
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', (e) => {
            e.stopPropagation();
            closeMobileMenu(e);
        });
    }

    if (mobileBackdrop) {
        mobileBackdrop.addEventListener('click', (e) => {
            e.stopPropagation();
            closeMobileMenu(e);
        });
    }

    // Close when clicking links inside drawer (except dropdown triggers)
    if (navMenu) {
        navMenu.querySelectorAll('.nav-sub-link:not(.has-children), .dropdown-item').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 1024) {
                    closeMobileMenu();
                }
            });
        });
    }

    // Close when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (document.body.classList.contains('mobile-nav-open')) {
            if (navMenu && !navMenu.contains(e.target) && mobileToggle && !mobileToggle.contains(e.target)) {
                closeMobileMenu();
            }
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.body.classList.contains('mobile-nav-open')) {
            closeMobileMenu();
        }
    });

    // Mobile dropdown toggle
    document.querySelectorAll('.nav-item-dropdown').forEach((item) => {
        const link = item.querySelector('.nav-sub-link');
        if (link) {
            link.addEventListener('click', (e) => {
                if (window.innerWidth <= 1024) {
                    const hasDropdown = item.querySelector('.dropdown-menu');
                    if (hasDropdown) {
                        e.preventDefault();
                        e.stopPropagation();
                        item.classList.toggle('is-open');
                    }
                }
            });
        }
    });

    // Reset mobile menu state on window resize back to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            closeMobileMenu();
            document.querySelectorAll('.nav-item-dropdown.is-open').forEach((el) => {
                el.classList.remove('is-open');
            });
        }
    });

    // Ajax Newsletter Signup Form
    const newsletterForm = document.getElementById('form-newsletter');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const emailInput = newsletterForm.querySelector('input[type="email"]');
            const email = emailInput.value;
            const btn = newsletterForm.querySelector('button');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = 'Subscribing...';

            try {
                const response = await fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email })
                });

                const result = await response.json();
                if (response.ok) {
                    alert('Thank you for subscribing!');
                    emailInput.value = '';
                } else {
                    alert(result.message || 'Subscription failed.');
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}
