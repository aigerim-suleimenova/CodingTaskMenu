(function() {
    'use strict';

    const MemberAreaNav = {

        init() {
            this.userDropdown();
            this.cartUpdate();
            this.mobileMenu();
            this.smoothScroll();
        },

        userDropdown() {
            const toggle = document.querySelector('.user-profile-toggle');
            const dropdown = document.querySelector('.user-dropdown-menu');

            if (!toggle || !dropdown) return;

            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

                toggle.setAttribute('aria-expanded', !isExpanded);
                dropdown.setAttribute('aria-hidden', isExpanded);
            });

            document.addEventListener('click', (e) => {
                if (!e.target.closest('.nav-user-profile')) {
                    toggle.setAttribute('aria-expanded', 'false');
                    dropdown.setAttribute('aria-hidden', 'true');
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    toggle.setAttribute('aria-expanded', 'false');
                    dropdown.setAttribute('aria-hidden', 'true');
                }
            });
        },

        cartUpdate() {
            if (typeof wc_add_to_cart_params === 'undefined') {
                return;
            }

            document.body.addEventListener('added_to_cart', () => this.fetchCartCount());
            document.body.addEventListener('removed_from_cart', () => this.fetchCartCount());
            document.body.addEventListener('updated_cart_totals', () => this.fetchCartCount());
        },

        async fetchCartCount() {
            try {
                const formData = new FormData();
                formData.append('action', 'get_cart_count');

                const response = await fetch(wc_add_to_cart_params.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.data.count !== undefined) {
                    this.updateCartBadge(data.data.count);
                }
            } catch (error) {
                console.error('Error fetching cart count:', error);
            }
        },

        updateCartBadge(count) {
            const cartCountElements = document.querySelectorAll('.cart-count, .menu-cart-count');

            if (count > 0) {
                cartCountElements.forEach(elem => {
                    elem.textContent = `(${count})`;
                });

                const cartIcon = document.querySelector('.cart-icon');
                if (cartIcon) {
                    cartIcon.classList.add('cart-updated');
                    setTimeout(() => cartIcon.classList.remove('cart-updated'), 300);
                }
            } else {
                cartCountElements.forEach(elem => elem.remove());
            }
        },

        mobileMenu() {
            const navMain = document.querySelector('.nav-main');

            if (!navMain) return;

            if (!document.querySelector('.mobile-menu-toggle') && window.innerWidth < 768) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'mobile-menu-toggle';
                toggleBtn.setAttribute('aria-label', 'Toggle Menu');
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                navMain.prepend(toggleBtn);
            }

            document.addEventListener('click', (e) => {
                if (e.target.closest('.mobile-menu-toggle')) {
                    const toggle = e.target.closest('.mobile-menu-toggle');
                    const menu = document.querySelector('.memberarea-menu');

                    toggle.classList.toggle('active');
                    menu?.classList.toggle('active');
                    document.body.classList.toggle('menu-open');
                }
            });
        },

        smoothScroll() {
            const anchorLinks = document.querySelectorAll('.memberarea-menu a[href^="#"]');

            anchorLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    const targetId = link.getAttribute('href');
                    const target = document.querySelector(targetId);

                    if (target) {
                        e.preventDefault();

                        const targetPosition = target.offsetTop - 80;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => MemberAreaNav.init());
    } else {
        MemberAreaNav.init();
    }

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            MemberAreaNav.mobileMenu();
        }, 250);
    });

})();

const style = document.createElement('style');
style.textContent = `
    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.2); }
        50% { transform: scale(0.9); }
        75% { transform: scale(1.1); }
    }

    .cart-updated {
        animation: cartBounce 0.6s ease !important;
    }
`;
document.head.appendChild(style);
