// General UI utilities and interactions

// Mobile Menu Toggle
function initMobileMenu() {
    const burgerIcon = document.querySelector('.burger-icon');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (burgerIcon && mobileMenu) {
        burgerIcon.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
        });

        // Close menu when a link is clicked
        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
            });
        });
    }
}

// Modal utilities
const Modal = {
    open: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    },
    close: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    },
    closeOnBackdrop: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.close(modalId);
                }
            });
        }
    }
};

// Form utilities
function formatCurrency(value) {
    return parseFloat(value).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type}">
            ${message}
        </div>
    `;
    const alertContainer = document.querySelector('.alert-container') || document.body;
    const alertElement = document.createElement('div');
    alertElement.innerHTML = alertHtml;
    alertContainer.insertBefore(alertElement.firstChild, alertContainer.firstChild);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertElement.firstChild.remove();
    }, 5000);
}

// Initialize all on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    initMobileMenu();

    // Setup modal close buttons
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const modal = e.target.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
            }
        });
    });

    // Setup backdrop closing for modals
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});
