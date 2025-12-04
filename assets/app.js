// assets/app.js

// Global App JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all app features
    initializeThemeToggle();
    initThemeSwitcher();
    initializeSidebar();
    initializeAccessibility();
    initializeAnimations();
    initializeFormValidation();
    initializeSearchAutocomplete();
    initializeCartUpdates();
    initializeNewsletterSignup();
    initializeLazyLoading();
    initializeKeyboardNavigation();
});

// Theme toggle functionality
function initializeThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Announce theme change to screen readers
            announceToScreenReader(`Theme changed to ${newTheme} mode`);
        });
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
}

// Sidebar functionality
function initializeSidebar() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle && sidebar && mainContent) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Update ARIA attributes
            const isExpanded = !sidebar.classList.contains('collapsed');
            sidebarToggle.setAttribute('aria-expanded', isExpanded);
            sidebar.setAttribute('aria-hidden', !isExpanded);
        });

        // Set initial ARIA states
        sidebarToggle.setAttribute('aria-expanded', !sidebar.classList.contains('collapsed'));
        sidebar.setAttribute('aria-hidden', sidebar.classList.contains('collapsed'));
    }

    // Auto-hide sidebar on mobile when clicking outside
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (!sidebar?.contains(event.target) && !sidebarToggle?.contains(event.target)) {
                sidebar?.classList.add('collapsed');
                mainContent?.classList.add('expanded');
                sidebarToggle?.setAttribute('aria-expanded', 'false');
                sidebar?.setAttribute('aria-hidden', 'true');
            }
        }
    });
}

// Accessibility enhancements
function initializeAccessibility() {
    // Skip to main content link
    const skipLink = document.querySelector('a[href="#main-content"]');
    if (skipLink) {
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const mainContent = document.getElementById('main-content');
            if (mainContent) {
                mainContent.focus();
                mainContent.scrollIntoView();
            }
        });
    }

    // Keyboard navigation for dropdowns
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (toggle && menu) {
            toggle.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggle.click();
                } else if (e.key === 'Escape') {
                    toggle.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('show');
                }
            });

            // Focus management within dropdown
            menu.addEventListener('keydown', function(e) {
                const focusableElements = menu.querySelectorAll('a, button');
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                } else if (e.key === 'Escape') {
                    toggle.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('show');
                    toggle.focus();
                }
            });
        }
    });

    // Announce dynamic content changes
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;

        document.body.appendChild(announcement);

        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    }

    // Make announcements available globally
    window.announceToScreenReader = announceToScreenReader;
}

// Animation triggers
function initializeAnimations() {
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements that should animate in
    const animateElements = document.querySelectorAll('.card, .alert, .badge');
    animateElements.forEach(el => observer.observe(el));

    // Add loading animation to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.form || this.closest('form')) {
                this.classList.add('loading');
            }
        });
    });
}

// Form validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                // Focus first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Real-time validation feedback
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
}

// Enhanced search autocomplete
function initializeSearchAutocomplete() {
    const searchInput = document.getElementById('search-input');
    const resultsDiv = document.getElementById('autocomplete-results');

    if (searchInput && resultsDiv) {
        let debounceTimer;
        let currentFocus = -1;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                currentFocus = -1;
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach((item, index) => {
                                const div = document.createElement('div');
                                div.className = 'p-2 border-bottom autocomplete-item';
                                div.style.cursor = 'pointer';
                                div.setAttribute('role', 'option');
                                div.setAttribute('aria-selected', 'false');
                                div.innerHTML = `<strong>${item.name}</strong>`;
                                div.addEventListener('click', function() {
                                    window.location.href = item.url;
                                });
                                div.addEventListener('keydown', function(e) {
                                    if (e.key === 'Enter' || e.key === ' ') {
                                        e.preventDefault();
                                        window.location.href = item.url;
                                    }
                                });
                                resultsDiv.appendChild(div);
                            });
                            resultsDiv.style.display = 'block';
                        } else {
                            resultsDiv.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching autocomplete:', error);
                        resultsDiv.style.display = 'none';
                    });
            }, 300);
        });

        // Keyboard navigation for autocomplete
        searchInput.addEventListener('keydown', function(e) {
            const items = resultsDiv.querySelectorAll('.autocomplete-item');
            if (items.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentFocus = currentFocus < items.length - 1 ? currentFocus + 1 : 0;
                updateFocus(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentFocus = currentFocus > 0 ? currentFocus - 1 : items.length - 1;
                updateFocus(items);
            } else if (e.key === 'Enter' && currentFocus >= 0) {
                e.preventDefault();
                items[currentFocus].click();
            } else if (e.key === 'Escape') {
                resultsDiv.style.display = 'none';
                currentFocus = -1;
            }
        });

        function updateFocus(items) {
            items.forEach((item, index) => {
                if (index === currentFocus) {
                    item.setAttribute('aria-selected', 'true');
                    item.style.backgroundColor = 'rgba(230, 126, 34, 0.1)';
                } else {
                    item.setAttribute('aria-selected', 'false');
                    item.style.backgroundColor = '';
                }
            });
        }

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
                currentFocus = -1;
            }
        });
    }
}

// Cart updates
function initializeCartUpdates() {
    // Update cart count periodically
    function updateCartCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('cart-count');
                if (badge) {
                    const currentCount = parseInt(badge.textContent) || 0;
                    const newCount = data.count;

                    if (newCount !== currentCount) {
                        badge.classList.add('bounce');
                        badge.textContent = newCount;
                        badge.setAttribute('aria-label', `${newCount} items in cart`);

                        setTimeout(() => {
                            badge.classList.remove('bounce');
                        }, 1000);
                    }
                }
            })
            .catch(error => console.error('Error updating cart count:', error));
    }

    // Update cart count every 30 seconds
    setInterval(updateCartCount, 30000);

    // Update on page load
    updateCartCount();
}

// Newsletter signup
function initializeNewsletterSignup() {
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = newsletterForm.querySelector('input[type="email"]');
            const submitBtn = newsletterForm.querySelector('button[type="submit"]');

            if (emailInput.value.trim()) {
                // Show loading state
                submitBtn.innerHTML = '<span class="spinner me-1"></span><span class="d-none d-sm-inline">Subscribing...</span>';
                submitBtn.disabled = true;

                // Simulate API call (replace with actual endpoint)
                setTimeout(() => {
                    announceToScreenReader('Thank you for subscribing! You\'ll receive our latest updates.');
                    emailInput.value = '';
                    submitBtn.innerHTML = '<span class="d-none d-sm-inline">Subscribe</span><span class="d-sm-none">📧</span>';
                    submitBtn.disabled = false;
                }, 1000);
            }
        });
    }
}

// Lazy loading for images
function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }
}

// Keyboard navigation enhancements
function initializeKeyboardNavigation() {
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + C for cart
        if (e.altKey && e.key === 'c') {
            e.preventDefault();
            const cartLink = document.querySelector('a[href*="cart"]');
            if (cartLink) cartLink.click();
        }

        // Alt + S for search
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            const searchInput = document.getElementById('search-input');
            if (searchInput) searchInput.focus();
        }

        // Alt + H for home
        if (e.altKey && e.key === 'h') {
            e.preventDefault();
            const homeLink = document.querySelector('a[href="/"]') || document.querySelector('.navbar-brand');
            if (homeLink) homeLink.click();
        }
    });

    // Announce keyboard shortcuts on focus
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            if (!this.getAttribute('aria-describedby')) {
                const helpId = 'keyboard-help';
                let helpDiv = document.getElementById(helpId);
                if (!helpDiv) {
                    helpDiv = document.createElement('div');
                    helpDiv.id = helpId;
                    helpDiv.className = 'sr-only';
                    helpDiv.textContent = 'Keyboard shortcuts: Alt+S for search, Alt+C for cart, Alt+H for home';
                    document.body.appendChild(helpDiv);
                }
                this.setAttribute('aria-describedby', helpId);
            }
        });
    }
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Initialize theme switcher in navbar script
function initThemeSwitcher() {
    const themeSwitcher = document.getElementById('theme-switcher');
    if (themeSwitcher) {
        themeSwitcher.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // Apply theme
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Update button icon
            const iconSpan = this.querySelector('.fs-5');
            if (iconSpan) {
                iconSpan.textContent = newTheme === 'dark' ? '☀️' : '🌙';
            }

            // Announce theme change to screen readers
            if (window.announceToScreenReader) {
                window.announceToScreenReader(`Theme changed to ${newTheme} mode`);
            }
        });
    }

    // Load saved theme on page load
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);

    // Set initial icon
    const iconSpan = themeSwitcher?.querySelector('.fs-5');
    if (iconSpan) {
        iconSpan.textContent = savedTheme === 'dark' ? '☀️' : '🌙';
    }
}

// Confirm delete actions
const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
deleteButtons.forEach(button => {
    button.addEventListener('click', function(event) {
        const message = this.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this item?';
        if (!confirm(message)) {
            event.preventDefault();
        }
    });
});

// Initialize tooltips and popovers
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});

// ============================================
// DASHBOARD FUNCTIONALITY
// ============================================

// Initialize dashboard features
function initializeDashboard() {
    initializeSidebarNavigation();
    initializeCharts();
    initializeAOS();
    initializeResponsiveSidebar();
}

// Sidebar navigation functionality
function initializeSidebarNavigation() {
    const navItems = document.querySelectorAll('.nav-item[data-section]');
    const contentSections = document.querySelectorAll('.content-section');

    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));

            // Add active class to clicked item
            this.classList.add('active');

            // Hide all sections
            contentSections.forEach(section => section.classList.remove('active'));

            // Show selected section
            const targetSection = document.getElementById(this.getAttribute('data-section'));
            if (targetSection) {
                targetSection.classList.add('active');

                // Announce section change to screen readers
                if (window.announceToScreenReader) {
                    window.announceToScreenReader(`Switched to ${this.querySelector('.nav-text').textContent} section`);
                }
            }
        });
    });
}

// Chart initialization using Chart.js
function initializeCharts() {
    // Sales Chart
    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas) {
        const salesCtx = salesChartCanvas.getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Sales',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Chart period buttons
        const chartButtons = document.querySelectorAll('.chart-btn[data-period]');
        chartButtons.forEach(button => {
            button.addEventListener('click', function() {
                chartButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const period = this.getAttribute('data-period');
                updateChartData(salesChart, period);
            });
        });
    }

    // Status Chart (Doughnut)
    const statusChartCanvas = document.getElementById('statusChart');
    if (statusChartCanvas) {
        const statusCtx = statusChartCanvas.getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Processing', 'Cancelled'],
                datasets: [{
                    data: [65, 20, 10, 5],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#6366f1',
                        '#ef4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
}

// Update chart data based on period
function updateChartData(chart, period) {
    let data;
    switch (period) {
        case '7d':
            data = [12000, 19000, 15000, 25000, 22000, 30000, 28000];
            break;
        case '30d':
            data = [45000, 52000, 48000, 61000, 55000, 67000, 63000];
            break;
        case '90d':
            data = [125000, 138000, 142000, 156000, 149000, 167000, 162000];
            break;
        default:
            data = [12000, 19000, 15000, 25000, 22000, 30000, 28000];
    }

    chart.data.datasets[0].data = data;
    chart.update();
}

// AOS (Animate On Scroll) initialization
function initializeAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100,
            delay: 100
        });
    }
}

// Responsive sidebar functionality
function initializeResponsiveSidebar() {
    const sidebar = document.querySelector('.dashboard-sidebar');
    const mainContent = document.querySelector('.dashboard-main');
    const toggleBtn = document.querySelector('.sidebar-toggle');

    if (sidebar && mainContent && toggleBtn) {
        // Mobile sidebar toggle
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('shifted');

            const isExpanded = sidebar.classList.contains('show');
            this.setAttribute('aria-expanded', isExpanded);
            sidebar.setAttribute('aria-hidden', !isExpanded);
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                    mainContent.classList.remove('shifted');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                    sidebar.setAttribute('aria-hidden', 'true');
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('show');
                mainContent.classList.remove('shifted');
                toggleBtn.setAttribute('aria-expanded', 'true');
                sidebar.setAttribute('aria-hidden', 'false');
            }
        });
    }
}

// Initialize dashboard only if dashboard elements exist
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.dashboard-container')) {
        initializeDashboard();
    }
});
