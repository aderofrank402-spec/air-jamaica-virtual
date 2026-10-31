/**
 * Professional Pilot Dashboard - Enhanced JavaScript
 * World Class Virtual Airline Interface
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard enhancements
    initializeDashboard();
});

/**
 * Main dashboard initialization
 */
function initializeDashboard() {
    // Initialize counter animations
    initializeCounters();

    // Initialize tooltips
    initializeTooltips();

    // Initialize real-time updates
    initializeRealTimeUpdates();

    // Initialize interactive elements
    initializeInteractiveElements();

    // Initialize accessibility features
    initializeAccessibility();

    console.log('Professional Dashboard initialized successfully');
}

/**
 * Counter animations for statistics
 */
function initializeCounters() {
    const counters = document.querySelectorAll('.counter');

    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    counters.forEach(counter => {
        observer.observe(counter);
    });
}

/**
 * Animate individual counter
 */
function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-count') || element.textContent);
    const duration = 2000; // 2 seconds
    const start = performance.now();
    const startValue = 0;

    function updateCounter(currentTime) {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function for smooth animation
        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.floor(startValue + (target - startValue) * easeOutCubic);

        element.textContent = currentValue.toLocaleString();

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target.toLocaleString();
        }
    }

    requestAnimationFrame(updateCounter);
}

/**
 * Initialize Bootstrap tooltips with custom styling
 */
function initializeTooltips() {
    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                animation: true,
                delay: { show: 300, hide: 100 },
                html: true
            });
        });
    }

    // Add custom tooltips for dashboard elements
    addCustomTooltips();
}

/**
 * Add custom tooltips to dashboard elements
 */
function addCustomTooltips() {
    // Add tooltips to action buttons
    const actionButtons = document.querySelectorAll('.btn-flight-action');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', showCustomTooltip);
        button.addEventListener('mouseleave', hideCustomTooltip);
    });
}

/**
 * Show custom tooltip
 */
function showCustomTooltip(event) {
    const button = event.currentTarget;
    const rect = button.getBoundingClientRect();

    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'custom-tooltip';
    tooltip.innerHTML = getTooltipContent(button);

    // Position tooltip
    tooltip.style.left = rect.left + (rect.width / 2) + 'px';
    tooltip.style.top = (rect.top - 10) + 'px';

    document.body.appendChild(tooltip);

    // Animate in
    setTimeout(() => {
        tooltip.classList.add('show');
    }, 10);
}

/**
 * Hide custom tooltip
 */
function hideCustomTooltip(event) {
    const tooltips = document.querySelectorAll('.custom-tooltip');
    tooltips.forEach(tooltip => {
        tooltip.classList.remove('show');
        setTimeout(() => {
            if (tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        }, 200);
    });
}

/**
 * Get tooltip content based on button type
 */
function getTooltipContent(button) {
    if (button.classList.contains('btn-pirep')) {
        return '<strong>File PIREP</strong><br>Submit your flight report and earn credits';
    } else if (button.classList.contains('btn-flight')) {
        return '<strong>Book Flight</strong><br>Browse and bid on available flights';
    } else if (button.classList.contains('btn-smartcars')) {
        return '<strong>SmartCARS</strong><br>Download ACARS client for flight tracking';
    } else if (button.classList.contains('btn-bids')) {
        return '<strong>My Bids</strong><br>View and manage your booked flights';
    }
    return '';
}

/**
 * Initialize real-time updates
 */
function initializeRealTimeUpdates() {
    // Update network statistics every 30 seconds
    setInterval(updateNetworkStats, 30000);

    // Update active flight status every 15 seconds if there's an active flight
    if (document.querySelector('.active-flight-status-widget .card.border-success')) {
        setInterval(updateActiveFlightStatus, 15000);
    }

    // Update weather every 5 minutes
    setInterval(updateWeatherInfo, 300000);
}

/**
 * Update network statistics
 */
function updateNetworkStats() {
    fetch('/api/dashboard/network-stats', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNetworkStatsDisplay(data.stats);
        }
    })
    .catch(error => {
        console.log('Network stats update failed:', error);
    });
}

/**
 * Update active flight status
 */
function updateActiveFlightStatus() {
    fetch('/api/dashboard/active-flight', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.flight_data) {
            updateActiveFlightDisplay(data.flight_data);
        }
    })
    .catch(error => {
        console.log('Active flight update failed:', error);
    });
}

/**
 * Update weather information
 */
function updateWeatherInfo() {
    const weatherWidget = document.querySelector('.weather-widget');
    if (weatherWidget) {
        const icao = weatherWidget.getAttribute('data-icao');
        if (icao) {
            fetch(`/api/weather/${icao}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateWeatherDisplay(data.weather);
                }
            })
            .catch(error => {
                console.log('Weather update failed:', error);
            });
        }
    }
}

/**
 * Update network stats display
 */
function updateNetworkStatsDisplay(stats) {
    const pilotsFlying = document.querySelector('.network-stat:nth-child(1) .stat-number');
    const usersOnline = document.querySelector('.network-stat:nth-child(2) .stat-number');
    const todaysFlights = document.querySelector('.network-stat:nth-child(3) .stat-number');

    if (pilotsFlying) {
        animateValueUpdate(pilotsFlying, stats.pilots_flying);
    }
    if (usersOnline) {
        animateValueUpdate(usersOnline, stats.users_online);
    }
    if (todaysFlights) {
        animateValueUpdate(todaysFlights, stats.todays_flights);
    }
}

/**
 * Animate value updates
 */
function animateValueUpdate(element, newValue) {
    const currentValue = parseInt(element.textContent);
    const duration = 1000;
    const start = performance.now();

    function updateValue(currentTime) {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);

        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        const currentDisplayValue = Math.floor(currentValue + (newValue - currentValue) * easeOutCubic);

        element.textContent = currentDisplayValue;

        if (progress < 1) {
            requestAnimationFrame(updateValue);
        } else {
            element.textContent = newValue;
        }
    }

    requestAnimationFrame(updateValue);
}

/**
 * Initialize interactive elements
 */
function initializeInteractiveElements() {
    // Add click tracking for analytics
    initializeClickTracking();

    // Add keyboard navigation support
    initializeKeyboardNavigation();

    // Add loading states for buttons
    initializeLoadingStates();

    // Add progress bar animations
    initializeProgressBars();
}

/**
 * Initialize click tracking
 */
function initializeClickTracking() {
    document.addEventListener('click', function(event) {
        const element = event.target.closest('.btn-flight-action, .recommended-flight, .financial-card');

        if (element) {
            // Track the click for analytics
            trackDashboardClick(element);

            // Add visual feedback
            addClickFeedback(element);
        }
    });
}

/**
 * Track dashboard clicks for analytics
 */
function trackDashboardClick(element) {
    let actionType = 'unknown';

    if (element.classList.contains('btn-pirep')) actionType = 'file_pirep';
    else if (element.classList.contains('btn-flight')) actionType = 'book_flight';
    else if (element.classList.contains('btn-smartcars')) actionType = 'download_smartcars';
    else if (element.classList.contains('btn-bids')) actionType = 'view_bids';
    else if (element.classList.contains('recommended-flight')) actionType = 'recommended_flight';
    else if (element.classList.contains('financial-card')) actionType = 'financial_view';

    // Send analytics data
    if (typeof gtag !== 'undefined') {
        gtag('event', 'dashboard_interaction', {
            'action_type': actionType,
            'timestamp': new Date().toISOString()
        });
    }

    console.log('Dashboard click tracked:', actionType);
}

/**
 * Add visual click feedback
 */
function addClickFeedback(element) {
    element.style.transform = 'scale(0.98)';
    setTimeout(() => {
        element.style.transform = '';
    }, 150);
}

/**
 * Initialize keyboard navigation
 */
function initializeKeyboardNavigation() {
    document.addEventListener('keydown', function(event) {
        // Quick keyboard shortcuts
        if (event.ctrlKey || event.metaKey) {
            switch(event.key) {
                case 'p':
                    event.preventDefault();
                    navigateToPage('/pireps/create');
                    break;
                case 'f':
                    event.preventDefault();
                    navigateToPage('/flights');
                    break;
                case 'b':
                    event.preventDefault();
                    navigateToPage('/profile/bids');
                    break;
            }
        }
    });
}

/**
 * Navigate to page with loading feedback
 */
function navigateToPage(url) {
    // Show loading state
    document.body.style.opacity = '0.8';

    // Navigate
    window.location.href = url;
}

/**
 * Initialize loading states for buttons
 */
function initializeLoadingStates() {
    const buttons = document.querySelectorAll('.btn-flight-action');

    buttons.forEach(button => {
        button.addEventListener('click', function(event) {
            if (this.classList.contains('loading')) {
                event.preventDefault();
                return;
            }

            // Add loading state
            this.classList.add('loading');
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            // Remove loading state after navigation starts
            setTimeout(() => {
                this.classList.remove('loading');
                this.innerHTML = originalText;
            }, 3000);
        });
    });
}

/**
 * Initialize progress bar animations
 */
function initializeProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar');

    const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateProgressBar(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    progressBars.forEach(bar => {
        observer.observe(bar);
    });
}

/**
 * Animate progress bar
 */
function animateProgressBar(progressBar) {
    const targetWidth = progressBar.style.width;
    progressBar.style.width = '0%';

    setTimeout(() => {
        progressBar.style.transition = 'width 2s ease-out';
        progressBar.style.width = targetWidth;
    }, 100);
}

/**
 * Initialize accessibility features
 */
function initializeAccessibility() {
    // Add ARIA labels where missing
    addAriaLabels();

    // Add focus management
    initializeFocusManagement();

    // Add screen reader announcements
    initializeScreenReaderSupport();
}

/**
 * Add ARIA labels to dashboard elements
 */
function addAriaLabels() {
    const statCards = document.querySelectorAll('.dashboard-stat-card');
    statCards.forEach((card, index) => {
        if (!card.getAttribute('aria-label')) {
            const statNumber = card.querySelector('.stat-number');
            const statLabel = card.querySelector('.stat-label');

            if (statNumber && statLabel) {
                card.setAttribute('aria-label',
                    `${statLabel.textContent}: ${statNumber.textContent}`);
            }
        }
    });
}

/**
 * Initialize focus management
 */
function initializeFocusManagement() {
    // Ensure proper tab order
    const interactiveElements = document.querySelectorAll(
        '.btn-flight-action, .recommended-flight a, .financial-card'
    );

    interactiveElements.forEach((element, index) => {
        element.setAttribute('tabindex', index + 1);
    });
}

/**
 * Initialize screen reader support
 */
function initializeScreenReaderSupport() {
    // Create live region for updates
    const liveRegion = document.createElement('div');
    liveRegion.setAttribute('aria-live', 'polite');
    liveRegion.setAttribute('aria-atomic', 'true');
    liveRegion.className = 'sr-only';
    liveRegion.id = 'dashboard-live-region';
    document.body.appendChild(liveRegion);
}

/**
 * Announce updates to screen readers
 */
function announceUpdate(message) {
    const liveRegion = document.getElementById('dashboard-live-region');
    if (liveRegion) {
        liveRegion.textContent = message;

        // Clear after announcement
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 1000);
    }
}

/**
 * Add custom CSS for tooltips and animations
 */
function addCustomCSS() {
    const style = document.createElement('style');
    style.textContent = `
        .custom-tooltip {
            position: fixed;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            z-index: 10000;
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
            max-width: 200px;
            text-align: center;
        }

        .custom-tooltip.show {
            opacity: 1;
        }

        .custom-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.9) transparent transparent transparent;
        }

        .loading {
            pointer-events: none;
            opacity: 0.7;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    `;
    document.head.appendChild(style);
}

// Add custom CSS on load
addCustomCSS();

// Expose global functions for external use
window.DashboardManager = {
    updateNetworkStats,
    updateActiveFlightStatus,
    updateWeatherInfo,
    announceUpdate
};