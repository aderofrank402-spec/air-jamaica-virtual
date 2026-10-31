/**
 * World-Class Virtual Airline Dashboard
 * Advanced JavaScript Functionality
 * Version: 2.0
 */

class WorldClassDashboard {
    constructor() {
        this.isInitialized = false;
        this.animationObserver = null;
        this.particles = [];
        this.theme = 'light'; // Future dark mode support

        this.init();
    }

    /**
     * Initialize the dashboard
     */
    init() {
        if (this.isInitialized) return;

        this.setupEventListeners();
        this.initializeAnimations();
        this.initializeCounters();
        this.initializeParticles();
        this.initializeTooltips();
        this.initializeInteractivity();
        this.initializeKeyboardShortcuts();
        this.initializePerformanceMonitoring();

        this.isInitialized = true;
        console.log('✈️ World-Class Dashboard initialized successfully');
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Page visibility API for performance optimization
        document.addEventListener('visibilitychange', this.handleVisibilityChange.bind(this));

        // Resize optimization
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(this.handleResize.bind(this), 250);
        });

        // Loading states for navigation
        document.addEventListener('click', this.handleNavigation.bind(this));
    }

    /**
     * Initialize advanced animations
     */
    initializeAnimations() {
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        this.animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateElement(entry.target);
                }
            });
        }, observerOptions);

        // Observe all animatable elements
        document.querySelectorAll('.stat-card, .action-card, .premium-section').forEach(el => {
            this.animationObserver.observe(el);
        });
    }

    /**
     * Animate individual element
     */
    animateElement(element) {
        if (element.classList.contains('animated')) return;

        element.classList.add('animated');

        // Counter animation for stats
        if (element.classList.contains('stat-card')) {
            this.animateCounter(element);
        }

        // Progress bar animation
        const progressBars = element.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            this.animateProgressBar(bar);
        });

        // Stagger child animations
        const children = element.querySelectorAll('.glass-card, .stat-icon, .action-icon');
        children.forEach((child, index) => {
            setTimeout(() => {
                child.style.animation = 'fadeInUp 0.6s ease forwards';
            }, index * 100);
        });
    }

    /**
     * Initialize and animate counters
     */
    initializeCounters() {
        document.querySelectorAll('.stat-number').forEach(counter => {
            if (!counter.dataset.animated) {
                this.setupCounterObserver(counter);
            }
        });
    }

    /**
     * Setup counter observer
     */
    setupCounterObserver(counter) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !counter.dataset.animated) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(counter);
    }

    /**
     * Animate counter with easing
     */
    animateCounter(element) {
        const counter = element.querySelector('.stat-number') || element;
        if (!counter || counter.dataset.animated) return;

        const text = counter.textContent.trim();
        const numberMatch = text.match(/[\d,]+/);

        if (!numberMatch) return;

        const target = parseInt(numberMatch[0].replace(/,/g, ''));
        const duration = Math.min(2000, Math.max(1000, target * 2));
        const prefix = text.substring(0, text.indexOf(numberMatch[0]));
        const suffix = text.substring(text.indexOf(numberMatch[0]) + numberMatch[0].length);

        counter.dataset.animated = 'true';

        this.animateValue(counter, 0, target, duration, (value) => {
            counter.textContent = prefix + this.formatNumber(Math.floor(value)) + suffix;
        });
    }

    /**
     * Animate numerical values with easing
     */
    animateValue(element, start, end, duration, callback) {
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Cubic easing out
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = start + (end - start) * eased;

            callback(current);

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    /**
     * Format numbers with thousands separators
     */
    formatNumber(num) {
        return new Intl.NumberFormat().format(num);
    }

    /**
     * Animate progress bars
     */
    animateProgressBar(progressBar) {
        const targetWidth = progressBar.style.width || '0%';
        progressBar.style.width = '0%';

        setTimeout(() => {
            progressBar.style.transition = 'width 2s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            progressBar.style.width = targetWidth;
        }, 100);
    }

    /**
     * Initialize particle system
     */
    initializeParticles() {
        if (!this.shouldRenderParticles()) return;

        this.createParticleCanvas();
        this.generateParticles();
        this.animateParticles();
    }

    /**
     * Check if particles should be rendered (performance consideration)
     */
    shouldRenderParticles() {
        return window.innerWidth > 768 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    /**
     * Create particle canvas
     */
    createParticleCanvas() {
        this.particleCanvas = document.createElement('canvas');
        this.particleCanvas.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            opacity: 0.1;
        `;

        this.particleCtx = this.particleCanvas.getContext('2d');
        document.body.appendChild(this.particleCanvas);

        this.resizeParticleCanvas();
    }

    /**
     * Resize particle canvas
     */
    resizeParticleCanvas() {
        if (!this.particleCanvas) return;

        this.particleCanvas.width = window.innerWidth;
        this.particleCanvas.height = window.innerHeight;
    }

    /**
     * Generate particles
     */
    generateParticles() {
        const particleCount = Math.min(50, Math.floor(window.innerWidth / 40));

        for (let i = 0; i < particleCount; i++) {
            this.particles.push({
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                size: Math.random() * 3 + 1,
                speedX: (Math.random() - 0.5) * 0.5,
                speedY: (Math.random() - 0.5) * 0.5,
                opacity: Math.random() * 0.5 + 0.2
            });
        }
    }

    /**
     * Animate particles
     */
    animateParticles() {
        if (!this.particleCanvas || !this.shouldRenderParticles()) return;

        this.particleCtx.clearRect(0, 0, this.particleCanvas.width, this.particleCanvas.height);

        this.particles.forEach(particle => {
            // Update position
            particle.x += particle.speedX;
            particle.y += particle.speedY;

            // Wrap around edges
            if (particle.x < 0) particle.x = this.particleCanvas.width;
            if (particle.x > this.particleCanvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = this.particleCanvas.height;
            if (particle.y > this.particleCanvas.height) particle.y = 0;

            // Draw particle
            this.particleCtx.beginPath();
            this.particleCtx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            this.particleCtx.fillStyle = `rgba(102, 126, 234, ${particle.opacity})`;
            this.particleCtx.fill();
        });

        requestAnimationFrame(this.animateParticles.bind(this));
    }

    /**
     * Initialize premium tooltips
     */
    initializeTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');

        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', this.showTooltip.bind(this));
            element.addEventListener('mouseleave', this.hideTooltip.bind(this));
            element.addEventListener('mousemove', this.updateTooltipPosition.bind(this));
        });
    }

    /**
     * Show premium tooltip
     */
    showTooltip(event) {
        const element = event.currentTarget;
        const text = element.getAttribute('data-tooltip');

        if (!text) return;

        this.currentTooltip = document.createElement('div');
        this.currentTooltip.className = 'premium-tooltip';
        this.currentTooltip.innerHTML = text;

        document.body.appendChild(this.currentTooltip);

        // Position tooltip
        this.updateTooltipPosition(event);

        // Animate in
        setTimeout(() => {
            this.currentTooltip.classList.add('visible');
        }, 10);
    }

    /**
     * Update tooltip position
     */
    updateTooltipPosition(event) {
        if (!this.currentTooltip) return;

        const x = event.clientX;
        const y = event.clientY;
        const rect = this.currentTooltip.getBoundingClientRect();

        let left = x + 15;
        let top = y - rect.height - 10;

        // Prevent tooltip from going off-screen
        if (left + rect.width > window.innerWidth) {
            left = x - rect.width - 15;
        }

        if (top < 0) {
            top = y + 15;
        }

        this.currentTooltip.style.left = left + 'px';
        this.currentTooltip.style.top = top + 'px';
    }

    /**
     * Hide tooltip
     */
    hideTooltip() {
        if (this.currentTooltip) {
            this.currentTooltip.classList.remove('visible');
            setTimeout(() => {
                if (this.currentTooltip && this.currentTooltip.parentNode) {
                    this.currentTooltip.parentNode.removeChild(this.currentTooltip);
                }
                this.currentTooltip = null;
            }, 200);
        }
    }

    /**
     * Initialize interactive features
     */
    initializeInteractivity() {
        // Magnetic buttons effect
        document.querySelectorAll('.action-card, .stat-card').forEach(card => {
            card.addEventListener('mousemove', this.magneticEffect.bind(this));
            card.addEventListener('mouseleave', this.resetMagneticEffect.bind(this));
        });

        // Click ripple effect
        document.addEventListener('click', this.createRippleEffect.bind(this));

        // Parallax effect for background elements
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            window.addEventListener('scroll', this.handleParallax.bind(this));
        }
    }

    /**
     * Magnetic effect for interactive elements
     */
    magneticEffect(event) {
        const element = event.currentTarget;
        const rect = element.getBoundingClientRect();
        const x = event.clientX - rect.left - rect.width / 2;
        const y = event.clientY - rect.top - rect.height / 2;

        const distance = Math.sqrt(x * x + y * y);
        const maxDistance = Math.sqrt(rect.width * rect.width + rect.height * rect.height) / 2;
        const strength = Math.max(0, 1 - distance / maxDistance);

        const translateX = x * strength * 0.1;
        const translateY = y * strength * 0.1;

        element.style.transform = `translate(${translateX}px, ${translateY}px) scale(${1 + strength * 0.02})`;
    }

    /**
     * Reset magnetic effect
     */
    resetMagneticEffect(event) {
        const element = event.currentTarget;
        element.style.transform = '';
    }

    /**
     * Create ripple effect on click
     */
    createRippleEffect(event) {
        const target = event.target.closest('.action-card, .btn, .stat-card');
        if (!target) return;

        const rect = target.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        const ripple = document.createElement('div');
        ripple.className = 'ripple-effect';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';

        target.appendChild(ripple);

        // Remove ripple after animation
        setTimeout(() => {
            if (ripple.parentNode) {
                ripple.parentNode.removeChild(ripple);
            }
        }, 600);
    }

    /**
     * Handle parallax scrolling
     */
    handleParallax() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.stat-card, .action-card');

        parallaxElements.forEach((element, index) => {
            const rate = scrolled * -0.1 * (index % 3 + 1);
            element.style.transform = `translateY(${rate}px)`;
        });
    }

    /**
     * Initialize keyboard shortcuts
     */
    initializeKeyboardShortcuts() {
        document.addEventListener('keydown', (event) => {
            // Only trigger when not in input fields
            if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') return;

            if (event.ctrlKey || event.metaKey) {
                switch (event.key.toLowerCase()) {
                    case 'p':
                        event.preventDefault();
                        this.navigateToSection('pirep');
                        break;
                    case 'f':
                        event.preventDefault();
                        this.navigateToSection('flights');
                        break;
                    case 'b':
                        event.preventDefault();
                        this.navigateToSection('bids');
                        break;
                    case 'd':
                        event.preventDefault();
                        this.navigateToSection('downloads');
                        break;
                }
            }
        });
    }

    /**
     * Navigate to specific section
     */
    navigateToSection(section) {
        const links = {
            'pirep': 'a[href*="pireps/create"]',
            'flights': 'a[href*="flights"]',
            'bids': 'a[href*="bids"]',
            'downloads': 'a[href*="downloads"]'
        };

        const link = document.querySelector(links[section]);
        if (link) {
            this.showLoadingState();
            link.click();
        }
    }

    /**
     * Show loading state
     */
    showLoadingState() {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-content">
                <div class="loading-spinner"></div>
                <p>Loading...</p>
            </div>
        `;

        document.body.appendChild(overlay);

        // Auto-remove after 5 seconds if navigation didn't complete
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 5000);
    }

    /**
     * Handle navigation clicks
     */
    handleNavigation(event) {
        const link = event.target.closest('a[href]');
        if (link && link.hostname === location.hostname) {
            this.showLoadingState();
        }
    }

    /**
     * Initialize performance monitoring
     */
    initializePerformanceMonitoring() {
        // Monitor FPS for smooth animations
        let frames = 0;
        let lastTime = performance.now();

        const monitorFPS = (currentTime) => {
            frames++;

            if (currentTime >= lastTime + 1000) {
                const fps = Math.round((frames * 1000) / (currentTime - lastTime));

                // Reduce particles if FPS is low
                if (fps < 30 && this.particles.length > 20) {
                    this.particles = this.particles.slice(0, Math.floor(this.particles.length * 0.7));
                }

                frames = 0;
                lastTime = currentTime;
            }

            requestAnimationFrame(monitorFPS);
        };

        if (this.shouldRenderParticles()) {
            requestAnimationFrame(monitorFPS);
        }
    }

    /**
     * Handle page visibility changes
     */
    handleVisibilityChange() {
        if (document.hidden) {
            // Pause expensive animations when tab is not visible
            this.pauseAnimations();
        } else {
            this.resumeAnimations();
        }
    }

    /**
     * Pause animations for performance
     */
    pauseAnimations() {
        document.querySelectorAll('.floating-element').forEach(el => {
            el.style.animationPlayState = 'paused';
        });
    }

    /**
     * Resume animations
     */
    resumeAnimations() {
        document.querySelectorAll('.floating-element').forEach(el => {
            el.style.animationPlayState = 'running';
        });
    }

    /**
     * Handle window resize
     */
    handleResize() {
        if (this.particleCanvas) {
            this.resizeParticleCanvas();

            // Regenerate particles for new screen size
            if (this.shouldRenderParticles()) {
                this.particles = [];
                this.generateParticles();
            }
        }
    }

    /**
     * Cleanup and destroy
     */
    destroy() {
        if (this.animationObserver) {
            this.animationObserver.disconnect();
        }

        if (this.particleCanvas) {
            this.particleCanvas.parentNode.removeChild(this.particleCanvas);
        }

        if (this.currentTooltip) {
            this.hideTooltip();
        }

        this.isInitialized = false;
    }
}

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.worldClassDashboard = new WorldClassDashboard();
});

// Add premium tooltip styles
const tooltipStyles = `
.premium-tooltip {
    position: fixed;
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    z-index: 10000;
    pointer-events: none;
    transform: translateY(10px);
    opacity: 0;
    transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    max-width: 250px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.premium-tooltip.visible {
    transform: translateY(0);
    opacity: 1;
}

.premium-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: rgba(0, 0, 0, 0.9) transparent transparent transparent;
}

.ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.3);
    width: 20px;
    height: 20px;
    margin-left: -10px;
    margin-top: -10px;
    animation: ripple 0.6s ease-out;
    pointer-events: none;
}

@keyframes ripple {
    0% {
        transform: scale(0);
        opacity: 1;
    }
    100% {
        transform: scale(20);
        opacity: 0;
    }
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(102, 126, 234, 0.9);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease;
}

.loading-content {
    text-align: center;
    color: white;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = tooltipStyles;
document.head.appendChild(styleSheet);